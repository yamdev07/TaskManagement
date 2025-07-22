@extends('layouts.app')

@section('title', 'Liste des clients')

@section('content')
<div class="container-fluid px-4 py-5">
    {{-- Header avec gradient AnyxTech --}}
    <div class="row mb-5">
        <div class="col-12">
            <div class="bg-gradient-anyxtech rounded-4 p-4 text-white shadow-lg">
                <div class="d-flex align-items-center justify-content-between flex-wrap">
                    <div>
                        <h1 class="display-6 fw-bold mb-2">
                            <i class="fas fa-users me-3"></i>
                            Gestion des Clients
                        </h1>
                        <p class="lead mb-0 opacity-90">Tableau de bord des abonnements</p>
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

    {{-- Statistiques Cards avec design moderne --}}
    {{-- Les variables $totalClientsCount, $payes, $nonPayes, $actifs, $suspendus viennent du contrôleur --}}
    @if ($totalClientsCount > 0 || $clients->count() > 0) 
        <div class="row g-4 mb-5">
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card border-0 shadow-sm h-100 card-hover">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-success-light rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                    <i class="fas fa-check-circle text-success fs-4"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="text-muted mb-1 fw-semibold">Clients Payés</h6>
                                <h3 class="mb-0 text-success fw-bold">{{ $payes }}</h3>
                                <small class="text-muted">sur {{ $totalClientsCount }} clients</small>
                            </div>
                        </div>
                        <div class="progress mt-3" style="height: 6px;">
                            <div class="progress-bar bg-success" style="width: {{ $totalClientsCount > 0 ? ($payes / $totalClientsCount) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card border-0 shadow-sm h-100 card-hover">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-danger-light rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                    <i class="fas fa-exclamation-triangle text-danger fs-4"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="text-muted mb-1 fw-semibold">Non Payés</h6>
                                <h3 class="mb-0 text-danger fw-bold">{{ $nonPayes }}</h3>
                                <small class="text-muted">en attente</small>
                            </div>
                        </div>
                        <div class="progress mt-3" style="height: 6px;">
                            <div class="progress-bar bg-danger" style="width: {{ $totalClientsCount > 0 ? ($nonPayes / $totalClientsCount) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card border-0 shadow-sm h-100 card-hover">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-anyxtech-light rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                    <i class="fas fa-wifi text-anyxtech fs-4"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="text-muted mb-1 fw-semibold">Clients Actifs</h6>
                                <h3 class="mb-0 text-anyxtech fw-bold">{{ $actifs }}</h3>
                                <small class="text-muted">connectés</small>
                            </div>
                        </div>
                        <div class="progress mt-3" style="height: 6px;">
                            <div class="progress-bar bg-anyxtech" style="width: {{ $totalClientsCount > 0 ? ($actifs / $totalClientsCount) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card border-0 shadow-sm h-100 card-hover">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-warning-light rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                    <i class="fas fa-pause-circle text-warning fs-4"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="text-muted mb-1 fw-semibold">Suspendus</h6>
                                <h3 class="mb-0 text-warning fw-bold">{{ $suspendus }}</h3>
                                <small class="text-muted">temporairement</small>
                            </div>
                        </div>
                        <div class="progress mt-3" style="height: 6px;">
                            <div class="progress-bar bg-warning" style="width: {{ $totalClientsCount > 0 ? ($suspendus / $totalClientsCount) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Barre d'outils moderne --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="position-relative">
                        <i class="fas fa-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                        {{-- La recherche soumet un formulaire pour que Laravel gère la pagination --}}
                        <form action="{{ url()->current() }}" method="GET" class="d-inline-block w-100">
                            <input type="text" 
                                   class="form-control form-control-lg border-0 bg-light ps-5" 
                                   id="searchInput" 
                                   name="search" {{-- Ajout de l'attribut name --}}
                                   placeholder="Rechercher par nom, contact ou site relais..."
                                   value="{{ request('search') }}" {{-- Garde la valeur de recherche après soumission --}}
                                   style="border-radius: 12px;">
                        </form>
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

    {{-- Tableau moderne --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 p-4">
            <h5 class="mb-0 text-dark fw-semibold">
                <i class="fas fa-table me-2 text-anyxtech"></i>
                Liste des clients
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-anyxtech text-white">
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
                        {{-- La partial client_list est maintenant responsable de rendre les TR pour la page actuelle --}}
                        @include('clients.partials.client_list', ['clients' => $clients])
                    </tbody>
                </table>
            </div>
        </div>
        {{-- Ajout de la pagination Laravel --}}
        <div class="card-footer bg-white border-0 p-4 d-flex justify-content-center">
            {{ $clients->appends(request()->input())->links('pagination::bootstrap-5') }} {{-- Ajoute les paramètres de recherche à la pagination --}}
        </div>
    </div>

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

    .card-hover {
        transition: all 0.3s ease;
        border-radius: 16px;
    }

    .card-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
    }

    .bg-anyxtech-light {
        background-color: var(--anyxtech-light) !important;
    }

    .bg-success-light {
        background-color: rgba(25, 135, 84, 0.1) !important;
    }

    .bg-danger-light {
        background-color: rgba(220, 53, 69, 0.1) !important;
    }

    .bg-warning-light {
        background-color: rgba(255, 193, 7, 0.1) !important;
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
        background-color: rgba(30, 58, 138, 0.02);
        transform: scale(1.001);
    }

    .form-control:focus {
        border-color: var(--anyxtech-secondary);
        box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.25);
    }

    .modal-content {
        border-radius: 20px;
    }

    .progress {
        border-radius: 10px;
        background-color: rgba(0, 0, 0, 0.05);
    }

    .progress-bar {
        border-radius: 10px;
    }

    /* Styles pour la pagination */
    .pagination {
        margin-top: 1rem;
    }

    .page-item .page-link {
        border-radius: 8px;
        margin: 0 4px;
        color: var(--anyxtech-primary);
        border: 1px solid var(--anyxtech-light);
        transition: all 0.3s ease;
    }

    .page-item.active .page-link {
        background: linear-gradient(135deg, var(--anyxtech-primary), var(--anyxtech-secondary));
        border-color: var(--anyxtech-primary);
        color: white;
        box-shadow: 0 4px 10px rgba(30, 58, 138, 0.2);
    }

    .page-item .page-link:hover {
        background-color: var(--anyxtech-light);
        border-color: var(--anyxtech-secondary);
        color: var(--anyxtech-primary);
        transform: translateY(-1px);
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

        .table-responsive {
            border-radius: 0.5rem; 
        }
    }
</style>

{{-- Scripts avec améliorations --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('searchInput');

        if (searchInput) {
            let searchTimeout;
            // Soumet le formulaire de recherche après un délai pour la pagination côté serveur
            searchInput.addEventListener('input', function () {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    this.closest('form').submit(); // Soumet le formulaire parent
                }, 400); // Délai de 400ms après la dernière frappe
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
            
            const modalElement = document.getElementById('successModal');
            modalElement.addEventListener('shown.bs.modal', function () {
                this.querySelector('.modal-content').style.animation = 'modalSlideIn 0.3s ease-out';
            });
            
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