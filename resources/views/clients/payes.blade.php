@extends('layouts.app')

@section('title', 'Clients payés')

@section('content')
<div class="container-fluid px-4 py-5">
    {{-- Header avec gradient AnyxTech --}}
    <div class="row mb-5">
        <div class="col-12">
            <div class="bg-gradient-anyxtech rounded-4 p-4 text-white shadow-lg">
                <div class="d-flex align-items-center justify-content-between flex-wrap">
                    <div>
                        <h1 class="display-6 fw-bold mb-2">
                            <i class="fas fa-check-circle me-3"></i>
                            Clients Payés
                        </h1>
                        <p class="lead mb-0 opacity-90">Gestion des abonnements payants</p>
                    </div>
                    <div class="text-end">
                        <div class="badge bg-white text-dark fs-6 px-3 py-2">
                            <i class="fas fa-calendar-alt me-2"></i>
                            {{ date('d/m/Y') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Barre d'outils moderne --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="position-relative">
                        <i class="fas fa-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                        <input type="text" 
                               class="form-control form-control-lg border-0 bg-light ps-5" 
                               id="searchInput" 
                               placeholder="Rechercher par nom, contact ou site relais..."
                               style="border-radius: 12px;">
                    </div>
                </div>
                <div class="col-lg-6 text-lg-end mt-3 mt-lg-0">
                    <a href="{{ route('clients.create') }}" 
                       class="btn btn-anyxtech btn-lg px-4 py-2 shadow-sm">
                        <i class="fas fa-plus me-2"></i>
                        Ajouter un client
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if ($clients->isEmpty())
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center p-5">
                <i class="fas fa-info-circle fs-1 text-muted mb-4"></i>
                <h4 class="text-muted">Aucun client payé trouvé</h4>
                <p class="text-muted mb-4">Vous pouvez ajouter un nouveau client en cliquant sur le bouton ci-dessus</p>
                <a href="{{ route('clients.create') }}" class="btn btn-anyxtech">
                    <i class="fas fa-plus me-2"></i> Créer un client
                </a>
            </div>
        </div>
    @else
        {{-- Avertissement clients payés --}}
        <div class="row mb-4 text-center">
            <div class="col-md-2 offset-md-5">
                <div class="alert alert-success border-0 shadow-sm p-3">
                    <i class="fas fa-check-circle me-2"></i>
                    Clients Actifs Payés : <strong>{{ $payes }}</strong>
                </div>
            </div>
        </div>

        {{-- Tableau moderne --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 p-4">
                <h5 class="mb-0 text-dark fw-semibold">
                    <i class="fas fa-check-circle me-2 text-success"></i>
                    Liste des clients payés
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-success text-white">
                            <tr>
                                <th class="fw-semibold py-3 ps-4">No</th>
                                <th class="fw-semibold py-3">Client</th>
                                <th class="fw-semibold py-3">Contact</th>
                                <th class="fw-semibold py-3">Site Relais</th>
                                <th class="fw-semibold py-3">Statut</th>
                                <th class="fw-semibold py-3">Paiement</th>
                                <th class="fw-semibold py-3">Catégorie</th>
                                <th class="fw-semibold py-3">Réabonnement</th>
                                <th class="fw-semibold py-3">Montant</th>
                                <th class="fw-semibold py-3 pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="clientTbody" class="table-group-divider">
                            @foreach ($clients as $client)
                                <tr data-nom="{{ strtolower($client->nom_client) }}" data-siterelais="{{ strtolower($client->sites_relais ?? '') }}">
                                    <td class="ps-4">{{ $client->id }}</td>
                                    <td>{{ $client->nom_client }}</td>
                                    <td>{{ $client->contact }}</td>
                                    <td>{{ $client->sites_relais ?? '-' }}</td>
                                    <td>
                                        @if ($client->statut)
                                            <span class="badge bg-anyxtech">{{ strtoupper($client->statut) }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="text-success">
                                            <i class="fas fa-check-circle me-1"></i> Payé
                                        </span>
                                    </td>
                                    <td>{{ $client->categorie ?? '-' }}</td>
                                    <td>
                                        {{ $client->date_reabonnement 
                                            ? \Carbon\Carbon::parse($client->date_reabonnement)->format('d/m/Y') 
                                            : '-' 
                                        }}
                                    </td>
                                    <td>{{ number_format($client->montant, 0, ',', ' ') }} F</td>
                                    <td class="pe-4">
                                        <form method="POST" action="{{ route('clients.deconnecter', $client->id) }}" onsubmit="return confirm('Confirmer la déconnexion (non paiement) de ce client ?');">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-power-off me-1"></i> Déconnecter
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            {{-- Pagination à intégrer ici si nécessaire --}}
            <div class="p-4">
                {{ $clients->links() }}
            </div>
        </div>
    @endif

    {{-- Modal succès avec design AnyxTech --}}
    @if(session('success'))
        <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-header bg-gradient-anyxtech text-white border-0">
                        <h5 class="modal-title fw-semibold" id="successModalLabel">
                            <i class="fas fa-check-circle me-2"></i>
                            Opération réussie
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <div class="bg-success-light rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                    <i class="fas fa-check text-success fs-5"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <p class="mb-0 text-dark">{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4 pt-0">
                        <button type="button" class="btn btn-anyxtech px-4" data-bs-dismiss="modal">
                            <i class="fas fa-check me-2"></i>
                            Compris
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

{{-- CSS personnalisé AnyxTech --}}
<style>
    :root {
        --anyxtech-primary: #1e3a8a;
        --anyxtech-secondary: #3b82f6;
        --anyxtech-accent: #06b6d4;
        --anyxtech-light: #e0f2fe;
        --anyxtech-dark: #1e293b;
    }

    .bg-gradient-anyxtech {
        background: linear-gradient(135deg, var(--anyxtech-primary) 0%, var(--anyxtech-secondary) 50%, var(--anyxtech-accent) 100%);
    }

    .bg-anyxtech {
        background-color: var(--anyxtech-primary) !important;
    }

    .text-anyxtech {
        color: var(--anyxtech-primary) !important;
    }

    .btn-anyxtech {
        background: linear-gradient(135deg, var(--anyxtech-primary), var(--anyxtech-secondary));
        border: none;
        color: white;
        border-radius: 12px;
        transition: all 0.3s ease;
        font-weight: 600;
    }

    .btn-anyxtech:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(30, 58, 138, 0.3);
        color: white;
    }

    .table th {
        border: none;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .table td {
        border: none;
        padding: 1rem 0.75rem;
        vertical-align: middle;
    }

    .table tbody tr {
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        transition: all 0.2s ease;
    }

    .table tbody tr:hover {
        background-color: rgba(25, 135, 84, 0.03);
        transform: scale(1.001);
    }

    .form-control:focus {
        border-color: var(--anyxtech-secondary);
        box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.25);
    }

    .modal-content {
        border-radius: 20px;
    }

    .bg-success-light {
        background-color: rgba(25, 135, 84, 0.1) !important;
    }

    .badge.bg-anyxtech {
        background-color: var(--anyxtech-primary) !important;
        padding: 0.35em 0.65em;
        font-size: 0.75em;
        font-weight: 600;
    }

    @media (max-width: 768px) {
        .container-fluid {
            padding-left: 1rem;
            padding-right: 1rem;
        }
        
        .card-body {
            padding: 1.5rem !important;
        }
        
        .display-6 {
            font-size: 1.75rem;
        }
    }
</style>

{{-- Scripts avec améliorations --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('searchInput');

        function applySearchFilter(value) {
            const rows = document.querySelectorAll('#clientTbody tr');
            let visibleCount = 0;
            
            rows.forEach(row => {
                const nom = row.dataset.nom?.toLowerCase() || '';
                const site = row.dataset.siterelais?.toLowerCase() || '';
                const contact = row.cells[2]?.textContent?.toLowerCase() || '';
                
                const isVisible = nom.includes(value) || site.includes(value) || contact.includes(value);
                row.style.display = isVisible ? '' : 'none';
                
                if (isVisible) {
                    visibleCount++;
                    row.style.animation = 'fadeIn 0.3s ease-in';
                }
            });

            // Afficher un message si aucun résultat
            const noResultsRow = document.getElementById('noResultsRow');
            if (noResultsRow) {
                noResultsRow.remove();
            }
            
            if (visibleCount === 0 && value.length > 0) {
                const tbody = document.getElementById('clientTbody');
                const noResultsHtml = `
                    <tr id="noResultsRow">
                        <td colspan="10" class="text-center py-5 text-muted">
                            <i class="fas fa-search fs-1 mb-3 opacity-50"></i>
                            <p class="mb-0">Aucun client payé trouvé pour "<strong>${value}</strong>"</p>
                        </td>
                    </tr>
                `;
                tbody.insertAdjacentHTML('beforeend', noResultsHtml);
            }
        }

        let isTyping = false;
        let searchTimeout;

        if (searchInput) {
            searchInput.addEventListener('input', function () {
                isTyping = true;
                clearTimeout(searchTimeout);
                
                const searchValue = this.value.toLowerCase();
                
                // Débounce la recherche pour de meilleures performances
                searchTimeout = setTimeout(() => {
                    applySearchFilter(searchValue);
                    isTyping = false;
                }, 300);
            });

            // Effet focus sur la barre de recherche
            searchInput.addEventListener('focus', function() {
                this.parentElement.classList.add('shadow-sm');
            });

            searchInput.addEventListener('blur', function() {
                this.parentElement.classList.remove('shadow-sm');
            });
        }

        // Modal de succès avec animation
        @if(session('success'))
            const successModal = new bootstrap.Modal(document.getElementById('successModal'));
            successModal.show();
            
            // Animation d'entrée
            const modalElement = document.getElementById('successModal');
            modalElement.addEventListener('shown.bs.modal', function () {
                this.querySelector('.modal-content').style.animation = 'modalSlideIn 0.3s ease-out';
            });
            
            // Auto-fermeture après 4 secondes
            setTimeout(() => {
                successModal.hide();
            }, 4000);
        @endif
    });

    // Animation CSS supplémentaire
    const style = document.createElement('style');
    style.textContent = `
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes modalSlideIn {
            from { transform: translateY(-50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
    `;
    document.head.appendChild(style);
</script>
@endsection
