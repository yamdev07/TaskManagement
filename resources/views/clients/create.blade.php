@extends('layouts.app')

@section('title', 'Ajouter un client')

@section('content')
    <div class="container mt-4">
        <div class="mb-4 text-center">
            <h1>Ajouter un client</h1>
        </div>

        <form action="{{ route('clients.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="nom_client" class="form-label">Nom du client</label>
                <input type="text" name="nom_client" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="contact" class="form-label">Contact</label>
                <input type="text" name="contact" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="sites_relais" class="form-label">Site relais</label>
                <input type="text" name="sites_relais" class="form-control">
            </div>

            <div class="mb-3">
                <label for="statut" class="form-label">Statut</label>
                <select name="statut" class="form-select">
                    <option value="">-- Sélectionner --</option>
                    <option value="payé">Payé</option>
                    <option value="non payé">Non payé</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="categorie" class="form-label">Catégorie</label>
                <input type="text" name="categorie" class="form-control">
            </div>

            <div class="mb-3">
                <label for="date_reabonnement" class="form-label">Date de réabonnement</label>
                <input type="date" name="date_reabonnement" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="montant" class="form-label">Montant</label>
                <input type="number" name="montant" class="form-control" min="0" required>
            </div>

            <div class="mb-3 form-check">
                <input 
                    type="checkbox" 
                    name="actif" 
                    class="form-check-input" 
                    id="actif" 
                    {{ old('actif', true) ? 'checked' : '' }}
                >
                <label class="form-check-label" for="actif">Client actif ?</label>
            </div>

            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-success">Enregistrer</button>
                <a href="{{ route('clients.index') }}" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
@endsection
