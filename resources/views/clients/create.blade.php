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
                <input type="text" name="nom_client" class="form-control" value="{{ old('nom_client') }}" required>
            </div>

            <div class="mb-3">
                <label for="contact" class="form-label">Contact</label>
                <input type="text" name="contact" class="form-control" value="{{ old('contact') }}" required>
            </div>

            <div class="mb-3">
                <label for="sites_relais" class="form-label">Site relais</label>
                <input type="text" name="sites_relais" class="form-control" value="{{ old('sites_relais') }}">
            </div>

            <div class="mb-3">
                <label for="statut" class="form-label">Statut</label>
                <select name="statut" class="form-select">
                    <option value="">-- Sélectionner --</option>
                    <option value="actif" {{ old('statut') === 'actif' ? 'selected' : '' }}>Actif</option>
                    <option value="inactif" {{ old('statut') === 'inactif' ? 'selected' : '' }}>Inactif</option>
                    <option value="suspendu" {{ old('statut') === 'suspendu' ? 'selected' : '' }}>Suspendu</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="categorie" class="form-label">Catégorie</label>
                <input type="text" name="categorie" class="form-control" value="{{ old('categorie') }}">
            </div>

            <div class="mb-3">
                <label for="jour_reabonnement" class="form-label">Jour de réabonnement (1-31)</label>
                <input type="number" name="jour_reabonnement" class="form-control" min="1" max="31" value="{{ old('jour_reabonnement') }}" required>
                <small class="form-text text-muted">Exemple : 5 => tous les 5 du mois</small>
            </div>

            <div class="mb-3">
                <label for="montant" class="form-label">Montant</label>
                <input type="number" name="montant" class="form-control" min="0" value="{{ old('montant') }}" required>
            </div>

            <div class="mb-3 form-check">
                <input 
                    type="checkbox" 
                    name="a_paye" 
                    class="form-check-input" 
                    id="a_paye" 
                    value="1"
                    {{ old('a_paye') ? 'checked' : '' }}
                >
                <label class="form-check-label" for="a_paye">Le client a payé ?</label>
            </div>

            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-success">Enregistrer</button>
                <a href="{{ route('clients.index') }}" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
@endsection
