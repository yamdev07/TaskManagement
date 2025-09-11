@extends('layouts.app')

@section('title', 'Réabonnement à venir')

@section('content')
<div class="container-fluid px-4 py-5">
    {{-- Header avec gradient bleu AnyxTech --}}
    <div class="row mb-5">
        <div class="col-12">
            <div class="rounded-4 p-4 text-white shadow-lg"
                style="background: linear-gradient(90deg, #007BFF, #0056b3);">
                <div class="d-flex align-items-center justify-content-between flex-wrap">
                    <div>
                        <h1 class="display-6 fw-bold mb-2">
                            <i class="fas fa-calendar-alt me-3"></i>
                            Réabonnements à venir
                        </h1>
                        <p class="lead mb-0 opacity-90">Gestion des abonnements arrivant à échéance</p>
                    </div>
                    <div class="text-end">
                        <div class="badge bg-white text-dark fs-6 px-3 py-2">
                            <i class="fas fa-clock me-2"></i>
                            Prochains 30 jours
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- Message d'erreur --}}
    @if(session('error'))
        <div class="alert alert-danger border-0 shadow-sm mb-4">
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-circle me-3 fs-4"></i>
                <div>{{ session('error') }}</div>
            </div>
        </div>
    @endif

    {{-- Cartes de statistiques --}}
    @if ($clients->count())
        @php
            $total = $clients->count();
            $payes = $clients->where('a_paye', 1)->count();
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
                                <small class="text-muted">à relancer</small>
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
                    <a href="{{ route('clients.create') }}" 
                       class="btn btn-anyxtech btn-lg px-4 py-2 shadow-sm">
                        <i class="fas fa-plus me-2"></i>
                        Ajouter un client
                    </a>
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
                <h4 class="text-dark mb-3">Aucun réabonnement à venir</h4>
                <p class="text-muted mb-4">Aucun abonnement n'arrive à échéance dans les prochains jours</p>
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
                    Liste des réabonnements
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
                                <th class="fw-semibold py-3">Réabonnement</th>
                                <th class="fw-semibold py-3">Montant</th>
                                <th class="fw-semibold py-3 pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="clientTbody">
                            @foreach ($clients as $client)
                            <tr 
                                data-nom="{{ strtolower($client->nom_client) }}" 
                                data-siterelais="{{ strtolower($client->sites_relais ?? '') }}"
                            >
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
                                <td>{{ $client->categorie ?? '-' }}</td>
                                <td>
                                    <span class="badge bg-light text-dark">
                                        {{ $client->date_reabonnement 
                                            ? \Carbon\Carbon::parse($client->date_reabonnement)->format('d/m/Y') 
                                            : '-' 
                                        }}
                                    </span>
                                </td>
                                <td>{{ number_format($client->montant, 0, ',', ' ') }} F</td>
                                <td class="pe-4">
                                    @php
                                        $numero_brut = preg_replace('/[^0-9]/', '', $client->contact);
                                        if (strlen($numero_brut) === 8) { 
                                            $numero_brut = '229' . $numero_brut; 
                                        }

                                        $date = $client->date_reabonnement
                                            ? \Carbon\Carbon::parse($client->date_reabonnement)->format('d/m/Y')
                                            : 'bientôt';

                                        // Ici on met des vrais \n
                                        $message_whatsapp = "Bonjour cher(e) client(e) {$client->nom_client},\n"
                                            . "Nous vous notifions que votre abonnement Internet arrive à échéance le {$date}.\n\n"
                                            . "Nous vous prions de bien vouloir procéder au réabonnement pour éviter une interruption de vos services.\n\n"
                                            . "ANYXTECH - Grandissons ensemble !\n\n"
                                            . "📱 MomoPay : *880*41*833398*{$client->montant}#\n"
                                            . "📞 Services clientèle : 0141421563 / 0152415241";

                                        // Encoder correctement
                                        $encoded_message = rawurlencode($message_whatsapp);

                                        $whatsapp_link = "https://wa.me/{$numero_brut}?text={$encoded_message}";
                                    @endphp


                                    {{-- NOUVEAU LIEN WHATSAPP SIMPLIFIÉ --}}
                                    <a href="{{ $whatsapp_link }}" 
                                       target="_blank" {{-- Ouvre dans un nouvel onglet --}}
                                       class="btn btn-success btn-sm whatsapp-btn shadow-sm"
                                       data-bs-toggle="tooltip"
                                       data-bs-title="{{ $message_whatsapp }}">
                                        <i class="fab fa-whatsapp"></i>
                                        <span class="d-none d-md-inline">Relancer</span>
                                    </a>
                                    {{-- FIN DU NOUVEAU LIEN WHATSAPP SIMPLIFIÉ --}}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
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
                const nom = row.dataset.nom;
                const site = row.dataset.siterelais;
                if (nom.includes(value) || site.includes(value)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
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
    /* Styles AnyxTech existants */
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
</style>
@endsection