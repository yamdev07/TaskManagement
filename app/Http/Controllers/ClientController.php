<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

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
            'date_reabonnement' => 'required|date',
            'montant'           => 'required|numeric|min:0',
            // On utilise 0 ou 1 pour a_paye en base (entier), donc validation adaptée
            'a_paye'            => 'nullable|boolean',
        ]);

        // Par défaut non payé si non renseigné
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
        // Validation des données
        $request->validate([
            'nom_client' => 'required|string|max:255',
            'contact' => 'required|string|max:255',
            'sites_relais' => 'nullable|string|max:255',
            'statut' => 'required|in:actif,inactif,suspendu',
            'categorie' => 'nullable|string|max:255',
            'date_reabonnement' => 'required|date',
            'montant' => 'required|numeric',
            'a_paye' => 'required|boolean',
        ]);

        // Récupération du client
        $client = Client::findOrFail($id);

        // Mise à jour des données
        $client->nom_client = $request->nom_client;
        $client->contact = $request->contact;
        $client->sites_relais = $request->sites_relais;
        $client->statut = $request->statut;
        $client->categorie = $request->categorie;
        $client->date_reabonnement = $request->date_reabonnement;
        $client->montant = $request->montant;
        $client->a_paye = $request->a_paye;

        $client->save();

        // Redirection vers la liste avec message de succès
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
    
    public function relancerViaWhatsApp(Client $client)
    {
        // Infos de config à placer dans .env
        $token = config('services.whatsapp.token');
        $phoneId = config('services.whatsapp.phone_id');

        // Numéro formaté pour l'envoi (ex : 229XXXXXXXX)
        $numero = preg_replace('/\D/', '', $client->contact); // Nettoyage si besoin

        $message = "Bonjour {$client->nom_client}, votre abonnement expire bientôt. Veuillez renouveler via ce lien : https://anyxtech.com/reabonnement. Merci.";

        $response = Http::withToken($token)->post("https://graph.facebook.com/v19.0/{$phoneId}/messages", [
            'messaging_product' => 'whatsapp',
            'to' => $numero,
            'type' => 'text',
            'text' => [
                'body' => $message
            ]
        ]);

        if ($response->successful()) {
            return back()->with('success', 'Message WhatsApp envoyé avec succès.');
        } else {
            return back()->with('error', 'Échec de l’envoi WhatsApp : ' . $response->body());
        }
    }
    
}
