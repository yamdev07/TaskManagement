@extends('layouts.app')

@section('title', 'Clients Non Payés')

@section('content')
<div class="container mt-5">
    <div class="mb-4 text-center">
        <h1 class="display-5 fw-bold text-danger">Clients Non Payés</h1>
    </div>

    {{-- Composant de recherche et ajout --}}
    <x-search-add 
        :add-url="route('clients.create')" 
        add-text="+ Ajouter un client" 
        search-placeholder="Rechercher par nom ou lieu..." 
    />

    @if ($clients->isEmpty())
        <div class="alert alert-warning text-center">Aucun client non payé trouvé.</div>
    @else
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-dark text-center">
                    <tr>
                        <th>No</th>
                        <th>Nom</th>
                        <th>Téléphone</th>
                        <th>Lieu</th>
                        <th>Date Réabonnement</th>
                        <th>Paiement</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($clients as $client)
                        <tr data-nom="{{ strtolower($client->nom_client) }}" data-lieu="{{ strtolower($client->sites_relais) }}">
                            <td class="text-center">{{ $client->id }}</td>
                            <td>{{ $client->nom_client }}</td>
                            <td>{{ $client->contact }}</td>
                            <td>{{ $client->sites_relais ?? '-' }}</td>
                            <td>
                                {{ $client->date_reabonnement 
                                    ? \Carbon\Carbon::parse($client->date_reabonnement)->format('d/m/Y') 
                                    : '-' 
                                }}
                            </td>
                            <td class="text-center">
                                <span class="badge bg-danger">
                                    <i class="fas fa-times-circle"></i> Non
                                </span>
                            </td>
                            <td class="text-center">
                                @if ($client->actif)
                                    <span class="badge bg-success">Actif</span>
                                @else
                                    <span class="badge bg-secondary">Coupé</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('clients.edit', $client->id) }}" class="btn btn-sm btn-outline-primary">
                                    Modifier
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

{{-- Modal de succès --}}
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
        @if(session('success'))
            const modal = new bootstrap.Modal(document.getElementById('successModal'));
            modal.show();
            setTimeout(() => modal.hide(), 3000);
        @endif

        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            const rows = document.querySelectorAll('tbody tr');
            searchInput.addEventListener('input', function () {
                const value = this.value.toLowerCase();
                rows.forEach(row => {
                    const nom = row.dataset.nom;
                    const lieu = row.dataset.lieu;
                    row.style.display = nom.includes(value) || lieu.includes(value) ? '' : 'none';
                });
            });
        }
    });
</script>
@endsection
