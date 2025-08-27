@extends('layouts.app')

@section('title', 'Clients Actifs')

@section('content')
<div class="container-fluid px-4 py-5">
    {{-- En-tête moderne --}}
    <div class="row mb-5">
        <div class="col-12">
            <div class="dashboard-header rounded-4 p-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap">
                    <div>
                        <h1 class="display-6 fw-bold mb-2 text-white">
                            <i class="fas fa-bolt me-3"></i>
                            Clients Actifs
                        </h1>
                        <p class="lead mb-0 text-white-80">Gestion des abonnements actuellement actifs</p>
                    </div>
                    <div class="header-badge">
                        <i class="fas fa-calendar-alt me-2"></i>
                        {{ date('d/m/Y') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Barre d'outils --}}
    <div class="action-bar mb-4">
        <form method="GET" action="{{ route('clients.actifs') }}" class="d-flex flex-grow-1 gap-2">
            <div class="search-container flex-grow-1">
                <i class="fas fa-search search-icon"></i>
                <input type="text" 
                    name="search" 
                    class="search-input" 
                    placeholder="Rechercher un client..."
                    value="{{ request('search') }}">
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search me-2"></i> Rechercher
            </button>
        </form>

        <a href="{{ route('clients.index') }}" class="back-button">
            <i class="fas fa-arrow-left me-2"></i>
            Retour
        </a>
    </div>


    {{-- Contenu principal --}}
    @if($clients->isEmpty())
        <div class="empty-state">
            <i class="fas fa-users-slash empty-icon"></i>
            <h3>Aucun client actif</h3>
            <p class="text-muted">Aucun client n'est actuellement actif dans le système</p>
        </div>
    @else
        <div class="custom-card">
            <div class="card-header">
                <div class="header-content">
                    <i class="fas fa-table me-2"></i>
                    <h5>Liste des clients actifs</h5>
                    <span class="badge count-badge">{{ $clients->count() }}</span>
                </div>
                <div class="header-actions">
                    <form action="{{ route('clients.export') }}" method="POST" style="display: inline;">
                        @csrf
                        <input type="hidden" name="format" value="pdf">
                        <button type="submit" class="export-btn">
                            <i class="fas fa-file-export me-2"></i>
                            Exporter
                        </button>
                    </form>
                </div>


            </div>

            <div class="table-container">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Contact</th>
                            <th>Site</th>
                            <th>Catégorie</th>
                            <th>Réabonnement</th>
                            <th>Montant</th>
                            <th>Statut</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($clients as $client)
                        <tr>
                            <td>
                                <div class="client-info">
                                    <span class="client-name">{{ $client->nom_client }}</span>
                                    <span class="client-id">#{{ $client->id }}</span>
                                </div>
                            </td>
                            <td>{{ $client->contact }}</td>
                            <td>{{ $client->sites_relais ?? '-' }}</td>
                            <td>{{ $client->categorie ?? '-' }}</td>
                            <td>
                                @if($client->date_reabonnement)
                                <span class="date-badge">
                                    {{ \Carbon\Carbon::parse($client->date_reabonnement)->format('d/m/Y') }}
                                </span>
                                @else
                                -
                                @endif
                            </td>
                            <td>{{ number_format($client->montant, 0, ',', ' ') }} F</td>
                            <td>
                                @if($client->a_paye)
                                <span class="status-badge paid">
                                    <i class="fas fa-check-circle me-1"></i> Payé
                                </span>
                                @else
                                <span class="status-badge unpaid">
                                    <i class="fas fa-exclamation-circle me-1"></i> Non payé
                                </span>
                                @endif
                            </td>
                            <td>
                                <div class="action-buttons">
                                    {{-- Bouton Modifier --}}
                                    <a href="{{ route('clients.edit', $client->id) }}" 
                                       class="action-btn edit-btn"
                                       data-tooltip="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    {{-- Bouton Suspendre --}}
                                    <form action="{{ route('clients.suspendre', $client->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" 
                                                class="action-btn suspend-btn"
                                                data-tooltip="Suspendre"
                                                onclick="return confirm('Suspendre ce client ?')">
                                            <i class="fas fa-pause"></i>
                                        </button>
                                    </form>

                                    {{-- Bouton Paiement si non payé --}}
                                    @if(!$client->a_paye)
                                    <form action="{{ route('clients.marquer-paye', $client->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" 
                                                class="action-btn pay-btn"
                                                data-tooltip="Marquer payé">
                                            <i class="fas fa-check-circle"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="table-footer">
                <div class="showing-entries">
                    Affichage de <span>1</span> à <span>10</span> sur <span>{{ $clients->total() }}</span> entrées
                </div>
                <div class="pagination">
                    {{ $clients->links() }}
                </div>
            </div>
        </div>
    @endif
</div>

<style>
/* Variables */
:root {
    --primary-color: #4361ee;
    --secondary-color: #3f37c9;
    --success-color: #4cc9f0;
    --danger-color: #f72585;
    --warning-color: #f8961e;
    --dark-color: #212529;
    --light-color: #f8f9fa;
    --border-radius: 10px;
    --box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    --transition: all 0.3s ease;
}

/* En-tête */
.dashboard-header {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    box-shadow: var(--box-shadow);
}

.header-badge {
    background: rgba(255, 255, 255, 0.2);
    padding: 0.5rem 1rem;
    border-radius: 50px;
    font-weight: 500;
    color: white;
}

/* Barre d'actions */
.action-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.search-container {
    position: relative;
    flex-grow: 1;
    max-width: 400px;
}

.search-icon {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: #6c757d;
}

.search-input {
    width: 100%;
    padding: 0.75rem 1rem 0.75rem 3rem;
    border: 1px solid #dee2e6;
    border-radius: var(--border-radius);
    transition: var(--transition);
}

.search-input:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 0.2rem rgba(67, 97, 238, 0.25);
    outline: none;
}

.back-button {
    display: inline-flex;
    align-items: center;
    padding: 0.75rem 1.5rem;
    background-color: white;
    color: var(--dark-color);
    border: 1px solid #dee2e6;
    border-radius: var(--border-radius);
    font-weight: 500;
    text-decoration: none;
    transition: var(--transition);
}

.back-button:hover {
    background-color: #f8f9fa;
    color: var(--primary-color);
    border-color: var(--primary-color);
}

/* Carte principale */
.custom-card {
    background-color: white;
    border-radius: var(--border-radius);
    box-shadow: var(--box-shadow);
    overflow: hidden;
}

.card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem;
    border-bottom: 1px solid #f1f3f5;
}

.header-content {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.header-content h5 {
    margin: 0;
    font-weight: 600;
    color: var(--dark-color);
}

.count-badge {
    background-color: var(--primary-color);
    color: white;
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
    border-radius: 50px;
}

.export-btn {
    display: inline-flex;
    align-items: center;
    padding: 0.5rem 1rem;
    background-color: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 6px;
    color: var(--dark-color);
    font-size: 0.875rem;
    transition: var(--transition);
}

.export-btn:hover {
    background-color: #e9ecef;
}

/* Tableau */
.table-container {
    overflow-x: auto;
    padding: 0 1.5rem;
}

.custom-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
}

.custom-table thead th {
    background-color: #f8f9fa;
    color: #495057;
    font-weight: 600;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 1rem;
    border-bottom: 2px solid #f1f3f5;
}

.custom-table tbody td {
    padding: 1rem;
    border-bottom: 1px solid #f1f3f5;
    vertical-align: middle;
}

.custom-table tbody tr:last-child td {
    border-bottom: none;
}

.custom-table tbody tr:hover {
    background-color: rgba(67, 97, 238, 0.03);
}

/* Styles des cellules */
.client-info {
    display: flex;
    flex-direction: column;
}

.client-name {
    font-weight: 500;
    color: var(--dark-color);
}

.client-id {
    font-size: 0.75rem;
    color: #6c757d;
}

.date-badge {
    display: inline-block;
    padding: 0.25rem 0.5rem;
    background-color: #e9ecef;
    border-radius: 4px;
    font-size: 0.875rem;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.35rem 0.75rem;
    border-radius: 50px;
    font-size: 0.75rem;
    font-weight: 500;
}

.status-badge.paid {
    background-color: rgba(40, 167, 69, 0.1);
    color: #28a745;
}

.status-badge.unpaid {
    background-color: rgba(220, 53, 69, 0.1);
    color: #dc3545;
}

/* Boutons d'action */
.action-buttons {
    display: flex;
    justify-content: flex-end;
    gap: 0.5rem;
}

.action-btn {
    width: 36px;
    height: 36px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    border: none;
    color: white;
    font-size: 1rem;
    cursor: pointer;
    transition: var(--transition);
    position: relative;
}

.action-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.action-btn::after {
    content: attr(data-tooltip);
    position: absolute;
    bottom: -40px;
    left: 50%;
    transform: translateX(-50%);
    background-color: var(--dark-color);
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.75rem;
    white-space: nowrap;
    opacity: 0;
    visibility: hidden;
    transition: var(--transition);
    z-index: 10;
}

.action-btn:hover::after {
    opacity: 1;
    visibility: visible;
    bottom: -35px;
}

.edit-btn {
    background-color: var(--primary-color);
}

.edit-btn:hover {
    background-color: #3a56d4;
}

.suspend-btn {
    background-color: var(--warning-color);
}

.suspend-btn:hover {
    background-color: #e07f0c;
}

.pay-btn {
    background-color: var(--success-color);
}

.pay-btn:hover {
    background-color: #3ab0d9;
}

.more-btn {
    background-color: #6c757d;
}

.more-btn:hover {
    background-color: #5a6268;
}

/* Menu déroulant */
.dropdown-menu {
    border: none;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    padding: 0.5rem;
}

.dropdown-item {
    padding: 0.5rem 1rem;
    border-radius: 6px;
    font-size: 0.875rem;
    transition: var(--transition);
}

.dropdown-item:hover {
    background-color: #f8f9fa;
    color: var(--primary-color);
}

/* Pied de tableau */
.table-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem;
    border-top: 1px solid #f1f3f5;
}

.showing-entries {
    font-size: 0.875rem;
    color: #6c757d;
}

.showing-entries span {
    font-weight: 500;
    color: var(--dark-color);
}

/* État vide */
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    background-color: white;
    border-radius: var(--border-radius);
    box-shadow: var(--box-shadow);
}

.empty-icon {
    font-size: 3rem;
    color: #adb5bd;
    margin-bottom: 1.5rem;
}

.empty-state h3 {
    color: var(--dark-color);
    margin-bottom: 0.5rem;
}

/* Responsive */
@media (max-width: 992px) {
    .action-bar {
        flex-direction: column;
        align-items: stretch;
    }
    
    .search-container {
        max-width: none;
    }
}

@media (max-width: 768px) {
    .card-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .header-actions {
        width: 100%;
    }
    
    .export-btn {
        width: 100%;
        justify-content: center;
    }
    
    .table-footer {
        flex-direction: column;
        gap: 1rem;
    }
}

@media (max-width: 576px) {
    .table-container {
        padding: 0 0.5rem;
    }
    
    .custom-table thead th,
    .custom-table tbody td {
        padding: 0.75rem 0.5rem;
    }
    
    .action-buttons {
        gap: 0.25rem;
    }
    
    .action-btn {
        width: 32px;
        height: 32px;
        font-size: 0.875rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialiser les tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-tooltip]'));
    
    // Fonction de recherche
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('.custom-table tbody tr');
            
            rows.forEach(row => {
                const rowText = row.textContent.toLowerCase();
                if (rowText.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }
    
    // Confirmation pour les actions critiques
    const confirmActions = document.querySelectorAll('[onclick^="return confirm"]');
    confirmActions.forEach(action => {
        action.addEventListener('click', function(e) {
            if (!confirm(this.getAttribute('data-confirm') || this.getAttribute('onclick').match(/confirm\('([^']+)'/)[1])) {
                e.preventDefault();
            }
        });
    });
});
</script>
@endsection