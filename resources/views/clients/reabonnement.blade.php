@extends('layouts.app')

@section('title', 'Réabonnement à venir')

@section('content')
<div class="container mt-5">
    <div class="mb-4 text-center">
        <h1 class="display-5 fw-bold">Réabonnements à venir</h1>
    </div>

    {{-- Cartes de statistiques --}}
    @if ($clients->count())
        @php
            $total = $clients->count();
            $payes = $clients->where('statut', 'payé')->count();
            $nonPayes = $total - $payes;
        @endphp

        <div class="mb-4 d-flex justify-content-center gap-3 flex-wrap">
            <div class="card text-white bg-success" style="min-width: 150px;">
                <div class="card-body text-center">
                    <h5 class="card-title">Payés</h5>
                    <p class="card-text fs-4">{{ $payes }} / {{ $total }}</p>
                </div>
            </div>

            <div class="card text-white bg-danger" style="min-width: 150px;">
                <div class="card-body text-center">
                    <h5 class="card-title">Non Payés</h5>
                    <p class="card-text fs-4">{{ $nonPayes }} / {{ $total }}</p>
                </div>
            </div>
        </div>
    @endif

    {{-- Barre de recherche --}}
    <x-search-add 
        :add-url="route('clients.create')" 
        add-text="+ Ajouter un client" 
        search-placeholder="Rechercher par nom ou site relais..." 
    />

    {{-- Tableau --}}
    @if ($clients->isEmpty())
        <div class="alert alert-info">Aucun client actif avec réabonnement à venir trouvé.</div>
    @else
        <table class="table table-bordered table-striped">
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
                    <tr 
                        data-nom="{{ strtolower($client->nom_client) }}" 
                        data-siterelais="{{ strtolower($client->sites_relais) }}"
                    >
                        <td>{{ $client->id }}</td>
                        <td>{{ $client->nom_client }}</td>
                        <td>{{ $client->contact }}</td>
                        <td>{{ $client->sites_relais ?? 'Non renseigné' }}</td>

                        {{-- Paiement --}}
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
                            @php
                                $numero = preg_replace('/[^0-9]/', '', $client->contact); 
                                $date = $client->date_reabonnement 
                                    ? \Carbon\Carbon::parse($client->date_reabonnement)->format('d/m/Y') 
                                    : 'bientôt';
                                $message = urlencode("Bonjour {$client->nom_client}, votre réabonnement arrive à échéance le {$date}. Merci de penser à renouveler pour éviter toute interruption de service. - AnyxTech");
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
