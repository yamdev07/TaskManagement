{{-- clients/partials/client_list.blade.php --}}

@forelse ($clients as $client)
    {{-- Les data-attributs ne sont plus nécessaires pour la recherche, car elle est gérée côté serveur --}}
    {{-- Je les laisse au cas où vous auriez d'autres scripts JS qui les utilisent --}}
    <tr data-nom="{{ $client->nom_client ?? '' }}" data-siterelais="{{ $client->site_relais ?? '' }}" data-contact="{{ $client->contact ?? '' }}">
        <td class="ps-4">{{ $loop->iteration + ($clients->perPage() * ($clients->currentPage() - 1)) }}</td>
        <td>
            <div class="d-flex align-items-center">
                <div class="me-3">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($client->nom_client ?? 'N/A') }}&background=random&color=fff&size=40&rounded=true" alt="Avatar" class="rounded-circle shadow-sm">
                </div>
                <div>
                    <h6 class="mb-0 fw-semibold text-dark">{{ $client->nom_client }}</h6>
                    <small class="text-muted">{{ $client->email ?? 'N/A' }}</small>
                </div>
            </div>
        </td>
        <td>{{ $client->contact }}</td>
        <td>{{ $client->site_relais ?? 'N/A' }}</td>
        <td>
            @if ($client->statut == 'actif')
                <span class="badge bg-success-subtle text-success fw-semibold py-2 px-3 rounded-pill">Actif</span>
            @elseif ($client->statut == 'suspendu')
                <span class="badge bg-warning-subtle text-warning fw-2 px-3 rounded-pill">Suspendu</span>
            @else
                <span class="badge bg-secondary-subtle text-secondary fw-semibold py-2 px-3 rounded-pill">{{ $client->statut ?? 'Inconnu' }}</span>
            @endif
        </td>
        <td>
            @if ($client->a_paye)
                <span class="badge bg-success-subtle text-success fw-semibold py-2 px-3 rounded-pill">Payé</span>
            @else
                <span class="badge bg-danger-subtle text-danger fw-semibold py-2 px-3 rounded-pill">Non Payé</span>
            @endif
        </td>
        <td>{{ $client->categorie ?? 'N/A' }}</td>
        <td>
            @if ($client->date_reabonnement)
                <span class="text-dark fw-semibold">{{ \Carbon\Carbon::parse($client->date_reabonnement)->format('d M Y') }}</span>
                @if (\Carbon\Carbon::parse($client->date_reabonnement)->isPast() && !$client->a_paye)
                    <small class="d-block text-danger">Expiré</small>
                @elseif (\Carbon\Carbon::parse($client->date_reabonnement)->diffInDays(now()) < 7 && !$client->a_paye)
                    <small class="d-block text-warning">Bientôt expiré</small>
                @endif
            @else
                <span class="text-muted">Non défini</span>
            @endif
        </td>
        <td class="fw-semibold text-anyxtech">{{ number_format($client->montant ?? 0, 0, ',', ' ') }} FCFA</td>

        {{-- Nouvelle colonne Prochain Mois Dû --}}
        <td>
            @if ($client->prochain_mois_du !== 'Non payé')
                <span class="badge {{ $client->a_paye ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} fw-semibold py-2 px-3 rounded-pill">
                    {{ $client->prochain_mois_du }}
                </span>
            @else
                <span class="badge bg-danger-subtle text-danger fw-semibold py-2 px-3 rounded-pill">
                    Non payé
                </span>
            @endif
        </td>

        <td>
            <div class="d-flex gap-2">
                <a href="{{ route('clients.show', $client->id) }}" class="btn btn-outline-primary btn-sm rounded-pill" data-bs-toggle="tooltip" title="Voir les détails">
                    <i class="fas fa-eye"></i>
                </a>
                <a href="{{ route('clients.edit', $client->id) }}" class="btn btn-outline-info btn-sm rounded-pill" data-bs-toggle="tooltip" title="Modifier">
                    <i class="fas fa-edit"></i>
                </a>
                <form action="{{ route('clients.destroy', $client->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce client ?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill" data-bs-toggle="tooltip" title="Supprimer">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </form>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="10" class="text-center py-5 text-muted">
            <i class="fas fa-folder-open fs-1 mb-3 opacity-50"></i>
            <p class="mb-0">Aucun client trouvé pour le moment ou avec les critères de recherche actuels.</p>
            <a href="{{ route('clients.create') }}" class="btn btn-anyxtech mt-3">Ajouter un client</a>
        </td>
    </tr>
@endforelse