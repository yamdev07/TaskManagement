@extends('layouts.app')

@section('title', 'Clients Suspendus')

@section('content')
<div class="container mt-5">
    <div class="mb-4 text-center">
        <h1 class="display-5 fw-bold">Liste des Clients Suspendus</h1>
        <a href="{{ route('clients.index') }}" class="btn btn-secondary mt-3">← Retour à la liste générale</a>
    </div>

    @if ($clients->isEmpty())
        <div class="alert alert-info">Aucun client suspendu trouvé.</div>
    @else
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Nom du client</th>
                    <th>Contact</th>
                    <th>Site relais</th>
                    <th>Catégorie</th>
                    <th>Date de réabonnement</th>
                    <th>Montant</th>
                    <th>Paiement</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($clients as $client)
                    <tr>
                        <td>{{ $client->id }}</td>
                        <td>{{ $client->nom_client }}</td>
                        <td>{{ $client->contact }}</td>
                        <td>{{ $client->sites_relais ?? 'Non renseigné' }}</td>
                        <td>{{ $client->categorie ?? '-' }}</td>
                        <td>
                            {{ $client->date_reabonnement
                                ? \Carbon\Carbon::parse($client->date_reabonnement)->format('d/m/Y')
                                : '-' 
                            }}
                        </td>
                        <td>{{ number_format($client->montant, 0, ',', ' ') }} F</td>
                        <td>
                            @if (strtolower($client->a_paye) === 'payé')
                                <span class="text-success">
                                    <i class="fas fa-check-circle"></i> Payé
                                </span>
                            @else
                                <span class="text-danger">
                                    <i class="fas fa-times-circle"></i> Non payé
                                </span>
                            @endif
                        </td>
                        <td>
                            @php
                                $numero = preg_replace('/[^0-9]/', '', $client->contact); 
                                $date = $client->date_reabonnement 
                                    ? \Carbon\Carbon::parse($client->date_reabonnement)->format('d/m/Y') 
                                    : 'bientôt';
                                $message = urlencode("Bonjour {$client->nom_client}, votre réabonnement  est arrivé à échéance le {$date}. Merci de penser à renouveler pour éviter toute interruption de service. - AnyxTech");
                            @endphp
                            <a 
                                href="https://wa.me/229{{ $numero }}?text={{ $message }}" 
                                target="_blank" 
                                class="btn btn-success btn-sm"
                            >
                                <i class="fab fa-whatsapp"></i> Relancer
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
