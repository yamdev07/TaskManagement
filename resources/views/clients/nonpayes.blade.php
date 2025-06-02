@extends('layouts.app')

@section('title', 'Clients non payés')

@section('content')
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Clients non payés</h1>
        <a href="{{ route('clients.create') }}" class="btn btn-success">+ Ajouter un client</a>
    </div>
    
    <div class="row mb-3">
        <div class="col-md-6">
            <input type="text" id="searchInput" class="form-control" placeholder="Rechercher par nom ou lieu...">
        </div>
    </div>

    @if ($clients->isEmpty())
        <div class="alert alert-info">Aucun client trouvé.</div>
    @else
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Téléphone</th>
                    <th>Lieu</th>
                    <th>Date Réabonnement</th>
                    <th>A payé</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($clients as $client)
                    <tr data-nom="{{ strtolower($client->nom) }}" data-lieu="{{ strtolower($client->lieu) }}">
                        <td>{{ $client->nom }}</td>
                        <td>{{ $client->email ?? 'Non renseigné' }}</td>
                        <td>{{ $client->telephone }}</td>
                        <td>{{ $client->lieu }}</td>
                        <td>{{ \Carbon\Carbon::parse($client->date_reabonnement)->format('d/m/Y') }}</td>
                        <td><span class="badge bg-danger">Non</span></td>
                        <td>
                            <a href="{{ route('clients.edit', $client->id) }}" class="btn btn-primary btn-sm">Modifier</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

{{-- Modal notification succès --}}
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

    <script>
        window.addEventListener('DOMContentLoaded', function () {
            var successModal = new bootstrap.Modal(document.getElementById('successModal'));
            successModal.show();

            setTimeout(function () {
                successModal.hide();
            }, 3000);
        });

        // Filtrage dynamique
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('searchInput');
            const rows = document.querySelectorAll('tbody tr');

            searchInput.addEventListener('input', function () {
                const value = this.value.toLowerCase();

                rows.forEach(row => {
                    const nom = row.dataset.nom;
                    const lieu = row.dataset.lieu;

                    if (nom.includes(value) || lieu.includes(value)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        });
    </script>
@endif
@endsection
