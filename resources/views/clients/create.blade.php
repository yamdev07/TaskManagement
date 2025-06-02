@extends('layouts.app')

@section('title', 'Ajouter un client')

@section('content')
    <div class="card">
        <div class="card-header">Ajouter un client</div>
        <div class="card-body">
            <form action="{{ route('clients.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="nom" class="form-label">Nom</label>
                    <input type="text" name="nom" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" class="form-control">
                </div>

                <div class="mb-3">
                    <label for="telephone" class="form-label">Téléphone</label>
                    <input type="text" name="telephone" class="form-control" required>
                </div>
                
                <div class="mb-3">
                    <label for="lieu" class="form-label">Lieu</label>
                    <input type="text" name="lieu" class="form-control" value="{{ old('lieu', $client->lieu ?? '') }}">
                </div>

                <div class="mb-3">
                    <label for="date_reabonnement" class="form-label">Date de réabonnement</label>
                    <input type="date" name="date_reabonnement" class="form-control" required>
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" name="a_paye" class="form-check-input" id="a_paye">
                    <label class="form-check-label" for="a_paye">A payé</label>
                </div>

                <button type="submit" class="btn btn-success">Enregistrer</button>
                <a href="{{ route('clients.index') }}" class="btn btn-secondary">Annuler</a>
            </form>
        </div>
    </div>
@endsection
