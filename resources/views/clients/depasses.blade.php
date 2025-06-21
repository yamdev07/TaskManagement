@extends('layouts.app')

@section('title', 'Réabonnement dépassé')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4 text-center fw-bold display-4">Réabonnements Dépassés (Clients Actifs)</h1>

    {{-- Cartes de statistiques --}}
    @if ($clients->count())
        @php
            $total = $clients->count();
            $payes = $clients->where('statut', 'payé')->count();
            $nonPayes = $total - $payes;
        @endphp

       <div class="d-flex justify-content-between mb-4" style="gap: 1rem;">
            <div class="text-white bg-success d-flex flex-column justify-content-center align-items-center" style="width: 150px; height: 150px; border-radius: 0.25rem;">
                <div class="fw-bold">Payés</div>
                <div class="fs-3">{{ $payes }} / {{ $total }}</div>
            </div>
            <div class="text-white bg-danger d-flex flex-column justify-content-center align-items-center" style="width: 150px; height: 150px; border-radius: 0.25rem;">
                <div class="fw-bold">Non Payés</div>
                <div class="fs-3">{{ $nonPayes }} / {{ $total }}</div>
            </div>
        </div>

    @endif

    {{-- Tableau --}}
    @if ($clients->isEmpty())
        <div class="alert alert-info">Aucun client actif avec réabonnement dépassé trouvé.</div>
    @else
        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Nom du client</th>
                    <th>Contact</th>
                    <th>Site relais</th>
                    <th>Paiement</th>
                    <th>Catégorie</th>
                    <th>Date de réabonnement</th>
                    <th>Montant</th>
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
                    <td>
                        @if (strtolower($client->statut) === 'payé')
                            <span class="badge bg-success">Payé</span>
                        @else
                            <span class="badge bg-danger">Non payé</span>
                        @endif
                    </td>
                    <td>{{ $client->categorie ?? '-' }}</td>
                    <td>{{ $client->date_reabonnement ? \Carbon\Carbon::parse($client->date_reabonnement)->format('d/m/Y') : '-' }}</td>
                    <td>{{ number_format($client->montant, 0, ',', ' ') }} F</td>
                    <td><a href="#" class="btn btn-sm btn-primary">Modifier</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    {{-- Modal succès --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
            <strong>Succès !</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
        </div>
    @endif
</div>
@endsection
