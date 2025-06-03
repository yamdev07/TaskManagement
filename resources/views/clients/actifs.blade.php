@extends('layouts.app')

@section('title', 'Clients Actifs')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Liste des Clients Actifs</h5>
            <a href="{{ route('clients.index') }}" class="btn btn-sm btn-secondary">← Retour à la liste</a>
        </div>
        <div class="card-body">
            @if($clients->isEmpty())
                <p class="text-muted">Aucun client actif trouvé.</p>
            @else
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Contact</th>
                            <th>Site relais</th>
                            <th>Catégorie</th>
                            <th>Date réabonnement</th>
                            <th>Montant</th>
                            <th>Paiement</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($clients as $client)
                            <tr>
                                <td>{{ $client->nom_client }}</td>
                                <td>{{ $client->contact }}</td>
                                <td>{{ $client->sites_relais }}</td>
                                <td>{{ $client->categorie }}</td>
                                <td>{{ $client->date_reabonnement }}</td>
                                <td>{{ $client->montant }}</td>
                                <td>
                                    @if($client->a_paye)
                                        <span class="badge bg-success">Payé</span>
                                    @else
                                        <span class="badge bg-danger">Non payé</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('clients.edit', $client->id) }}" class="btn btn-sm btn-primary">Modifier</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
@endsection
