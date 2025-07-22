<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Services\InfobipService;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ClientsExport;
use Barryvdh\DomPDF\Facade\Pdf;

class ClientController extends Controller
{

    protected $twilioService;
    /**
     * Affiche la liste paginée des clients avec les statistiques globales.
     * Prend en charge la recherche.
     */
    public function index(Request $request)
    {
        $query = Client::query();

        if ($request->filled('search')) {
            $search = strtolower($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(nom_client) LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(contact) LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(sites_relais) LIKE ?', ["%{$search}%"]);
            });
        }

        $clients = $query->orderBy('id')->paginate(10);

        $totalClientsCount = Client::count();
        $payes = Client::where('a_paye', 1)->count();
        $nonPayes = $totalClientsCount - $payes;
        $actifs = Client::where('statut', 'actif')->count();
        $suspendus = Client::where('statut', 'suspendu')->count();

        return view('clients.index', compact('clients', 'totalClientsCount', 'payes', 'nonPayes', 'actifs', 'suspendus'));
    }
    public function clientsPayes(Request $request)
    {
        $query = Client::where('statut', 'actif')
                    ->where('a_paye', 1);

        if ($request->filled('search')) {
            $search = strtolower($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(nom_client) LIKE ?', ["%{$search}%"])
                ->orWhereRaw('LOWER(contact) LIKE ?', ["%{$search}%"])
                ->orWhereRaw('LOWER(sites_relais) LIKE ?', ["%{$search}%"]);
            });
        }

        $clients = $query->orderBy('id')->paginate(10);

        $totalClientsCount = Client::count();
        $actifs = Client::where('statut', 'actif')->count();
        $suspendus = Client::where('statut', 'suspendu')->count();
        $totalPayes = Client::where('statut', 'actif')->where('a_paye', 1)->count(); // nombre de clients actifs et payés
        $nonPayes = Client::where('a_paye', 0)->count();

        return view('clients.payes', compact('clients', 'actifs', 'suspendus', 'totalClientsCount', 'totalPayes', 'nonPayes'));
    }

    public function nonPayes(Request $request)
    {
        $query = Client::where('statut', 'actif')
                       ->where('a_paye', 0);

        if ($request->filled('search')) {
            $search = strtolower($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(nom_client) LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(contact) LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(sites_relais) LIKE ?', ["%{$search}%"]);
            });
        }
        
        $clients = $query->orderBy('id')->paginate(10);

        $totalClientsCount = Client::count();
        $payes = Client::where('a_paye', 1)->count();
        $nonPayes = Client::where('a_paye', 0)->count();
        $actifs = Client::where('statut', 'actif')->count();
        $suspendus = Client::where('statut', 'suspendu')->count();

        return view('clients.nonpayes', compact('clients', 'totalClientsCount', 'payes', 'nonPayes', 'actifs', 'suspendus'));
    }

    public function ajaxList(Request $request)
    {
        $query = Client::query();

        if ($request->filled('search')) {
            $search = strtolower($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(nom_client) LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(contact) LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(sites_relais) LIKE ?', ["%{$search}%"]);
            });
        }

        $clients = $query->orderByDesc('created_at')->get();

        return view('clients.partials.client_list', compact('clients'));
    }

    public function aReabonnement(Request $request)
    {
        $dateLimite = Carbon::now()->addDays(3)->toDateString();

        $query = Client::whereDate('date_reabonnement', '<=', $dateLimite)
                       ->whereDate('date_reabonnement', '>=', Carbon::today());

        if ($request->filled('search')) {
            $search = strtolower($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(nom_client) LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(contact) LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(sites_relais) LIKE ?', ["%{$search}%"]);
            });
        }

        $clients = $query->paginate(10);

        $totalClientsCount = Client::count();
        $payes = Client::where('a_paye', 1)->count();
        $nonPayes = $totalClientsCount - $payes;
        $actifs = Client::where('statut', 'actif')->count();
        $suspendus = Client::where('statut', 'suspendu')->count();

        return view('clients.reabonnement', compact('clients', 'totalClientsCount', 'payes', 'nonPayes', 'actifs', 'suspendus'));
    }

    public function depasses(Request $request)
    {
        $today = Carbon::today();

        $query = Client::whereDate('date_reabonnement', '<', $today)
                       ->where('statut', 'actif');

        if ($request->filled('search')) {
            $search = strtolower($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(nom_client) LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(contact) LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(sites_relais) LIKE ?', ["%{$search}%"]);
            });
        }

        $clients = $query->paginate(10);

        $totalClientsCount = Client::count();
        $payes = Client::where('a_paye', 1)->count();
        $nonPayes = $totalClientsCount - $payes;
        $actifs = Client::where('statut', 'actif')->count();
        $suspendus = Client::where('statut', 'suspendu')->count();

        return view('clients.depasses', compact('clients', 'totalClientsCount', 'payes', 'nonPayes', 'actifs', 'suspendus'));
    }

    public function clientsActifs(Request $request)
    {
        $query = Client::where('statut', 'actif');

        if ($request->filled('search')) {
            $search = strtolower($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(nom_client) LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(contact) LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(sites_relais) LIKE ?', ["%{$search}%"]);
            });
        }

        $clients = $query->paginate(10);

        $totalClientsCount = Client::count();
        $payes = Client::where('a_paye', 1)->count();
        $nonPayes = Client::where('a_paye', 0)->count();
        $actifs = Client::where('statut', 'actif')->count();
        $suspendus = Client::where('statut', 'suspendu')->count();

        return view('clients.actifs', compact('clients', 'totalClientsCount', 'payes', 'nonPayes', 'actifs', 'suspendus'));
    }

    public function clientsSuspendus(Request $request)
    {
        $query = Client::where('statut', 'suspendu');

        if ($request->filled('search')) {
            $search = strtolower($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(nom_client) LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(contact) LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(sites_relais) LIKE ?', ["%{$search}%"]);
            });
        }

        $clients = $query->paginate(10);

        $totalClientsCount = Client::count();
        $payes = Client::where('a_paye', 1)->count();
        $nonPayes = Client::where('a_paye', 0)->count();
        $actifs = Client::where('statut', 'actif')->count();
        $suspendus = Client::where('statut', 'suspendu')->count();

        return view('clients.suspendus', compact('clients', 'totalClientsCount', 'payes', 'nonPayes', 'actifs', 'suspendus'));
    }

    public function reconnecter($id)
    {
        $client = Client::findOrFail($id);
        $client->a_paye = 1;
        $client->save();

        return redirect()->back()->with('success', 'Client reconnecté avec succès.');
    }

    public function deconnecter($id)
    {
        $client = Client::findOrFail($id);
        $client->a_paye = 0;
        $client->save();

        return redirect()->back()->with('success', 'Client déconnecté avec succès (non payé).');
    }

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

    public function create()
    {
        return view('clients.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nom_client'        => 'required|string|max:255',
            'contact'           => 'required|string|max:20',
            'sites_relais'      => 'nullable|string|max:255',
            'statut'            => 'nullable|string|in:actif,inactif,suspendu',
            'categorie'         => 'nullable|string|max:100',
            'jour_reabonnement' => 'required|integer|min:1|max:31',
            'montant'           => 'required|numeric|min:0',
            'a_paye'            => 'nullable|boolean',
        ]);

        $validatedData['date_reabonnement'] = $this->calculerDateReabonnement($validatedData['jour_reabonnement']);
        $validatedData['a_paye'] = $request->input('a_paye', 0);

        Client::create($validatedData);

        return redirect()->route('clients.index')->with('success', 'Client ajouté avec succès !');
    }

    public function edit(Client $client)
    {
        return view('clients.edit', compact('client'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nom_client'        => 'required|string|max:255',
            'contact'           => 'required|string|max:255',
            'sites_relais'      => 'nullable|string|max:255',
            'statut'            => 'required|in:actif,inactif,suspendu',
            'categorie'         => 'nullable|string|max:255',
            'jour_reabonnement' => 'required|integer|min:1|max:31',
            'date_reabonnement' => 'required|date',
            'montant'           => 'required|numeric',
            'a_paye'            => 'required|in:0,1',
        ]);

        $client = Client::findOrFail($id);

        $client->nom_client        = $request->nom_client;
        $client->contact           = $request->contact;
        $client->sites_relais      = $request->sites_relais;
        $client->statut            = $request->statut;
        $client->categorie         = $request->categorie;
        $client->jour_reabonnement = $request->jour_reabonnement;
        $client->date_reabonnement = $request->date_reabonnement;
        $client->montant           = $request->montant;
        $client->a_paye            = (int) $request->a_paye;

        $client->save();

        return redirect()->route('clients.index')->with('success', 'Client modifié avec succès.');
    }

    public function suspendre(Client $client)
    {
        $client->statut = 'suspendu';
        $client->save();

        return redirect()->route('clients.index')->with('success', 'Client suspendu avec succès.');
    }

    public function marquerCommePaye(Client $client)
    {
        $client->a_paye = 1;
        $client->save();

        return redirect()->back()->with('success', 'Client marqué comme payé.');
    }

    public function relancerViaWhatsApp(Client $client, InfobipService $infobip)
    {
        $numero = preg_replace('/[^0-9]/', '', $client->contact);
        if (strlen($numero) === 8) {
            $numero = '229' . $numero;
        }
        $numeroWhatsapp = "+$numero";
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

        $numero = preg_replace('/[^0-9]/', '', $client->contact);
        if (strlen($numero) === 8) {
            $numero = '229' . $numero;
        }

        $date = $client->date_reabonnement 
            ? Carbon::parse($client->date_reabonnement)->format('d/m/Y') 
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

            if ($date->isPast()) {
                $date = $date->addMonth();
            }

            return $date->toDateString();
        } catch (\Exception $e) {
            \Log::error("Erreur lors du calcul de la date de réabonnement pour le jour {$jour}: " . $e->getMessage());
            return null;
        }
    }

    public function mettreAJourDatesReabonnement()
    {
        $clients = Client::whereNotNull('jour_reabonnement')->get();

        foreach ($clients as $client) {
            $jour = $client->jour_reabonnement;

            try {
                $nouvelleDate = Carbon::create(now()->year, now()->month, $jour);

                if ($nouvelleDate->isPast()) {
                    $nouvelleDate->addMonth();
                }

                $client->date_reabonnement = $nouvelleDate->toDateString();
                $client->save();
            } catch (\Exception $e) {
                \Log::warning("Impossible de mettre à jour la date de réabonnement pour le client ID: {$client->id}, jour: {$jour}. Erreur: " . $e->getMessage());
                continue;
            }
        }

        return response()->json(['message' => 'Dates de réabonnement mises à jour avec succès.']);
    }

    public function show(Client $client)
    {
        return view('clients.show', compact('client'));
    }
    public function reactiver($id)
    {
        $client = Client::findOrFail($id);
        $client->statut = 'actif';
        $client->save();

        return redirect()->back()->with('success', 'Client réactivé avec succès.');
    }

    
    public function export(Request $request)
    {
        $type = $request->input('type', 'all');

        // Récupérer les clients selon le type (exemple : réabonnements dépassés)
        if ($type === 'expired') {
            $clients = Client::where('date_reabonnement', '<', now())->get();
        } else {
            $clients = Client::all();
        }

        // Générer le PDF avec la vue
        $pdf = Pdf::loadView('clients.export_pdf', compact('clients'));

        // Télécharger le PDF
        return $pdf->download('clients_reabonnement_depasses.pdf');
    }
    public function exportPdf()
    {
        $clients = Client::where('statut', 'actif')->get();

        $pdf = Pdf::loadView('clients.pdf', compact('clients'));
        return $pdf->download('clients_actifs.pdf');
    }


}
