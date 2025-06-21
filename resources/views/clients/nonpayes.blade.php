@extends('layouts.app')

@section('title', 'Clients Non Payés')

@section('content')
<div class="container mt-5">

    <div class="mb-4 text-center">
        <h1 class="display-5 fw-bold text-danger">Liste des Clients Non Payés</h1>
        
    </div>
    
    {{-- Composant recherche + ajouter --}}
    <x-search-add 
    :add-url="route('clients.create')" 
    add-text="+ Ajouter un client" 
    search-placeholder="Rechercher par nom ou site relais..." 
    />
        
    <div class="alert alert-warning w-fit-content mx-auto" style="max-width: 300px;">
        Clients Actifs Non Payés : <strong>{{ $total }}</strong>
    </div>

    @if ($clients->isEmpty())
        <div class="alert alert-warning text-center">Aucun client non payé trouvé.</div>
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

                        {{-- Statut --}}
                        <td class="text-center">
                            @php
                                $statut = strtolower($client->statut);
                                $badgeClass = match($statut) {
                                    'actif' => 'text-success',
                                    'suspendu' => 'text-danger',
                                    default => 'text-secondary',
                                };
                                $icon = match($statut) {
                                    'actif' => 'fa-circle-check',
                                    'suspendu' => 'fa-circle-xmark',
                                    default => 'fa-question-circle',
                                };
                            @endphp
                            <span class="{{ $badgeClass }}">
                                <i class="fas {{ $icon }}"></i> {{ ucfirst($statut) }}
                            </span>
                        </td>

                        {{-- Paiement --}}
                        <td class="text-center">
                            <span class="text-danger">
                                <i class="fas fa-times-circle"></i> Non payé
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

                        {{-- Action : Reconnecter --}}
                        <td>
                            <form action="{{ route('clients.reconnecter', $client->id) }}" method="POST" onsubmit="return confirm('Confirmer la reconnexion du client ?')">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-outline-success">
                                    <i class="fas fa-plug"></i> Reconnecter
                                </button>
                            </form>
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

{{-- JS --}}
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
                    const site = row.dataset.siterelais;
                    row.style.display = nom.includes(value) || site.includes(value) ? '' : 'none';
                });
            });
        }
    });
</script>
@endsection
