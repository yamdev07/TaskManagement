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
                    <input type="text" name="statut" class="form-control" value="{{ $client->statut }}">
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

                <div class="mb-3 form-check">
                    <input 
                        type="checkbox" 
                        name="actif" 
                        class="form-check-input" 
                        id="actif" 
                        {{ $client->actif ? 'checked' : '' }}>
                    <label class="form-check-label" for="actif">Client actif ?</label>
                </div>

                <button type="submit" class="btn btn-primary">Mettre à jour</button>
                <a href="{{ route('clients.index') }}" class="btn btn-secondary">Annuler</a>
            </form>
        </div>
    </div>
@endsection
