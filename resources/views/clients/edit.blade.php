@extends('layouts.app')

@section('title', 'Modifier le client')

@section('content')
    <div class="card">
        <div class="card-header">Modifier le client</div>
        <div class="card-body">
            <form action="{{ route('clients.update', $client->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="nom_client" class="form-label">Nom du client</label>
                    <input type="text" name="nom_client" class="form-control" value="{{ $client->nom_client }}" required>
                </div>

                <div class="mb-3">
                    <label for="contact" class="form-label">Contact</label>
                    <input type="text" name="contact" class="form-control" value="{{ $client->contact }}" required>
                </div>

                <div class="mb-3">
                    <label for="sites_relais" class="form-label">Site relais</label>
                    <input type="text" name="sites_relais" class="form-control" value="{{ $client->sites_relais }}">
                </div>

                <div class="mb-3">
                    <label for="statut" class="form-label">Statut</label>
                    <select name="statut" class="form-select" required>
                        <option value="actif" {{ $client->statut === 'actif' ? 'selected' : '' }}>Actif</option>
                        <option value="inactif" {{ $client->statut === 'inactif' ? 'selected' : '' }}>Inactif</option>
                        <option value="suspendu" {{ $client->statut === 'suspendu' ? 'selected' : '' }}>Suspendu</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="categorie" class="form-label">Catégorie</label>
                    <input type="text" name="categorie" class="form-control" value="{{ $client->categorie }}">
                </div>

                <div class="mb-3">
                    <label for="date_reabonnement" class="form-label">Date de réabonnement</label>
                    <input type="date" name="date_reabonnement" class="form-control" value="{{ $client->date_reabonnement }}" required>
                </div>

                <div class="mb-3">
                    <label for="montant" class="form-label">Montant</label>
                    <input type="number" name="montant" class="form-control" value="{{ $client->montant }}" required>
                </div>

                {{-- Nouveau champ paiement --}}
                <div class="mb-3">
                    <label for="a_paye" class="form-label">Statut de paiement</label>
                    <select name="a_paye" class="form-select" required>
                        <option value="payé" {{ strtolower($client->a_paye ?? '') === 'payé' ? 'selected' : '' }}>Payé</option>
                        <option value="non payé" {{ strtolower($client->a_paye ?? '') === 'non payé' ? 'selected' : '' }}>Non payé</option>
                    </select>
                </div>


                <button type="submit" class="btn btn-primary">Mettre à jour</button>
                <a href="{{ route('clients.index') }}" class="btn btn-secondary">Annuler</a>
            </form>
        </div>
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

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var successModal = new bootstrap.Modal(document.getElementById('successModal'));
                successModal.show();

                setTimeout(() => {
                    successModal.hide();
                    window.location.href = "{{ route('clients.index') }}";
                }, 3000);
            });
        </script>
    @endif

@endsection
