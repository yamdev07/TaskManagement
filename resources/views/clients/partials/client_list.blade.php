@foreach ($clients as $index => $client)
<tr data-nom="{{ strtolower($client->nom_client) }}" data-siterelais="{{ strtolower($client->sites_relais ?? '') }}">
    <td>{{ $index + 1 }}</td>
    <td>{{ $client->nom_client }}</td>
    <td>{{ $client->contact }}</td>
    <td>{{ $client->sites_relais }}</td>
    <td>
        @if ($client->statut == 'actif')
            <span class="badge bg-primary">Actif</span>
        @elseif ($client->statut == 'suspendu')
            <span class="badge bg-secondary">Suspendu</span>
        @else
            <span class="badge bg-warning text-dark">{{ ucfirst($client->statut) }}</span>
        @endif
    </td>
    <td>
        @if ($client->a_paye)
            <span class="badge bg-success">Payé</span>
        @else
            <span class="badge bg-danger">Non payé</span>
        @endif
    </td>
    <td>{{ $client->categorie }}</td>
    <td>{{ \Carbon\Carbon::parse($client->date_reabonnement)->format('d/m/Y') }}</td>
    <td>{{ number_format($client->montant, 0, ',', ' ') }} FCFA</td>
    <td>
        {{-- Actions comme modifier/supprimer --}}
        <a href="{{ route('clients.edit', $client->id) }}" class="btn btn-sm btn-primary">Modifier</a>
        <form action="{{ route('clients.destroy', $client->id) }}" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit" onclick="return confirm('Confirmer la suppression ?')" class="btn btn-sm btn-danger">Supprimer</button>
        </form>
    </td>
</tr>
@endforeach
