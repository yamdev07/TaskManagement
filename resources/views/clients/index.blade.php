@extends('layouts.app')

@section('title', 'Liste des clients')

@section('content')
<div class="container mt-5">
    <div class="mb-4 text-center">
        <h1 class="display-5 fw-bold">Liste des Clients</h1>
    </div>

    {{-- Carte des stats --}}
    @if ($clients->count())
        @php
            $total = $clients->count();
            $payes = $clients->where('statut', 'payé')->count();
            $nonPayes = $total - $payes;
        @endphp

        <div class="mb-4 d-flex justify-content-center gap-3">
            <div class="card text-white bg-success" style="min-width: 150px;">
                <div class="card-body text-center">
                    <h5 class="card-title">Clients Payés</h5>
                    <p class="card-text fs-4">{{ $payes }} / {{ $total }}</p>
                </div>
            </div>

            <div class="card text-white bg-danger" style="min-width: 150px;">
                <div class="card-body text-center">
                    <h5 class="card-title">Non Payés</h5>
                    <p class="card-text fs-4">{{ $nonPayes }} / {{ $total }}</p>
                </div>
            </div>

            <div class="card text-white bg-primary" style="min-width: 150px;">
                <div class="card-body text-center">
                    <h5 class="card-title">Clients Actifs</h5>
                    <p class="card-text fs-4">{{ $actifs }}</p>
                </div>
            </div>

            <div class="card text-white bg-secondary" style="min-width: 150px;">
                <div class="card-body text-center">
                    <h5 class="card-title">Clients Suspendus</h5>
                    <p class="card-text fs-4">{{ $suspendus }}</p>
                </div>
            </div>

        </div>
    @endif

    {{-- Barre de recherche et bouton ajouter --}}
    <x-search-add 
        :add-url="route('clients.create')" 
        add-text="+ Ajouter un client" 
        search-placeholder="Rechercher par nom ou site relais..." 
    />

    {{-- Tableau des clients --}}
    @if ($clients->isEmpty())
        <div class="alert alert-info">Aucun client trouvé.</div>
    @else
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Nom du client</th>
                    <th>Contact</th>
                    <th>Site relais</th>
                    <th>Statut</th>
                    <th>Paiement</th>
                    <th>Catégorie</th>
                    <th>Date de réabonnement</th>
                    <th>Montant</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($clients as $client)
                    <tr 
                        data-nom="{{ strtolower($client->nom_client) }}" 
                        data-siterelais="{{ strtolower($client->sites_relais) }}"
                    >
                        <td>{{ $client->id }}</td>
                        <td>{{ $client->nom_client }}</td>
                        <td>{{ $client->contact }}</td>
                        <td>{{ $client->sites_relais ?? 'Non renseigné' }}</td>

                        {{-- Statut avec icône --}}
                        <td>
                            @php
                                $statut = strtolower($client->statut);
                                $badgeClass = match($statut) {
                                    'actif' => 'text-success',
                                    'inactif' => 'text-warning',
                                    'suspendu' => 'text-danger',
                                    default => 'text-secondary',
                                };
                                $icon = match($statut) {
                                    'actif' => 'fa-circle-check',
                                    'inactif' => 'fa-circle-exclamation',
                                    'suspendu' => 'fa-circle-xmark',
                                    default => 'fa-question-circle',
                                };
                            @endphp
                            <span class="{{ $badgeClass }}">
                                <i class="fas {{ $icon }}"></i> {{ ucfirst($statut) }}
                            </span>
                        </td>

                        {{-- Paiement avec icône --}}
                        <td>
                            @if (strtolower($client->statut) === 'payé')
                                <span class="text-success">
                                    <i class="fas fa-check-circle"></i> Payé
                                </span>
                            @else
                                <span class="text-danger">
                                    <i class="fas fa-times-circle"></i> Non payé
                                </span>
                            @endif
                        </td>

                        <td>{{ $client->categorie ?? '-' }}</td>
                        <td>
                            {{ $client->date_reabonnement 
                                ? \Carbon\Carbon::parse($client->date_reabonnement)->format('d/m/Y') 
                                : '-' 
                            }}
                        </td>
                        <td>{{ number_format($client->montant, 0, ',', ' ') }} F</td>
                        <td>
                            <a href="{{ route('clients.edit', $client->id) }}" class="btn btn-primary btn-sm">Modifier</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

{{-- Modal succès --}}
@if(session('success'))
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-success">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="successModalLabel">Succès</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    {{ session('success') }}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" data-bs-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>
@endif

{{-- Script JS --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Recherche dynamique
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            const rows = document.querySelectorAll('tbody tr');
            searchInput.addEventListener('input', function () {
                const value = this.value.toLowerCase();
                rows.forEach(row => {
                    const nom = row.dataset.nom;
                    const site = row.dataset.siterelais;
                    if (nom.includes(value) || site.includes(value)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        }

        // Modal succès auto hide
        @if(session('success'))
            var successModal = new bootstrap.Modal(document.getElementById('successModal'));
            successModal.show();
            setTimeout(() => successModal.hide(), 3000);
        @endif
    });
</script>
@endsection
