@extends('layouts.app') {{-- Assurez-vous que votre layout principal est correctement défini et contient les scripts Bootstrap et Font Awesome --}}

@section('title', 'Détails du client: ' . ($client->nom_client ?? 'N/A'))

@section('content')
<div class="container-fluid px-4 py-5">
    {{-- Header de la page de détails --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="bg-gradient-anyxtech rounded-4 p-4 text-white shadow-lg d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <h1 class="display-6 fw-bold mb-2">
                        <i class="fas fa-user-circle me-3"></i>
                        Détails du Client: {{ $client->nom_client ?? 'N/A' }}
                    </h1>
                    <p class="lead mb-0 opacity-90">Informations complètes sur l'abonnement</p>
                </div>
                <div class="text-end mt-3 mt-md-0">
                    <a href="{{ route('clients.index') }}" class="btn btn-light btn-lg px-4 py-2 shadow-sm rounded-pill">
                        <i class="fas fa-arrow-left me-2"></i>
                        Retour à la liste
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Carte des informations générales du client --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-header bg-white border-bottom-0 p-4">
            <h5 class="mb-0 text-dark fw-semibold">
                <i class="fas fa-info-circle me-2 text-anyxtech"></i>
                Informations Générales
            </h5>
        </div>
        <div class="card-body p-4">
            <div class="row g-3">
                <div class="col-md-6 col-lg-4">
                    <div class="detail-item">
                        <small class="text-muted text-uppercase fw-bold d-block mb-1">Nom du Client</small>
                        <p class="fw-semibold fs-5 text-dark mb-0">{{ $client->nom_client ?? 'Non spécifié' }}</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="detail-item">
                        <small class="text-muted text-uppercase fw-bold d-block mb-1">Contact</small>
                        <p class="fw-semibold fs-5 text-dark mb-0">{{ $client->contact ?? 'Non spécifié' }}</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="detail-item">
                        <small class="text-muted text-uppercase fw-bold d-block mb-1">Email</small>
                        <p class="fw-semibold fs-5 text-dark mb-0">{{ $client->email ?? 'N/A' }}</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="detail-item">
                        <small class="text-muted text-uppercase fw-bold d-block mb-1">Site Relais</small>
                        <p class="fw-semibold fs-5 text-dark mb-0">{{ $client->site_relais ?? 'N/A' }}</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="detail-item">
                        <small class="text-muted text-uppercase fw-bold d-block mb-1">Catégorie</small>
                        <p class="fw-semibold fs-5 text-dark mb-0">{{ $client->categorie ?? 'Non classé' }}</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="detail-item">
                        <small class="text-muted text-uppercase fw-bold d-block mb-1">Statut Actuel</small>
                        @if ($client->statut == 'actif')
                            <span class="badge bg-success-subtle text-success fs-6 py-2 px-3 rounded-pill">Actif</span>
                        @elseif ($client->statut == 'suspendu')
                            <span class="badge bg-warning-subtle text-warning fs-6 py-2 px-3 rounded-pill">Suspendu</span>
                        @else
                            <span class="badge bg-secondary-subtle text-secondary fs-6 py-2 px-3 rounded-pill">{{ $client->statut ?? 'Inconnu' }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Carte des informations d'abonnement et de paiement --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-header bg-white border-bottom-0 p-4">
            <h5 class="mb-0 text-dark fw-semibold">
                <i class="fas fa-calendar-alt me-2 text-anyxtech"></i>
                Informations d'Abonnement et Paiement
            </h5>
        </div>
        <div class="card-body p-4">
            <div class="row g-3">
                <div class="col-md-6 col-lg-4">
                    <div class="detail-item">
                        <small class="text-muted text-uppercase fw-bold d-block mb-1">Jour de Réabonnement</small>
                        <p class="fw-semibold fs-5 text-dark mb-0">{{ $client->jour_reabonnement ?? 'Non défini' }}</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="detail-item">
                        <small class="text-muted text-uppercase fw-bold d-block mb-1">Date de Réabonnement</small>
                        <p class="fw-semibold fs-5 text-dark mb-0">
                            @if ($client->date_reabonnement)
                                {{ \Carbon\Carbon::parse($client->date_reabonnement)->format('d F Y') }}
                                @if (\Carbon\Carbon::parse($client->date_reabonnement)->isPast() && !$client->a_paye)
                                    <span class="badge bg-danger ms-2">Expiré</span>
                                @elseif (\Carbon\Carbon::parse($client->date_reabonnement)->diffInDays(now()) <= 7 && !$client->a_paye)
                                    <span class="badge bg-warning ms-2">Bientôt</span>
                                @endif
                            @else
                                Non défini
                            @endif
                        </p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="detail-item">
                        <small class="text-muted text-uppercase fw-bold d-block mb-1">Montant de l'abonnement</small>
                        <p class="fw-semibold fs-5 text-anyxtech mb-0">{{ number_format($client->montant ?? 0, 0, ',', ' ') }} FCFA</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="detail-item">
                        <small class="text-muted text-uppercase fw-bold d-block mb-1">Statut de Paiement</small>
                        @if ($client->a_paye)
                            <span class="badge bg-success-subtle text-success fs-6 py-2 px-3 rounded-pill">Payé</span>
                        @else
                            <span class="badge bg-danger-subtle text-danger fs-6 py-2 px-3 rounded-pill">Non Payé</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="detail-item">
                        <small class="text-muted text-uppercase fw-bold d-block mb-1">Date d'ajout</small>
                        <p class="fw-semibold fs-5 text-dark mb-0">
                            {{ $client->created_at ? \Carbon\Carbon::parse($client->created_at)->format('d F Y H:i') : 'N/A' }}
                        </p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="detail-item">
                        <small class="text-muted text-uppercase fw-bold d-block mb-1">Dernière mise à jour</small>
                        <p class="fw-semibold fs-5 text-dark mb-0">
                            {{ $client->updated_at ? \Carbon\Carbon::parse($client->updated_at)->format('d F Y H:i') : 'N/A' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Actions Spécifiques au Client --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-header bg-white border-bottom-0 p-4">
            <h5 class="mb-0 text-dark fw-semibold">
                <i class="fas fa-cogs me-2 text-anyxtech"></i>
                Actions rapides
            </h5>
        </div>
        <div class="card-body p-4">
            <div class="d-flex flex-wrap gap-3">
                @if ($client->a_paye == 0)
                    <form action="{{ route('clients.reconnecter', $client->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-success px-4 py-2 shadow-sm rounded-pill">
                            <i class="fas fa-plug me-2"></i> Reconnecter (Marquer Payé)
                        </button>
                    </form>
                @else
                    <form action="{{ route('clients.deconnecter', $client->id) }}" method="POST">
                        @csrf
                        {{-- Laravel n'accepte pas PATCH/PUT avec un formulaire HTML simple si ce n'est pas _method, donc POST est ok pour deconnecter si c'est ce que vous aviez --}}
                        <button type="submit" class="btn btn-warning px-4 py-2 shadow-sm rounded-pill">
                            <i class="fas fa-plug-off me-2"></i> Déconnecter (Marquer Non Payé)
                        </button>
                    </form>
                @endif
                
                @if ($client->statut == 'actif')
                    <form action="{{ route('clients.suspendre', $client->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-danger px-4 py-2 shadow-sm rounded-pill">
                            <i class="fas fa-pause me-2"></i> Suspendre le Client
                        </button>
                    </form>
                @endif

                {{-- Si vous avez bien renommé la route WhatsApp comme suggéré --}}
                <form action="{{ route('clients.relancerWhatsApp', $client->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-success px-4 py-2 shadow-sm rounded-pill">
                        <i class="fab fa-whatsapp me-2"></i> Relancer par WhatsApp
                    </button>
                </form>
            </div>
        </div>
    </div>


    {{-- Boutons d'action principaux --}}
    <div class="d-flex justify-content-end gap-3 mt-5">
        <a href="{{ route('clients.edit', $client->id) }}" class="btn btn-anyxtech btn-lg px-4 py-2 shadow-sm">
            <i class="fas fa-edit me-2"></i>
            Modifier le Client
        </a>
        <form action="{{ route('clients.destroy', $client->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce client ? Cette action est irréversible.');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-outline-danger btn-lg px-4 py-2 shadow-sm">
                <i class="fas fa-trash-alt me-2"></i>
                Supprimer le Client
            </button>
        </form>
    </div>

</div>

{{-- Inclure le CSS personnalisé AnyxTech (copier/coller depuis votre index.blade.php) --}}
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

    .detail-item {
        background-color: #f8f9fa; /* Light grey background for detail sections */
        border-radius: 10px;
        padding: 1rem;
        border: 1px solid #e9ecef;
        height: 100%; /* Ensure all detail items have consistent height */
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .detail-item p {
        word-wrap: break-word; /* Ensure long text wraps */
        margin-bottom: 0; /* Remove extra margin */
    }

    /* Badges avec des couleurs subtiles */
    .badge-success-subtle {
        background-color: #d1e7dd;
        color: #0f5132;
    }

    .badge-danger-subtle {
        background-color: #f8d7da;
        color: #842029;
    }

    .badge-warning-subtle {
        background-color: #fff3cd;
        color: #664d03;
    }

    .badge-secondary-subtle {
        background-color: #e2e3e5;
        color: #495057;
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
@endsection