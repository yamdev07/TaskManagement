@extends('layouts.app')

@section('title', 'Liste des clients')

@section('content')
<div class="container mt-5">
    <div class="mb-4 text-center">
        <h1 class="display-5 fw-bold">Liste des Clients</h1>
    </div>

    {{-- Statistiques --}}
    @if ($clients->count())
        @php
            $total = $clients->count();
            $payes = $clients->where('a_paye', 1)->count();
            $nonPayes = $total - $payes;
        @endphp

        <div class="mb-4 d-flex justify-content-center gap-3 flex-wrap">
            <div class="card text-white bg-success" style="min-width: 150px;">
                <div class="card-body text-center">
                    <h5 class="card-title">Clients Payés</h5>
                    <p class="card-text fs-4">{{ $payes }} / {{ $total }}</p>
                </div>
            </div>

            <div class="card text-white bg-danger" style="min-width: 150px;">
                <div class="card-body text-center">
                    <h5 class="card-title">Clients non payés</h5>
                    <p class="card-text fs-4">{{ $nonPayes }} / {{ $total }}</p>
                </div>
            </div>

            <div class="card text-white bg-primary" style="min-width: 150px;">
                <div class="card-body text-center">
                    <h5 class="card-title">Clients actifs</h5>
                    <p class="card-text fs-4">{{ $actifs }}</p>
                </div>
            </div>

            <div class="card text-white bg-secondary" style="min-width: 150px;">
                <div class="card-body text-center">
                    <h5 class="card-title">Clients suspendus</h5>
                    <p class="card-text fs-4">{{ $suspendus }}</p>
                </div>
            </div>
        </div>
    @endif

    {{-- Barre de recherche + bouton --}}
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <input type="text" class="form-control w-50" id="searchInput" placeholder="Rechercher par nom ou site relais...">
        <a href="{{ route('clients.create') }}" class="btn btn-success">+ Ajouter un client</a>
    </div>

    {{-- Tableau des clients --}}
    <div class="table-responsive">
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
            <tbody id="clientTbody">
                @include('clients.partials.client_list', ['clients' => $clients])
            </tbody>
        </table>
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
</div>

{{-- Scripts --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('searchInput');

        function attachSearch() {
            if (searchInput) {
                searchInput.addEventListener('input', function () {
                    const value = this.value.toLowerCase();
                    document.querySelectorAll('#clientTbody tr').forEach(row => {
                        const nom = row.dataset.nom?.toLowerCase() || '';
                        const site = row.dataset.siterelais?.toLowerCase() || '';
                        row.style.display = nom.includes(value) || site.includes(value) ? '' : 'none';
                    });
                });
            }
        }

        attachSearch();

        @if(session('success'))
            const successModal = new bootstrap.Modal(document.getElementById('successModal'));
            successModal.show();
            setTimeout(() => successModal.hide(), 3000);
        @endif

        // Rafraîchissement automatique toutes les 10 secondes
        setInterval(() => {
            fetch("{{ route('clients.ajaxList') }}")
                .then(res => res.text())
                .then(html => {
                    document.getElementById('clientTbody').innerHTML = html;
                    attachSearch(); // Re-attacher la recherche après mise à jour
                });
        }, 10000);
    });
</script>
@endsection
