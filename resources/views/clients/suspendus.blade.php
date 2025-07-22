@extends('layouts.app')

@section('title', 'Clients Suspendus')

@section('content')
<div class="container-fluid px-4 py-5">
    {{-- En-tête moderne --}}
    <div class="row mb-5">
        <div class="col-12">
            <div class="bg-gradient-warning rounded-4 p-4 text-white shadow-lg">
                <div class="d-flex align-items-center justify-content-between flex-wrap">
                    <div>
                        <h1 class="display-6 fw-bold mb-2">
                            <i class="fas fa-pause-circle me-3"></i>
                            Clients Suspendus
                        </h1>
                        <p class="lead mb-0 opacity-90">Gestion des abonnements temporairement suspendus</p>
                    </div>
                    <div class="text-end">
                        <div class="badge bg-white text-dark fs-6 px-3 py-2">
                            <i class="fas fa-calendar-alt me-2"></i>
                            {{ date('d/m/Y') }}
                        </div>
                        <a href="{{ route('clients.index') }}" class="btn btn-light btn-sm mt-2">
                            <i class="fas fa-arrow-left me-1"></i> Retour
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Barre d'outils --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center">
                <div class="position-relative" style="width: 300px;">
                    <i class="fas fa-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                    <input type="text" 
                           class="form-control form-control-lg border-0 bg-light ps-5" 
                           id="searchInput" 
                           placeholder="Rechercher un client..."
                           style="border-radius: 12px;">
                </div>
                <div class="text-end">
                    <span class="badge bg-danger fs-6 px-3 py-2">
                        <i class="fas fa-users me-1"></i>
                        {{ $clients->count() }} client(s) suspendu(s)
                    </span>
                </div>
            </div>
        </div>
    </div>

    @if ($clients->isEmpty())
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center p-5">
                <i class="fas fa-check-circle fs-1 text-success mb-4"></i>
                <h4 class="text-muted">Aucun client suspendu</h4>
                <p class="text-muted">Tous vos clients sont actuellement actifs</p>
            </div>
        </div>
    @else
        {{-- Tableau moderne --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 p-4">
                <h5 class="mb-0 text-dark fw-semibold">
                    <i class="fas fa-table me-2 text-warning"></i>
                    Liste des suspensions
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-warning text-white">
                            <tr>
                                <th class="fw-semibold py-3 ps-4">ID</th>
                                <th class="fw-semibold py-3">Client</th>
                                <th class="fw-semibold py-3">Contact</th>
                                <th class="fw-semibold py-3">Site</th>
                                <th class="fw-semibold py-3">Catégorie</th>
                                <th class="fw-semibold py-3">Réabonnement</th>
                                <th class="fw-semibold py-3">Montant</th>
                                <th class="fw-semibold py-3">Paiement</th>
                                <th class="fw-semibold py-3 pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($clients as $client)
                            <tr>
                                <td class="ps-4">{{ $client->id }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm me-3">
                                            <div class="avatar-title bg-light rounded-circle text-warning">
                                                <i class="fas fa-user"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $client->nom_client }}</h6>
                                            <small class="text-muted">
                                                Depuis {{ $client->created_at ? $client->created_at->format('d/m/Y') : 'Date inconnue' }}
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $client->contact }}</td>
                                <td>{{ $client->sites_relais ?? '-' }}</td>
                                <td>{{ $client->categorie ?? '-' }}</td>
                                <td>
                                    @if($client->date_reabonnement)
                                    <span class="badge bg-light text-dark">
                                        {{ \Carbon\Carbon::parse($client->date_reabonnement)->format('d/m/Y') }}
                                    </span>
                                    @else
                                    -
                                    @endif
                                </td>
                                <td>{{ number_format($client->montant, 0, ',', ' ') }} F</td>
                                <td>
                                    @if($client->a_paye)
                                    <span class="badge bg-success-light text-success">
                                        <i class="fas fa-check-circle me-1"></i> Payé
                                    </span>
                                    @else
                                    <span class="badge bg-danger-light text-danger">
                                        <i class="fas fa-times-circle me-1"></i> Non payé
                                    </span>
                                    @endif
                                </td>
                                <td class="pe-4">
                                    <div class="d-flex gap-2">
                                        @php
                                            $numero = preg_replace('/[^0-9]/', '', $client->contact);
                                            if (strlen($numero) === 8) {
                                                $numero = '229' . $numero;
                                            }
                                            $date = $client->date_reabonnement 
                                                ? \Carbon\Carbon::parse($client->date_reabonnement)->format('d/m/Y') 
                                                : 'bientôt';
                                            $message = "Bonjour {$client->nom_client}, votre réabonnement est arrivé à échéance le {$date}. Merci de penser à renouveler pour éviter toute interruption de service. - AnyxTech";
                                        @endphp

                                        <a href="{!! 'https://wa.me/' . $numero . '?text=' . urlencode($message) !!}" 
                                           target="_blank" 
                                           class="btn btn-success btn-sm whatsapp-btn"
                                           data-bs-toggle="tooltip"
                                           data-bs-title="Relancer par WhatsApp">
                                            <i class="fab fa-whatsapp"></i>
                                            <span class="d-none d-md-inline">Relancer</span>
                                        </a>

                                        <form action="{{ route('clients.reactiver', $client->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-primary btn-sm"
                                                    data-bs-toggle="tooltip" data-bs-title="Réactiver le client">
                                                <i class="fas fa-play"></i>
                                                <span class="d-none d-md-inline">Réactiver</span>
                                            </button>
                                        </form>

                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if($clients->hasPages())
                <div class="pagination-container px-4 py-3 d-flex flex-column flex-md-row justify-content-between align-items-center border-top">
                    <div class="pagination-info mb-2 mb-md-0">
                        Affichage de <span class="fw-semibold">{{ $clients->firstItem() }}</span> 
                        à <span class="fw-semibold">{{ $clients->lastItem() }}</span> 
                        sur <span class="fw-semibold">{{ $clients->total() }}</span> clients
                    </div>
                    
                    <nav aria-label="Pagination">
                        <ul class="pagination pagination-sm mb-0">
                            {{-- Précédent --}}
                            <li class="page-item {{ $clients->onFirstPage() ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ $clients->previousPageUrl() }}" aria-label="Précédent">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                            
                            {{-- Pages --}}
                            @foreach ($clients->getUrlRange(max(1, $clients->currentPage() - 2), min($clients->lastPage(), $clients->currentPage() + 2)) as $page => $url)
                                <li class="page-item {{ $page == $clients->currentPage() ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                </li>
                            @endforeach
                            
                            {{-- Suivant --}}
                            <li class="page-item {{ !$clients->hasMorePages() ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ $clients->nextPageUrl() }}" aria-label="Suivant">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
                @endif
            </div>
        </div>
    @endif
</div>

<style>
    :root {
        --warning-color: #ffc107;
        --warning-light: #fff3cd;
        --danger-light: #f8d7da;
        --success-light: #d1e7dd;
    }

    .bg-gradient-warning {
        background: linear-gradient(135deg, #ffc107, #fd7e14);
    }

    .avatar-sm {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .avatar-title {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .whatsapp-btn {
        background-color: #25D366;
        border-color: #25D366;
    }

    .whatsapp-btn:hover {
        background-color: #1da851;
        border-color: #1da851;
    }

    .table th {
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .table td {
        vertical-align: middle;
    }

    .bg-warning-light {
        background-color: var(--warning-light);
    }

    .bg-danger-light {
        background-color: var(--danger-light);
    }

    .bg-success-light {
        background-color: var(--success-light);
    }

    .badge {
        font-weight: 500;
        padding: 0.35em 0.65em;
    }

    @media (max-width: 768px) {
        .container-fluid {
            padding-left: 1rem;
            padding-right: 1rem;
        }
        
        .display-6 {
            font-size: 1.75rem;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Activer les tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Fonction de recherche
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const value = this.value.toLowerCase();
            const rows = document.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(value) ? '' : 'none';
            });
        });
    }
});
</script>
@endsection