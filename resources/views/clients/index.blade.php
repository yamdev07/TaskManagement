@extends('layouts.app')

@section('title', 'Liste des clients')

@section('content')
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1>Liste des Clients</h1>
        </div>
        <div class="row mb-3 align-items-center">
            <div class="col-md-6">
                <input type="text" id="searchInput" class="form-control" placeholder="Rechercher par nom ou lieu...">
            </div>
            <div class="col-md-6 text-end">
                <a href="{{ route('clients.create') }}" class="btn btn-success">+ Ajouter un client</a>
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
                        <tr data-nom="{{ $client->nom }}" data-lieu="{{ $client->lieu }}">
                            <td>{{ $client->nom }}</td>
                            <td>{{ $client->email ?? 'Non renseigné' }}</td>
                            <td>{{ $client->telephone }}</td>
                            <td>{{ $client->lieu }}</td> <!-- Il manquait peut-être ça -->
                            <td>{{ \Carbon\Carbon::parse($client->date_reabonnement)->format('d/m/Y') }}</td>
                            <td>
                                @if ($client->a_paye)
                                    <span class="badge bg-success">Oui</span>
                                @else
                                    <span class="badge bg-danger">Non</span>
                                @endif
                            </td>
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
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const searchInput = document.getElementById('searchInput');
                const rows = document.querySelectorAll('tbody tr');

                searchInput.addEventListener('input', function () {
                    const value = this.value.toLowerCase();

                    rows.forEach(row => {
                        const nom = row.dataset.nom.toLowerCase();
                        const lieu = row.dataset.lieu.toLowerCase();

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
