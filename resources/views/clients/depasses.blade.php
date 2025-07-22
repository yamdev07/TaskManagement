@extends('layouts.app')

@section('title', 'Réabonnement dépassé')

@section('content')
<div class="container-fluid px-4 py-5">
    {{-- Header avec gradient AnyxTech pour les réabonnements dépassés --}}
    <div class="row mb-5">
        <div class="col-12">
            <div class="bg-gradient-anyxtech-expired rounded-4 p-4 text-white shadow-lg">
                <div class="d-flex align-items-center justify-content-between flex-wrap">
                    <div>
                        <h1 class="display-6 fw-bold mb-2">
                            <i class="fas fa-exclamation-triangle me-3"></i>
                            Réabonnements Dépassés
                        </h1>
                        <p class="lead mb-0 opacity-90">Clients actifs avec abonnement expiré</p>
                    </div>
                    <div class="text-end">
                        <div class="badge bg-white text-dark fs-6 px-3 py-2">
                            <i class="fas fa-clock me-2"></i>
                            Échéance dépassée
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Cartes de statistiques --}}
    @if ($clients->count())
        @php
            $total = $clients->count();
            $payes = $clients->where('statut', 'payé')->count();
            $nonPayes = $total - $payes;
        @endphp

        <div class="row g-4 mb-5">
            <div class="col-xl-6 col-lg-6 col-md-6">
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
                                <small class="text-muted">sur {{ $total }} clients</small>
                            </div>
                        </div>
                        <div class="progress mt-3" style="height: 6px;">
                            <div class="progress-bar bg-success" style="width: {{ $total > 0 ? ($payes / $total) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-6 col-lg-6 col-md-6">
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
                                <small class="text-muted">urgence à régulariser</small>
                            </div>
                        </div>
                        <div class="progress mt-3" style="height: 6px;">
                            <div class="progress-bar bg-danger" style="width: {{ $total > 0 ? ($nonPayes / $total) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Barre d'outils --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="position-relative">
                        <i class="fas fa-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                        <input type="text" 
                            class="form-control form-control-lg border-0 bg-light ps-5" 
                            id="searchInput" 
                            placeholder="Rechercher par nom ou site relais..."
                            style="border-radius: 12px;">
                    </div>
                </div>
                <div class="col-lg-6 text-lg-end mt-3 mt-lg-0">
                    <form action="{{ route('clients.export') }}" method="POST" class="d-inline-block">
                        @csrf
                        <input type="hidden" name="type" value="expired">
                        <button type="submit" class="btn btn-anyxtech">
                            <i class="fas fa-download me-2"></i> Exporter PDF
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    {{-- Tableau --}}
    @if ($clients->isEmpty())
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center p-5">
                <div class="bg-anyxtech-light rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 80px; height: 80px;">
                    <i class="fas fa-check-circle fs-3 text-anyxtech"></i>
                </div>
                <h4 class="text-dark mb-3">Aucun réabonnement dépassé</h4>
                <p class="text-muted mb-4">Tous les abonnements sont à jour</p>
                <a href="{{ route('clients.index') }}" class="btn btn-anyxtech px-4">
                    <i class="fas fa-users me-2"></i> Voir tous les clients
                </a>
            </div>
        </div>
    @else
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 p-4">
                <h5 class="mb-0 text-dark fw-semibold">
                    <i class="fas fa-table me-2 text-anyxtech"></i>
                    Liste des réabonnements dépassés
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-anyxtech text-white">
                            <tr>
                                <th class="fw-semibold py-3 ps-4">ID</th>
                                <th class="fw-semibold py-3">Client</th>
                                <th class="fw-semibold py-3">Contact</th>
                                <th class="fw-semibold py-3">Site Relais</th>
                                <th class="fw-semibold py-3">Paiement</th>
                                <th class="fw-semibold py-3">Catégorie</th>
                                <th class="fw-semibold py-3">Date échéance</th>
                                <th class="fw-semibold py-3">Jours de retard</th>
                                <th class="fw-semibold py-3">Montant</th>
                                <th class="fw-semibold py-3 pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="clientTbody">
                            @foreach ($clients as $client)
                            <tr>
                                <td class="ps-4">{{ $client->id }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm me-3">
                                            <div class="avatar-title bg-anyxtech-light rounded-circle text-anyxtech">
                                                <i class="fas fa-user"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $client->nom_client }}</h6>
                                            <small class="text-muted">
                                                Depuis {{ $client->created_at ? $client->created_at->format('d/m/Y') : '-' }}
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $client->contact }}</td>
                                <td>{{ $client->sites_relais ?? 'Non renseigné' }}</td>
                                <td>
                                    @if(strtolower($client->statut) === 'payé')
                                    <span class="badge bg-success-light text-success">
                                        <i class="fas fa-check-circle me-1"></i> Payé
                                    </span>
                                    @else
                                    <span class="badge bg-danger-light text-danger">
                                        <i class="fas fa-times-circle me-1"></i> Non payé
                                    </span>
                                    @endif
                                </td>
                                <td>{{ $client->categorie ?? '-' }}</td>
                                <td>
                                    <span class="badge bg-light text-dark">
                                        {{ $client->date_reabonnement 
                                            ? \Carbon\Carbon::parse($client->date_reabonnement)->format('d/m/Y') 
                                            : '-' 
                                        }}
                                    </span>
                                </td>
                                <td>
                                    @php
                                        $daysLate = $client->date_reabonnement 
                                            ? now()->diffInDays(\Carbon\Carbon::parse($client->date_reabonnement), false)
                                            : 0;
                                    @endphp
                                    <span class="badge {{ $daysLate > 30 ? 'bg-danger' : 'bg-warning' }} text-white">
                                        {{ $daysLate }} jours
                                    </span>
                                </td>
                                <td>{{ number_format($client->montant, 0, ',', ' ') }} F</td>
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
                                            $message = "Bonjour {$client->nom_client}, votre abonnement est expiré depuis le {$date}. Merci de régulariser au plus vite pour éviter toute suspension de service. - AnyxTech";
                                        @endphp

                                        <a href="{!! 'https://wa.me/' . $numero . '?text=' . urlencode($message) !!}" 
                                           target="_blank" 
                                           class="btn btn-success btn-sm whatsapp-btn shadow-sm"
                                           data-bs-toggle="tooltip"
                                           data-bs-title="{{ $message }}">
                                            <i class="fab fa-whatsapp"></i>
                                            <span class="d-none d-md-inline">Relancer</span>
                                        </a>

                                        <a href="{{ route('clients.edit', $client->id) }}" 
                                           class="btn btn-primary btn-sm shadow-sm"
                                           data-bs-toggle="tooltip"
                                           data-bs-title="Modifier le client">
                                            <i class="fas fa-edit"></i>
                                            <span class="d-none d-md-inline">Modifier</span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @if($clients->hasPages())
            <div class="card-footer bg-white border-0 p-4 d-flex justify-content-center">
                {{ $clients->links('pagination::bootstrap-5') }}
            </div>
            @endif
        </div>
    @endif
</div>

{{-- Modal export --}}
<div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-gradient-anyxtech text-white border-0">
                <h5 class="modal-title fw-semibold" id="exportModalLabel">
                    <i class="fas fa-file-export me-2"></i>
                    Exporter la liste
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body p-4">
                <form action="{{ route('clients.export') }}" method="POST">
                    @csrf
                    <input type="hidden" name="type" value="expired">
                    <div class="mb-3">
                        <label for="exportFormat" class="form-label">Format d'export</label>
                        <select class="form-select" id="exportFormat" name="format">
                            <option value="excel">Excel (.xlsx)</option>
                            <option value="csv">CSV (.csv)</option>
                            <option value="pdf">PDF (.pdf)</option>
                        </select>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-anyxtech">
                            <i class="fas fa-download me-2"></i> Exporter
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Modal succès --}}
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

{{-- Script JS --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Recherche dynamique
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        const rows = document.querySelectorAll('#clientTbody tr');
        searchInput.addEventListener('input', function () {
            const value = this.value.toLowerCase();
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(value) ? '' : 'none';
            });
        });
    }

    // Modal succès auto hide
    @if(session('success'))
        const successModal = new bootstrap.Modal(document.getElementById('successModal'));
        successModal.show();
        setTimeout(() => successModal.hide(), 4000);
    @endif

    // Activer les tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>

<style>
    :root {
        --anyxtech-primary: #1e3a8a;
        --anyxtech-secondary: #3b82f6;
        --anyxtech-accent: #06b6d4;
        --anyxtech-light: #e0f2fe;
        --anyxtech-dark: #1e293b;
        --success-light: rgba(25, 135, 84, 0.1);
        --danger-light: rgba(220, 53, 69, 0.1);
        --warning-light: rgba(255, 193, 7, 0.1);
    }

    .bg-gradient-anyxtech-expired {
        background: linear-gradient(135deg, #ef4444 0%, #f59e0b 100%);
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
        background-color: var(--success-light) !important;
    }

    .bg-danger-light {
        background-color: var(--danger-light) !important;
    }

    .bg-warning-light {
        background-color: var(--warning-light) !important;
    }

    .whatsapp-btn {
        background-color: #25D366;
        border-color: #25D366;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .whatsapp-btn:hover {
        background-color: #1da851;
        border-color: #1da851;
        transform: translateY(-1px);
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

    .badge {
        font-weight: 500;
        border-radius: 10px;
        transition: all 0.3s ease;
    }

    .badge:hover {
        transform: translateY(-1px);
    }

    @media (max-width: 768px) {
        .container-fluid {
            padding-left: 1rem;
            padding-right: 1rem;
        }
        
        .display-6 {
            font-size: 1.75rem;
        }

        .btn-lg {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
        }
    }
</style>
@endsection