<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class ClientController extends Controller
{
    // Liste tous les clients
    public function index(Request $request)
    {
        $query = Client::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                ->orWhere('lieu', 'like', "%{$search}%");
            });
        }

        $clients = $query->orderBy('nom')->get();

        return view('clients.index', compact('clients'));
    }


    // Liste clients à réabonnement proche (dans 7 jours)
    public function aReabonnement()
    {
        $aujourdhui = Carbon::today();
        $dans7jours = Carbon::today()->addDays(7);

        $clients = Client::whereBetween('date_reabonnement', [$aujourdhui, $dans7jours])->get();

        return view('clients.reabonnement', compact('clients'));
    }

    // Liste clients réabonnement dépassé
    public function depasses()
    {
        $clients = Client::where('date_reabonnement', '<', Carbon::today())->get();

        return view('clients.depasses', compact('clients'));
    }

    // Liste clients qui ont payé
    public function payes()
    {
        $clients = Client::where('a_paye', true)->get();

        return view('clients.payes', compact('clients'));
    }

    // Liste clients qui n'ont pas payé
    public function nonPayes()
    {
        $clients = Client::where('a_paye', false)->get();

        return view('clients.nonpayes', compact('clients'));
    }

    // Envoyer un email aux clients dont le réabonnement approche
    public function envoyerNotifications()
    {
        $aujourdhui = Carbon::today();
        $dans7jours = Carbon::today()->addDays(7);

        $clients = Client::whereBetween('date_reabonnement', [$aujourdhui, $dans7jours])->get();

        foreach ($clients as $client) {
            Mail::raw("Bonjour {$client->nom}, votre date de réabonnement approche. Merci de renouveler via ce lien : https://anyxtech.com/reabonnement", function ($message) use ($client) {
                $message->to($client->email)
                        ->subject('Réabonnement proche');
            });
        }

        // 🔁 Redirige vers la page clients.index avec un message de confirmation
        return redirect()->route('clients.index')->with('success', 'Notifications envoyées avec succès.');
    }

        // Formulaire d'ajout
    public function create()
    {
        return view('clients.create');
    }

    // Enregistrement dans la base
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nom' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'telephone' => 'required|string|max:20',
            'lieu' => 'nullable|string|max:255',
            'date_reabonnement' => 'required|date',
            // 'a_paye' => 'required|boolean', // On retire cette ligne
        ]);

        // Si la checkbox n'est pas cochée, $request->has('a_paye') vaut false
        $validatedData['a_paye'] = $request->has('a_paye');

        Client::create($validatedData);

        return redirect()->route('clients.index')->with('success', 'Client ajouté avec succès !');
    }



    // Formulaire de modification
    public function edit(Client $client)
    {
        return view('clients.edit', compact('client'));
    }

    // Mise à jour dans la base
        public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'nom' => 'required',
            'email' => 'nullable|email',
            'telephone' => 'required',
            'lieu' => 'nullable|string|max:255',
            'date_reabonnement' => 'required|date',
            // 'a_paye' => 'required|boolean',
        ]);

        $validated['a_paye'] = $request->has('a_paye');

        $client->update($validated);

        return redirect()->route('clients.index')->with('success', 'Client mis à jour avec succès.');
    }


}
