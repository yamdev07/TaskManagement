<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Services\InfobipService;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Paiement;

class ClientController extends Controller
{
    protected $twilioService;

    // --- Méthode privée pour gérer paiement et réabonnement ---

    private function payerEtReabonner(Client $client)
    {
        $today = Carbon::today();

        // 1️⃣ Récupérer le mois impayé le plus ancien
        $moisImpaye = $client->paiements()
            ->where('statut', false)
            ->orderBy('annee')
            ->orderBy('mois')
            ->first();

        if ($moisImpaye) {
            // On marque ce mois comme payé
            $moisImpaye->statut = true;
            $moisImpaye->date_paiement = $today;
            $moisImpaye->save();

            $mois = $moisImpaye->mois;
            $annee = $moisImpaye->annee;
        } else {
            // Si tous les mois sont payés, on crée le paiement pour le mois courant
            $mois = $today->month;
            $annee = $today->year;

            Paiement::create([
                'client_id'     => $client->id,
                'mois'          => $mois,
                'annee'         => $annee,
                'statut'        => true,
                'montant'       => $client->montant,
                'date_paiement' => $today,
            ]);
        }

        // 2️⃣ Mise à jour de la date de réabonnement sur le mois payé seulement
        if ($client->jour_reabonnement) {
            $jour = min($client->jour_reabonnement, Carbon::create($annee, $mois, 1)->endOfMonth()->day);
            $client->date_reabonnement = Carbon::create($annee, $mois, $jour)->toDateString();
        }

        // 3️⃣ Statut actif et payé pour ce mois
        $client->statut = 'actif';
        $client->a_paye = 1;

        $client->save();
    }

    private function prochainMoisDû(Client $client)
    {
        // Cherche le mois impayé le plus ancien
        $moisImpaye = $client->paiements()
            ->where('statut', false)
            ->orderBy('annee')
            ->orderBy('mois')
            ->first();

        if ($moisImpaye) {
            // Si un mois impayé existe, c'est le prochain à relancer
            return Carbon::create($moisImpaye->annee, $moisImpaye->mois, $client->jour_reabonnement);
        }

        // Si tous les mois sont payés, le prochain mois dû = mois suivant le dernier paiement
        $dernierPaiement = $client->paiements()->orderBy('annee', 'desc')->orderBy('mois', 'desc')->first();
        if ($dernierPaiement) {
            $mois = $dernierPaiement->mois + 1;
            $annee = $dernierPaiement->annee;
            if ($mois > 12) {
                $mois = 1;
                $annee += 1;
            }
            return Carbon::create($annee, $mois, $client->jour_reabonnement);
        }

        // Si aucun paiement, le mois dû = mois courant
        return Carbon::now();
    }

    // --- Méthode privée pour récupérer les statistiques ---
    private function getStats()
    {
        $today = Carbon::today();
        $moisCourant = $today->month;
        $anneeCourante = $today->year;

        $totalClientsCount = Client::count();
        $payes = Client::whereHas('paiements', function($q) use ($moisCourant, $anneeCourante){
            $q->where('mois', $moisCourant)->where('annee', $anneeCourante)->where('statut', true);
        })->count();
        $nonPayes = $totalClientsCount - $payes;
        $actifs = Client::where('statut', 'actif')->count();
        $suspendus = Client::where('statut', 'suspendu')->count();

        return compact('totalClientsCount', 'payes', 'nonPayes', 'actifs', 'suspendus');
    }

    // --- INDEX (liste clients avec stats) ---
    public function index(Request $request)
    {
        $today = Carbon::today();
        $moisCourant = $today->month;
        $anneeCourante = $today->year;

        // Récupérer tous les clients pour mise à jour dynamique
        $clientsAll = Client::all();
        foreach ($clientsAll as $client) {
            // Vérifie si le client a payé ce mois
            $paiement = $client->paiements()
                ->where('mois', $moisCourant)
                ->where('annee', $anneeCourante)
                ->where('statut', true)
                ->first();
            $client->a_paye = $paiement ? 1 : 0;

            // CORRECTION : Logique de suspension automatique UNIQUEMENT si le statut n'est pas déjà géré manuellement
            // On ne change pas le statut si le client a été suspendu manuellement
            if ($client->date_reabonnement && $client->statut !== 'suspendu') {
                $dateReabonnement = Carbon::parse($client->date_reabonnement);
                
                // Calculer la date limite (date_reabonnement + 2 mois)
                $dateLimite = $dateReabonnement->copy()->addMonths(2);
                
                // Suspension automatique si la date actuelle dépasse la date limite
                if ($today->gt($dateLimite)) {
                    $client->statut = 'suspendu';
                }
            }

            // Calcul du prochain mois dû
            $client->prochain_mois_du = $this->prochainMoisDû($client)->format('Y-m-d');

            $client->save();
        }

        // Requête avec recherche et pagination
        $query = Client::query();
        if ($request->filled('search')) {
            $search = strtolower($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(nom_client) LIKE ?', ["%{$search}%"])
                ->orWhereRaw('LOWER(contact) LIKE ?', ["%{$search}%"])
                ->orWhereRaw('LOWER(sites_relais) LIKE ?', ["%{$search}%"]);
            });
        }

        $clients = $query->orderBy('id')->paginate(10)->appends($request->all());

        // Statistiques
        $stats = [
            'totalClientsCount' => Client::count(),
            'payes' => Client::whereHas('paiements', function($q) use ($moisCourant, $anneeCourante){
                $q->where('mois', $moisCourant)->where('annee', $anneeCourante)->where('statut', true);
            })->count(),
            'nonPayes' => Client::count() - Client::whereHas('paiements', function($q) use ($moisCourant, $anneeCourante){
                $q->where('mois', $moisCourant)->where('annee', $anneeCourante)->where('statut', true);
            })->count(),
            'actifs' => Client::where('statut','actif')->count(),
            'suspendus' => Client::where('statut','suspendu')->count(),
        ];

        $stats['clientsReabonnementProche'] = Client::whereDate('date_reabonnement', '<=', $today->copy()->addDays(5))
                                                    ->whereDate('date_reabonnement', '>=', $today)
                                                    ->count();

        $stats['clientsReabonnementDepasse'] = Client::where('date_reabonnement', '<', $today)->count();

        return view('clients.index', array_merge(compact('clients'), $stats));
    }

    // --- CLIENTS PAYES ---
    public function clientsPayes(Request $request)
    {
        $today = Carbon::today();
        $moisCourant = $today->month;
        $anneeCourante = $today->year;

        $query = Client::whereHas('paiements', function($q) use ($moisCourant, $anneeCourante){
            $q->where('mois', $moisCourant)->where('annee', $anneeCourante)->where('statut', true);
        });

        if ($request->filled('search')) {
            $search = strtolower($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(nom_client) LIKE ?', ["%{$search}%"])
                ->orWhereRaw('LOWER(contact) LIKE ?', ["%{$search}%"])
                ->orWhereRaw('LOWER(sites_relais) LIKE ?', ["%{$search}%"]);
            });
        }

        $clients = $query->orderBy('id')->paginate(10)->appends($request->all());

        $stats = [
            'totalClientsCount' => Client::count(),
            'payes' => $query->count(),
            'nonPayes' => Client::count() - $query->count(),
            'actifs' => Client::where('statut','actif')->count(),
            'suspendus' => Client::where('statut','suspendu')->count(),
        ];

        return view('clients.payes', array_merge(compact('clients'), $stats));
    }

    // --- CLIENTS NON PAYES ---
    public function nonPayes(Request $request)
    {
        $today = Carbon::today();
        $moisCourant = $today->month;
        $anneeCourante = $today->year;

        $query = Client::whereDoesntHave('paiements', function($q) use ($moisCourant, $anneeCourante){
            $q->where('mois', $moisCourant)->where('annee', $anneeCourante)->where('statut', true);
        });

        if ($request->filled('search')) {
            $search = strtolower($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(nom_client) LIKE ?', ["%{$search}%"])
                ->orWhereRaw('LOWER(contact) LIKE ?', ["%{$search}%"])
                ->orWhereRaw('LOWER(sites_relais) LIKE ?', ["%{$search}%"]);
            });
        }

        $clients = $query->orderBy('id')->paginate(10)->appends($request->all());

        $stats = [
            'totalClientsCount' => Client::count(),
            'payes' => Client::whereHas('paiements', function($q) use ($moisCourant, $anneeCourante){
                $q->where('mois', $moisCourant)->where('annee', $anneeCourante)->where('statut', true);
            })->count(),
            'nonPayes' => $query->count(),
            'actifs' => Client::where('statut','actif')->count(),
            'suspendus' => Client::where('statut','suspendu')->count(),
        ];

        return view('clients.nonpayes', array_merge(compact('clients'), $stats));
    }

    // --- Liste clients à réabonnement proche ---
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

        $clients = $query->paginate(10)->appends($request->all());
        $stats = $this->getStats();

        return view('clients.reabonnement', array_merge(compact('clients'), $stats));
    }

    // --- Liste clients dépassés ---
    public function depasses(Request $request)
    {
        $today = Carbon::today();
        $moisCourant = $today->month;
        $anneeCourante = $today->year;

        $query = Client::where('statut', 'actif')
            ->whereDoesntHave('paiements', function($q) use ($moisCourant, $anneeCourante){
                $q->where('mois', $moisCourant)->where('annee', $anneeCourante)->where('statut', true);
            })
            ->whereDate('date_reabonnement', '<', $today);

        if ($request->filled('search')) {
            $search = strtolower($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(nom_client) LIKE ?', ["%{$search}%"])
                ->orWhereRaw('LOWER(contact) LIKE ?', ["%{$search}%"])
                ->orWhereRaw('LOWER(sites_relais) LIKE ?', ["%{$search}%"]);
            });
        }

        $clients = $query->paginate(10)->appends($request->all());
        $stats = $this->getStats();

        return view('clients.depasses', array_merge(compact('clients'), $stats));
    }

    // --- Liste clients actifs ---
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

        $clients = $query->paginate(10)->appends($request->all());
        $stats = $this->getStats();

        return view('clients.actifs', array_merge(compact('clients'), $stats));
    }

    // --- Liste clients suspendus ---
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

        $clients = $query->paginate(10)->appends($request->all());
        $stats = $this->getStats();

        return view('clients.suspendus', array_merge(compact('clients'), $stats));
    }

    // --- Marquer comme payé ---
    public function marquerCommePaye(Client $client)
    {
        $this->payerEtReabonner($client);

        // Statut payé + actif
        $client->a_paye = 1;
        $client->statut = 'actif';
        $client->save();

        return redirect()->back()->with('success', 'Client marqué comme payé et date de réabonnement mise à jour.');
    }

    public function reconnecter($id)
    {
        $client = Client::findOrFail($id);
        $this->payerEtReabonner($client);

        return redirect()->back()->with('success', 'Client reconnecté et date de réabonnement mise à jour.');
    }

    public function deconnecter($id)
    {
        $client = Client::findOrFail($id);
        $client->a_paye = 0;
        $client->save();

        return redirect()->back()->with('success', 'Client déconnecté avec succès (non payé).');
    }

    // --- Création, modification, suppression ---
    public function create() { return view('clients.create'); }

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

        // CORRECTION : Calcul automatique de la date de réabonnement
        $today = Carbon::today();
        $jour = min($validatedData['jour_reabonnement'], $today->endOfMonth()->day);
        $validatedData['date_reabonnement'] = Carbon::create($today->year, $today->month, $jour)->toDateString();
        
        $validatedData['a_paye'] = $request->input('a_paye', 0);

        Client::create($validatedData);

        return redirect()->route('clients.index')->with('success', 'Client ajouté avec succès !');
    }

    public function edit(Client $client) { 
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
            'montant'           => 'required|numeric',
            'a_paye'            => 'required|in:0,1',
        ]);

        $client = Client::findOrFail($id);

        // Mise à jour des informations
        $client->nom_client        = $request->nom_client;
        $client->contact           = $request->contact;
        $client->sites_relais      = $request->sites_relais;
        $client->statut            = $request->statut; // ⚡ Respecte le choix manuel
        $client->categorie         = $request->categorie;
        $client->jour_reabonnement = $request->jour_reabonnement;
        $client->montant           = $request->montant;
        $client->a_paye            = (int) $request->a_paye;

        // CORRECTION : Recalculer la date de réabonnement
        $client->date_reabonnement = $this->calculerDateReabonnement($client);

        // Sauvegarde en BDD
        $client->save();

        return redirect()->route('clients.index')
            ->with('success', 'Client modifié avec succès et date de réabonnement mise à jour.');
    }

    // --- CORRECTION : Méthodes pour suspendre/réactiver manuellement ---
    public function suspendre($id)
    {
        $client = Client::findOrFail($id);
        $client->statut = 'suspendu';
        $client->save();

        return redirect()->back()->with('success', 'Client suspendu avec succès.');
    }

    public function reactiver($id)
    {
        $client = Client::findOrFail($id);
        $client->statut = 'actif';
        $client->save();

        return redirect()->back()->with('success', 'Client réactivé avec succès.');
    }

    // --- Notifications et relances ---
    public function envoyerNotifications()
    {
        $clients = Client::whereBetween('date_reabonnement', [Carbon::today(), Carbon::today()->addDays(7)])
                          ->whereNotNull('email')
                          ->get();

        foreach ($clients as $client) {
            Mail::raw(
                "Bonjour {$client->nom_client}, votre date de réabonnement approche. Merci de renouveler via ce lien : https://anyxtech.com/reabonnement",
                function ($message) use ($client) {
                    $message->to($client->email)->subject('Réabonnement proche');
                }
            );
        }

        return redirect()->route('clients.index')->with('success', 'Notifications envoyées avec succès.');
    }

    public function relancerViaWhatsApp(Client $client, InfobipService $infobip)
    {
        $numero = preg_replace('/[^0-9]/', '', $client->contact);
        if (strlen($numero) === 8) $numero = '229' . $numero;
        $numeroWhatsapp = "+$numero";
        $nomClient = $client->nom_client;

        $success = $infobip->sendWhatsAppTemplate($numeroWhatsapp, $nomClient);

        return $success
            ? back()->with('success', "Message WhatsApp envoyé à {$nomClient} via Infobip.")
            : back()->with('error', "Échec de l'envoi WhatsApp via Infobip.");
    }

    public function relancer($id, InfobipService $infobip)
    {
        $client = Client::findOrFail($id);
        $numero = preg_replace('/[^0-9]/', '', $client->contact);
        if (strlen($numero) === 8) $numero = '229' . $numero;

        $date = $client->date_reabonnement 
            ? Carbon::parse($client->date_reabonnement)->format('d/m/Y') 
            : 'bientôt';

        $message = "Bonjour {$client->nom_client}, votre réabonnement arrive à échéance le {$date}. Merci de renouveler votre abonnement pour éviter toute interruption. - AnyxTech";

        $success = $infobip->sendSms($numero, $message);

        return $success
            ? redirect()->back()->with('success', "Message envoyé avec succès à {$client->nom_client}.")
            : redirect()->back()->with('error', "Erreur lors de l'envoi du message.");
    }

    // --- CORRECTION : Calcul de la date de réabonnement ---
    private function calculerDateReabonnement(Client $client)
    {
        // Si le client a payé, on utilise le mois suivant
        if ($client->a_paye) {
            $today = Carbon::today();
            $mois = $today->month + 1;
            $annee = $today->year;
            if ($mois > 12) {
                $mois = 1;
                $annee += 1;
            }
        } else {
            // Sinon, on utilise le mois courant
            $today = Carbon::today();
            $mois = $today->month;
            $annee = $today->year;
        }

        // Calculer le jour en respectant la limite du mois
        $jour = min($client->jour_reabonnement, Carbon::create($annee, $mois, 1)->endOfMonth()->day);

        return Carbon::create($annee, $mois, $jour)->toDateString();
    }

    public function mettreAJourDatesReabonnement()
    {
        foreach (Client::all() as $client) {
            try {
                $client->date_reabonnement = $this->calculerDateReabonnement($client);
                $client->save();
            } catch (\Exception $e) {
                \Log::warning("Impossible de mettre à jour la date pour client ID: {$client->id}. Erreur: " . $e->getMessage());
            }
        }

        return response()->json(['message' => 'Dates de réabonnement mises à jour avec succès.']);
    }

    public function show(Client $client) { 
        return view('clients.show', compact('client')); 
    }

    // --- Export PDF ---
    public function export(Request $request)
    {
        $type = $request->input('type', 'all');

        $clients = $type === 'expired' 
            ? Client::where('statut', 'actif')->where('a_paye', 0)->where('date_reabonnement', '<', now())->get()
            : Client::all();

        $pdf = Pdf::loadView('clients.export_pdf', compact('clients'));
        return $pdf->download('clients_reabonnement_depasses.pdf');
    }

    public function exportPdf()
    {
        $clients = Client::where('statut', 'actif')->get();
        $pdf = Pdf::loadView('clients.pdf', compact('clients'));
        return $pdf->download('clients_actifs.pdf');
    }
}