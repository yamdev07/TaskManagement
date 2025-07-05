<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Services\InfobipService;
use Illuminate\Support\Facades\Http;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $query = Client::query();

        if ($request->filled('search')) {
            $search = strtolower($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(nom_client) LIKE ?', ["%{$search}%"])
                ->orWhereRaw('LOWER(sites_relais) LIKE ?', ["%{$search}%"]);
            });
        }

        $clients = $query->orderBy('id')->get();

        // Statistiques globales
        $actifs = Client::where('statut', 'actif')->count();
        $suspendus = Client::where('statut', 'suspendu')->count();

        // Comptage des paiements
        $payes = Client::where('a_paye', 1)->count();
        $nonPayes = Client::where('a_paye', 0)->count();

        return view('clients.index', compact('clients', 'actifs', 'suspendus', 'payes', 'nonPayes'));
    }

    public function ajaxList()
    {
        $clients = Client::orderByDesc('created_at')->get();
        return view('clients.partials.client_list', compact('clients'));
    }


    // Clients dont le réabonnement est dans 3 jours ou moins
    public function aReabonnement()
    {
        $dateLimite = Carbon::now()->addDays(3)->toDateString();

        $clients = Client::whereDate('date_reabonnement', '<=', $dateLimite)
                         ->whereDate('date_reabonnement', '>=', Carbon::today())
                         ->get();

        return view('clients.reabonnement', compact('clients'));
    }

    // Clients dont le réabonnement est dépassé (avant aujourd'hui)
   public function depasses()
{
    $today = \Carbon\Carbon::today();

    $clients = Client::whereDate('date_reabonnement', '<', $today)
                     ->where('statut', 'actif') // ou autre condition selon ta logique métier
                     ->get();

    return view('clients.depasses', compact('clients'));
}


    // Liste des clients payés
    public function clientsPayes()
    {
        // Clients actifs et payés
        $clients = Client::where('statut', 'actif')
                        ->where('a_paye', 1)
                        ->orderBy('id')
                        ->get();

        // Comptages pour stats si besoin
        $actifs = Client::where('statut', 'actif')->count();
        $suspendus = Client::where('statut', 'suspendu')->count();

        $total = $clients->count();
        $nonPayes = Client::where('a_paye', 0)->count();

        return view('clients.payes', compact('clients', 'actifs', 'suspendus', 'total', 'nonPayes'));
    }


    // Liste des clients non payés
    public function nonPayes()
    {
        // Clients actifs qui n'ont pas payé
        $clients = Client::where('statut', 'actif')
                        ->where('a_paye', 0)
                        ->orderBy('id')
                        ->get();

        $total = $clients->count();

        // Attention à bien passer 'total' dans le compact
        return view('clients.nonpayes', compact('clients', 'total'));
    }



    // Mettre un client en statut payé (reconnexion)
    public function reconnecter($id)
    {
        $client = Client::findOrFail($id);
        $client->a_paye = 1;
        $client->save();

        return redirect()->back()->with('success', 'Client reconnecté avec succès.');
    }

    // Mettre un client en statut non payé (déconnexion)
    public function deconnecter($id)
    {
        $client = Client::findOrFail($id);
        $client->a_paye = 0;
        $client->save();

        return redirect()->back()->with('success', 'Client déconnecté avec succès (non payé).');
    }

    // Envoi des notifications aux clients dont le réabonnement approche dans 7 jours
    public function envoyerNotifications()
    {
        $aujourdhui = Carbon::today();
        $dans7jours = Carbon::today()->addDays(7);

        $clients = Client::whereBetween('date_reabonnement', [$aujourdhui, $dans7jours])
                         ->whereNotNull('email')
                         ->get();

        foreach ($clients as $client) {
            Mail::raw(
                "Bonjour {$client->nom_client}, votre date de réabonnement approche. Merci de renouveler via ce lien : https://anyxtech.com/reabonnement", 
                function ($message) use ($client) {
                    $message->to($client->email)
                            ->subject('Réabonnement proche');
                }
            );
        }

        return redirect()->route('clients.index')->with('success', 'Notifications envoyées avec succès.');
    }

    // Formulaire création client
    public function create()
    {
        return view('clients.create');
    }

    // Stockage nouveau client
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nom_client'        => 'required|string|max:255',
            'contact'           => 'required|string|max:20',
            'sites_relais'      => 'nullable|string|max:255',
            'statut'            => 'nullable|string|in:actif,inactif,suspendu',
            'categorie'         => 'nullable|string|max:100',
            'jour_reabonnement' => 'required|integer|min:1|max:31', // nouveau champ
            'montant'           => 'required|numeric|min:0',
            'a_paye'            => 'nullable|boolean',
        ]);

        // Calcul automatique de la date de réabonnement à partir du jour
        $validatedData['date_reabonnement'] = $this->calculerDateReabonnement($validatedData['jour_reabonnement']);
        $validatedData['a_paye'] = $request->input('a_paye', 0);

        Client::create($validatedData);

        return redirect()->route('clients.index')->with('success', 'Client ajouté avec succès !');
    }

    // Formulaire édition client
    public function edit(Client $client)
    {
        return view('clients.edit', compact('client'));
    }

    // Mise à jour client
   public function update(Request $request, $id)
    {
        $request->validate([
            'nom_client'        => 'required|string|max:255',
            'contact'           => 'required|string|max:255',
            'sites_relais'      => 'nullable|string|max:255',
            'statut'            => 'required|in:actif,inactif,suspendu',
            'categorie'         => 'nullable|string|max:255',
            'jour_reabonnement' => 'required|integer|min:1|max:31',
            'montant'           => 'required|numeric',
            'a_paye'            => 'required|boolean',
        ]);

        $client = Client::findOrFail($id);

        $client->nom_client         = $request->nom_client;
        $client->contact            = $request->contact;
        $client->sites_relais       = $request->sites_relais;
        $client->statut             = $request->statut;
        $client->categorie          = $request->categorie;
        $client->jour_reabonnement  = $request->jour_reabonnement;
        $client->date_reabonnement  = $this->calculerDateReabonnement($request->jour_reabonnement);
        $client->montant            = $request->montant;
        $client->a_paye             = $request->a_paye;

        $client->save();

        return redirect()->route('clients.index')->with('success', 'Client modifié avec succès.');
    }




    // Liste clients actifs
    public function clientsActifs()
    {
        $clients = Client::where('statut', 'actif')->get();
        return view('clients.actifs', compact('clients'));
    }

    // Liste clients suspendus avec recherche
    public function clientsSuspendus(Request $request)
    {
        $query = Client::where('statut', 'suspendu');

        if ($request->filled('search')) {
            $search = strtolower($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(nom_client) LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(sites_relais) LIKE ?', ["%{$search}%"]);
            });
        }

        $clients = $query->orderBy('id')->get();

        return view('clients.suspendus', compact('clients'));
    }
    
    public function relancerViaWhatsApp(Client $client, InfobipService $infobip)
    {
        // Numéro internationalisé (229xxxxxxxx)
        $numero = preg_replace('/[^0-9]/', '', $client->contact);
        if (strlen($numero) === 8) {
            $numero = '229' . $numero;
        }

        // Infobip requiert le format E.164 avec préfixe WhatsApp
        $numeroWhatsapp = "+$numero"; // Pas besoin d'ajouter 'whatsapp:' devant avec Infobip

        $nomClient = $client->nom_client;

        $success = $infobip->sendWhatsAppTemplate($numeroWhatsapp, $nomClient);

        if ($success) {
            return back()->with('success', "Message WhatsApp envoyé à {$nomClient} via Infobip.");
        } else {
            return back()->with('error', "Échec de l’envoi WhatsApp via Infobip.");
        }
    }


    public function relancer($id, InfobipService $infobip)
    {
        $client = Client::findOrFail($id);

        // Nettoyer le numéro : ici on part du principe qu'il faut ajouter l'indicatif Bénin '229'
        $numero = preg_replace('/[^0-9]/', '', $client->contact);
        if (strlen($numero) === 8) {
            $numero = '229' . $numero;
        }

        $date = $client->date_reabonnement 
            ? \Carbon\Carbon::parse($client->date_reabonnement)->format('d/m/Y') 
            : 'bientôt';

        $message = "Bonjour {$client->nom_client}, votre réabonnement arrive à échéance le {$date}. Merci de penser à renouveler votre abonnement pour éviter toute interruption. - AnyxTech";

        $success = $infobip->sendSms($numero, $message);

        if ($success) {
            return redirect()->back()->with('success', "Message envoyé avec succès à {$client->nom_client}.");
        } else {
            return redirect()->back()->with('error', "Erreur lors de l'envoi du message.");
        }
    }

   private function calculerDateReabonnement($jour)
    {
        $mois = now()->month;
        $annee = now()->year;

        try {
            $date = Carbon::create($annee, $mois, $jour);

            // Si la date est déjà passée ce mois-ci, passer au mois suivant
            if ($date->isPast()) {
                $date = $date->addMonth();
            }

            return $date->toDateString(); // Format Y-m-d
        } catch (\Exception $e) {
            return null; // Pour gérer le cas des jours invalides (31 février, etc.)
        }
    }


    public function mettreAJourDatesReabonnement()
    {
        $clients = Client::whereNotNull('jour_reabonnement')->get();

        foreach ($clients as $client) {
            $jour = $client->jour_reabonnement;

            // Crée une date avec le jour enregistré + mois et année actuels
            try {
                $nouvelleDate = Carbon::create(now()->year, now()->month, $jour);

                // Si la nouvelle date est dans le passé, on passe au mois suivant
                if ($nouvelleDate->isPast()) {
                    $nouvelleDate->addMonth();
                }

                $client->date_reabonnement = $nouvelleDate->toDateString();
                $client->save();
            } catch (\Exception $e) {
                // Évite de planter si le jour est invalide pour ce mois (ex: 31 février)
                continue;
            }
        }

        return response()->json(['message' => 'Dates de réabonnement mises à jour avec succès.']);
    }


}
