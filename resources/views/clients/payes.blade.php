@extends('layouts.app')

@section('title', 'Clients payés')

@section('content')
<div class="container mt-5">
    <div class="mb-4 text-center">
        <h1 class="display-5 fw-bold">Clients Payés</h1>
    </div>

    <x-search-add 
        :add-url="route('clients.create')" 
        add-text="+ Ajouter un client" 
        search-placeholder="Rechercher par nom ou site relais..." 
    />

    @if ($clients->isEmpty())
        <div class="alert alert-info">Aucun client payé trouvé.</div>
    @else
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Nom du client</th>
                    <th>Contact</th>
                    <th>Site relais</th>
                    <th>Statut</th>
                    <th>Paiement</th> {{-- Colonne ajoutée --}}
                    <th>Catégorie</th>
                    <th>Date de réabonnement</th>
                    <th>Montant</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($clients as $client)
                    <tr data-nom="{{ strtolower($client->nom_client) }}" data-siterelais="{{ strtolower($client->sites_relais) }}">
                        <td>{{ $client->id }}</td>
                        <td>{{ $client->nom_client }}</td>
                        <td>{{ $client->contact }}</td>
                        <td>{{ $client->sites_relais ?? '-' }}</td>
                        <td>
                            @if ($client->statut)
                                <span class="badge bg-danger">{{ strtoupper($client->statut) }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td> {{-- Paiement --}}
                            <span class="text-success">
                                <i class="fas fa-check-circle"></i> Payé
                            </span>
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
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

        @if(session('success'))
            var successModal = new bootstrap.Modal(document.getElementById('successModal'));
            successModal.show();
            setTimeout(function () {
                successModal.hide();
            }, 3000);
        @endif
    });
</script>
@endsection
