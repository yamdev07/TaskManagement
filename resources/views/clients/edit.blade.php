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
                    <input type="text" name="nom_client" id="nom_client" class="form-control" value="{{ old('nom_client', $client->nom_client) }}" required>
                </div>

                <div class="mb-3">
                    <label for="contact" class="form-label">Contact</label>
                    <input type="text" name="contact" id="contact" class="form-control" value="{{ old('contact', $client->contact) }}" required>
                </div>

                <div class="mb-3">
                    <label for="sites_relais" class="form-label">Site relais</label>
                    <input type="text" name="sites_relais" id="sites_relais" class="form-control" value="{{ old('sites_relais', $client->sites_relais) }}">
                </div>

                <div class="mb-3">
                    <label for="statut" class="form-label">Statut</label>
                    <select name="statut" id="statut" class="form-select" required>
                        <option value="actif" {{ old('statut', $client->statut) === 'actif' ? 'selected' : '' }}>Actif</option>
                        <option value="inactif" {{ old('statut', $client->statut) === 'inactif' ? 'selected' : '' }}>Inactif</option>
                        <option value="suspendu" {{ old('statut', $client->statut) === 'suspendu' ? 'selected' : '' }}>Suspendu</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="categorie" class="form-label">Catégorie</label>
                    <input type="text" name="categorie" id="categorie" class="form-control" value="{{ old('categorie', $client->categorie) }}">
                </div>

                <div class="mb-3">
                    <label for="date_reabonnement" class="form-label">Date de réabonnement</label>
                    <input type="date" name="date_reabonnement" id="date_reabonnement" class="form-control" value="{{ old('date_reabonnement', $client->date_reabonnement) }}" required>
                </div>

                <div class="mb-3">
                    <label for="montant" class="form-label">Montant</label>
                    <input type="number" name="montant" id="montant" class="form-control" value="{{ old('montant', $client->montant) }}" required>
                </div>

                <div class="mb-3">
                    <label for="a_paye" class="form-label">Statut de paiement</label>
                    <select name="a_paye" id="a_paye" class="form-select" required>
                        <option value="1" {{ old('a_paye', $client->a_paye) == 1 ? 'selected' : '' }}>Payé</option>
                        <option value="0" {{ old('a_paye', $client->a_paye) == 0 ? 'selected' : '' }}>Non payé</option>
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
