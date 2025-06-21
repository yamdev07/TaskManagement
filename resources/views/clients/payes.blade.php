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

        <div class="alert alert-warning text-center mx-auto" style="max-width: 350px;">
            Clients Actifs Non Payés : <strong>{{ $total }}</strong>
        </div>
        
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
                    <tr data-nom="{{ strtolower($client->nom_client) }}" data-siterelais="{{ strtolower($client->sites_relais ?? '') }}">
                        <td>{{ $client->id }}</td>
                        <td>{{ $client->nom_client }}</td>
                        <td>{{ $client->contact }}</td>
                        <td>{{ $client->sites_relais ?? '-' }}</td>
                        <td>
                            @if ($client->statut)
                                <span class="badge bg-success">{{ strtoupper($client->statut) }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if($client->a_paye)
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

                        <!-- Nouvelle colonne Actions -->
                        <td>
                            @if($client->a_paye)
                                <form method="POST" action="{{ route('clients.deconnecter', $client->id) }}" onsubmit="return confirm('Confirmer la déconnexion (non paiement) de ce client ?');">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-danger">Déconnecter</button>
                                </form>
                            @else
                                <span class="text-muted">-</span>
                            @endif
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
    const rows = document.querySelectorAll('tbody tr');
    const visibleCount = document.getElementById('visibleCount');
    const visibleClientsList = document.getElementById('visibleClientsList');

    function updateVisibleClients() {
        let count = 0;
        visibleClientsList.innerHTML = '';

        rows.forEach(row => {
            if (row.style.display !== 'none') {
                count++;
                const clientName = row.children[1].textContent;
                const li = document.createElement('li');
                li.textContent = clientName;
                visibleClientsList.appendChild(li);
            }
        });

        visibleCount.textContent = count;
    }

    if (searchInput) {
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

            updateVisibleClients();
        });
    }

    updateVisibleClients();

    @if(session('success'))
        var successModal = new bootstrap.Modal(document.getElementById('successModal'));
        successModal.show();
        setTimeout(() => successModal.hide(), 3000);
    @endif
});
</script>
@endsection
