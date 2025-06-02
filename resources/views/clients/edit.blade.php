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
                    <label for="nom" class="form-label">Nom</label>
                    <input type="text" name="nom" class="form-control" value="{{ $client->nom }}" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ $client->email }}">
                </div>

                <div class="mb-3">
                    <label for="telephone" class="form-label">Téléphone</label>
                    <input type="text" name="telephone" class="form-control" value="{{ $client->telephone }}" required>
                </div>
                
                <div class="mb-3">
                    <label for="lieu" class="form-label">Lieu</label>
                    <input type="text" name="lieu" class="form-control" value="{{ old('lieu', $client->lieu ?? '') }}">
                </div>

                <div class="mb-3">
                    <label for="date_reabonnement" class="form-label">Date de réabonnement</label>
                    <input type="date" name="date_reabonnement" class="form-control" value="{{ $client->date_reabonnement }}" required>
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" name="a_paye" class="form-check-input" id="a_paye" {{ $client->a_paye ? 'checked' : '' }}>
                    <label class="form-check-label" for="a_paye">A payé</label>
                </div>

                <button type="submit" class="btn btn-primary">Mettre à jour</button>
                <a href="{{ route('clients.index') }}" class="btn btn-secondary">Annuler</a>
            </form>
        </div>
    </div>
@endsection
