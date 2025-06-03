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

        return view('clients.index', compact('clients'));
    }

    public function aReabonnement()
    {
        $aujourdhui = Carbon::today();
        $dans7jours = Carbon::today()->addDays(7);

        $clients = Client::whereBetween('date_reabonnement', [$aujourdhui, $dans7jours])->get();

        return view('clients.reabonnement', compact('clients'));
    }

    public function depasses()
    {
        $clients = Client::where('date_reabonnement', '<', Carbon::today())->get();

        return view('clients.depasses', compact('clients'));
    }

    public function clientsPayes()
    {
        $clients = Client::where('a_paye', true)->get();
        return view('clients.payes', compact('clients'));
    }

    public function nonPayes()
    {
        $clients = Client::where('a_paye', false)->get();
        return view('clients.nonpayes', compact('clients'));
    }

    public function envoyerNotifications()
    {
        $aujourdhui = Carbon::today();
        $dans7jours = Carbon::today()->addDays(7);

        $clients = Client::whereBetween('date_reabonnement', [$aujourdhui, $dans7jours])
                         ->whereNotNull('email')
                         ->get();

        foreach ($clients as $client) {
            Mail::raw("Bonjour {$client->nom_client}, votre date de réabonnement approche. Merci de renouveler via ce lien : https://anyxtech.com/reabonnement", function ($message) use ($client) {
                $message->to($client->email)
                        ->subject('Réabonnement proche');
            });
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
            'nom_client'         => 'required|string|max:255',
            'contact'            => 'required|string|max:20',
            'sites_relais'       => 'nullable|string|max:255',
            'statut'             => 'nullable|string|max:50',
            'categorie'          => 'nullable|string|max:100',
            'date_reabonnement'  => 'required|date',
            'montant'            => 'required|numeric|min:0',
            'email'              => 'nullable|email|max:255',
            'a_paye'             => 'nullable|boolean',
        ]);

        // Assurez-vous que la case à cocher est bien gérée
        $validatedData['a_paye'] = $request->has('a_paye');

        Client::create($validatedData);

        return redirect()->route('clients.index')->with('success', 'Client ajouté avec succès !');
    }

    public function edit(Client $client)
    {
        return view('clients.edit', compact('client'));
    }

    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'nom_client'         => 'required|string|max:255',
            'contact'            => 'required|string|max:20',
            'sites_relais'       => 'nullable|string|max:255',
            'statut'             => 'nullable|string|max:50',
            'categorie'          => 'nullable|string|max:100',
            'date_reabonnement'  => 'required|date',
            'montant'            => 'required|numeric|min:0',
            'email'              => 'nullable|email|max:255',
            'a_paye'             => 'nullable|boolean',
        ]);

        $validated['a_paye'] = $request->has('a_paye');

        $client->update($validated);

        return redirect()->route('clients.index')->with('success', 'Client mis à jour avec succès !');
    }
}
