@extends('layouts.app')

@section('title', 'Modifier le client')

@section('content')
<div class="container-fluid px-4 py-5">
    {{-- Header avec navigation --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex align-items-center mb-3">
                <a href="{{ route('clients.index') }}" class="btn btn-outline-anyxtech me-3">
                    <i class="fas fa-arrow-left me-2"></i>
                    Retour
                </a>
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('clients.index') }}" class="text-anyxtech">
                                    <i class="fas fa-users me-1"></i>
                                    Clients
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Modification</li>
                        </ol>
                    </nav>
                </div>
            </div>
            
            <div class="bg-gradient-anyxtech rounded-4 p-4 text-white shadow-lg">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 me-4">
                        <div class="bg-white bg-opacity-20 rounded-circle d-flex align-items-center justify-content-center" 
                             style="width: 60px; height: 60px;">
                            <i class="fas fa-user-edit fs-4 text-white"></i>
                        </div>
                    </div>
                    <div>
                        <h1 class="display-6 fw-bold mb-2">Modification Client</h1>
                        <p class="lead mb-0 opacity-90">{{ $client->nom_client }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Formulaire de modification --}}
    <div class="row justify-content-center">
        <div class="col-xl-8 col-lg-10">
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-white border-0 p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-3">
                            <div class="bg-anyxtech-light rounded-circle d-flex align-items-center justify-content-center" 
                                 style="width: 50px; height: 50px;">
                                <i class="fas fa-edit text-anyxtech fs-5"></i>
                            </div>
                        </div>
                        <div>
                            <h5 class="mb-0 text-dark fw-semibold">Informations du client</h5>
                            <p class="text-muted mb-0 small">Modifiez les informations ci-dessous</p>
                        </div>
                    </div>
                </div>
                
                <div class="card-body p-5">
                    <form action="{{ route('clients.update', $client->id) }}" method="POST" id="clientEditForm">
                        @csrf
                        @method('PUT')

                        <div class="row g-4">
                            {{-- Informations générales --}}
                            <div class="col-12">
                                <div class="border-start border-4 border-anyxtech ps-3 mb-4">
                                    <h6 class="text-anyxtech fw-semibold mb-1">Informations générales</h6>
                                    <p class="text-muted mb-0 small">Données de base du client</p>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-floating mb-0">
                                    <input type="text" 
                                           name="nom_client" 
                                           id="nom_client" 
                                           class="form-control form-control-modern" 
                                           value="{{ old('nom_client', $client->nom_client) }}" 
                                           placeholder="Nom du client"
                                           required>
                                    <label for="nom_client">
                                        <i class="fas fa-user me-2 text-anyxtech"></i>
                                        Nom du client
                                    </label>
                                    @error('nom_client')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-floating mb-0">
                                    <input type="text" 
                                           name="contact" 
                                           id="contact" 
                                           class="form-control form-control-modern" 
                                           value="{{ old('contact', $client->contact) }}" 
                                           placeholder="Contact"
                                           required>
                                    <label for="contact">
                                        <i class="fas fa-phone me-2 text-anyxtech"></i>
                                        Contact
                                    </label>
                                    @error('contact')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-floating mb-0">
                                    <input type="text" 
                                           name="sites_relais" 
                                           id="sites_relais" 
                                           class="form-control form-control-modern" 
                                           value="{{ old('sites_relais', $client->sites_relais) }}" 
                                           placeholder="Site relais">
                                    <label for="sites_relais">
                                        <i class="fas fa-map-marker-alt me-2 text-anyxtech"></i>
                                        Site relais
                                    </label>
                                    @error('sites_relais')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-floating mb-0">
                                    <input type="text" 
                                           name="categorie" 
                                           id="categorie" 
                                           class="form-control form-control-modern" 
                                           value="{{ old('categorie', $client->categorie) }}" 
                                           placeholder="Catégorie">
                                    <label for="categorie">
                                        <i class="fas fa-tags me-2 text-anyxtech"></i>
                                        Catégorie
                                    </label>
                                    @error('categorie')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Statut et paiement --}}
                            <div class="col-12 mt-5">
                                <div class="border-start border-4 border-info ps-3 mb-4">
                                    <h6 class="text-info fw-semibold mb-1">Statut et paiement</h6>
                                    <p class="text-muted mb-0 small">État du compte et informations de paiement</p>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-floating mb-0">
                                    <select name="statut" id="statut" class="form-select form-select-modern" required>
                                        <option value="actif" {{ old('statut', $client->statut) === 'actif' ? 'selected' : '' }}>
                                            <i class="fas fa-check-circle"></i> Actif
                                        </option>
                                        <option value="inactif" {{ old('statut', $client->statut) === 'inactif' ? 'selected' : '' }}>
                                            <i class="fas fa-times-circle"></i> Inactif
                                        </option>
                                        <option value="suspendu" {{ old('statut', $client->statut) === 'suspendu' ? 'selected' : '' }}>
                                            <i class="fas fa-pause-circle"></i> Suspendu
                                        </option>
                                    </select>
                                    <label for="statut">
                                        <i class="fas fa-info-circle me-2 text-info"></i>
                                        Statut du compte
                                    </label>
                                    @error('statut')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-floating mb-0">
                                    <select name="a_paye" id="a_paye" class="form-select form-select-modern" required>
                                        <option value="1" {{ old('a_paye', $client->a_paye) == 1 ? 'selected' : '' }}>
                                            ✅ Payé
                                        </option>
                                        <option value="0" {{ old('a_paye', $client->a_paye) == 0 ? 'selected' : '' }}>
                                            ❌ Non payé
                                        </option>
                                    </select>
                                    <label for="a_paye">
                                        <i class="fas fa-credit-card me-2 text-info"></i>
                                        Statut de paiement
                                    </label>
                                    @error('a_paye')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Informations de facturation --}}
                            <div class="col-12 mt-5">
                                <div class="border-start border-4 border-success ps-3 mb-4">
                                    <h6 class="text-success fw-semibold mb-1">Facturation et abonnement</h6>
                                    <p class="text-muted mb-0 small">Détails de l'abonnement et montants</p>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="form-floating mb-0">
                                    <input type="number" 
                                           name="jour_reabonnement" 
                                           id="jour_reabonnement" 
                                           class="form-control form-control-modern" 
                                           min="1" 
                                           max="31" 
                                           value="{{ old('jour_reabonnement', $client->jour_reabonnement) }}" 
                                           placeholder="Jour"
                                           required>
                                    <label for="jour_reabonnement">
                                        <i class="fas fa-calendar-day me-2 text-success"></i>
                                        Jour de réabonnement
                                    </label>
                                    @error('jour_reabonnement')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="form-floating mb-0">
                                    <input type="date" 
                                           name="date_reabonnement" 
                                           id="date_reabonnement" 
                                           class="form-control form-control-modern" 
                                           value="{{ old('date_reabonnement', $client->date_reabonnement) }}" 
                                           required>
                                    <label for="date_reabonnement">
                                        <i class="fas fa-calendar me-2 text-success"></i>
                                        Date de réabonnement
                                    </label>
                                    @error('date_reabonnement')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="form-floating mb-0">
                                    <input type="number" 
                                           name="montant" 
                                           id="montant" 
                                           class="form-control form-control-modern" 
                                           value="{{ old('montant', $client->montant) }}" 
                                           placeholder="Montant"
                                           step="0.01"
                                           required>
                                    <label for="montant">
                                        <i class="fas fa-money-bill-wave me-2 text-success"></i>
                                        Montant (FCFA)
                                    </label>
                                    @error('montant')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Boutons d'action --}}
                        <div class="row mt-5">
                            <div class="col-12">
                                <hr class="my-4">
                                <div class="d-flex justify-content-end gap-3">
                                    <a href="{{ route('clients.index') }}" 
                                       class="btn btn-light btn-lg px-4 py-2">
                                        <i class="fas fa-times me-2"></i>
                                        Annuler
                                    </a>
                                    <button type="submit" 
                                            class="btn btn-anyxtech btn-lg px-4 py-2 shadow-sm">
                                        <i class="fas fa-save me-2"></i>
                                        <span class="btn-text">Mettre à jour</span>
                                        <span class="spinner-border spinner-border-sm d-none ms-2" role="status"></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
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
                            Client mis à jour
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <div class="bg-success-light rounded-circle d-flex align-items-center justify-content-center" 
                                     style="width: 50px; height: 50px;">
                                    <i class="fas fa-user-check text-success fs-5"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1 text-dark">Modification réussie !</h6>
                                <p class="mb-0 text-muted">{{ session('success') }}</p>
                            </div>
                        </div>
                        
                        <div class="progress mt-3" style="height: 4px;">
                            <div class="progress-bar bg-anyxtech progress-countdown" style="width: 100%;"></div>
                        </div>
                        <small class="text-muted mt-2 d-block">
                            <i class="fas fa-clock me-1"></i>
                            Redirection automatique dans <span id="countdown">3</span> secondes...
                        </small>
                    </div>
                    <div class="modal-footer border-0 p-4 pt-0">
                        <a href="{{ route('clients.index') }}" class="btn btn-anyxtech px-4">
                            <i class="fas fa-arrow-left me-2"></i>
                            Retour à la liste
                        </a>
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

    .btn-outline-anyxtech {
        border: 2px solid var(--anyxtech-primary);
        color: var(--anyxtech-primary);
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-outline-anyxtech:hover {
        background: var(--anyxtech-primary);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(30, 58, 138, 0.2);
    }

    .bg-anyxtech-light {
        background-color: var(--anyxtech-light) !important;
    }

    .bg-success-light {
        background-color: rgba(25, 135, 84, 0.1) !important;
    }

    .form-control-modern, .form-select-modern {
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        padding: 0.75rem 1rem;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: #fafafa;
    }

    .form-control-modern:focus, .form-select-modern:focus {
        border-color: var(--anyxtech-secondary);
        box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.15);
        background: white;
        transform: translateY(-2px);
    }

    .form-floating > .form-control-modern:focus ~ label,
    .form-floating > .form-select-modern:focus ~ label {
        color: var(--anyxtech-primary);
        font-weight: 600;
    }

    .form-floating > label {
        color: #6b7280;
        font-weight: 500;
        font-size: 0.9rem;
    }

    .card {
        border-radius: 20px;
        transition: all 0.3s ease;
    }

    .breadcrumb-item a {
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .breadcrumb-item a:hover {
        color: var(--anyxtech-secondary) !important;
    }

    .progress-countdown {
        animation: countdown 3s linear;
    }

    @keyframes countdown {
        from { width: 100%; }
        to { width: 0%; }
    }

    .modal-content {
        border-radius: 20px;
        overflow: hidden;
    }

    .border-anyxtech {
        border-color: var(--anyxtech-primary) !important;
    }

    .form-floating .invalid-feedback {
        margin-top: 0.5rem;
        font-size: 0.875rem;
        color: #dc3545;
    }

    .btn-light {
        background-color: #f8f9fa;
        border: 2px solid #e9ecef;
        color: #6c757d;
        font-weight: 600;
        border-radius: 12px;
        transition: all 0.3s ease;
    }

    .btn-light:hover {
        background-color: #e9ecef;
        border-color: #adb5bd;
        color: #495057;
        transform: translateY(-1px);
    }

    @media (max-width: 768px) {
        .container-fluid {
            padding-left: 1rem;
            padding-right: 1rem;
        }
        
        .card-body {
            padding: 2rem 1.5rem !important;
        }
        
        .display-6 {
            font-size: 1.75rem;
        }

        .d-flex.justify-content-end.gap-3 {
            flex-direction: column;
            gap: 1rem !important;
        }

        .btn-lg {
            width: 100%;
        }
    }
</style>

{{-- Scripts avec améliorations --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('clientEditForm');
        const submitBtn = form.querySelector('button[type="submit"]');
        const btnText = submitBtn.querySelector('.btn-text');
        const spinner = submitBtn.querySelector('.spinner-border');

        // Animation de soumission du formulaire
        form.addEventListener('submit', function(e) {
            submitBtn.disabled = true;
            btnText.textContent = 'Mise à jour...';
            spinner.classList.remove('d-none');
            
            // Réactiver le bouton après 3 secondes en cas de problème
            setTimeout(() => {
                if (submitBtn.disabled) {
                    submitBtn.disabled = false;
                    btnText.textContent = 'Mettre à jour';
                    spinner.classList.add('d-none');
                }
            }, 3000);
        });

        // Validation en temps réel
        const inputs = form.querySelectorAll('input[required], select[required]');
        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                if (this.value.trim() === '') {
                    this.classList.add('is-invalid');
                } else {
                    this.classList.remove('is-invalid');
                    this.classList.add('is-valid');
                }
            });

            input.addEventListener('input', function() {
                if (this.classList.contains('is-invalid') && this.value.trim() !== '') {
                    this.classList.remove('is-invalid');
                    this.classList.add('is-valid');
                }
            });
        });

        // Modal de succès avec compte à rebours
        @if(session('success'))
            const successModal = new bootstrap.Modal(document.getElementById('successModal'));
            successModal.show();
            
            const countdownElement = document.getElementById('countdown');
            let countdown = 3;
            
            const countdownInterval = setInterval(() => {
                countdown--;
                if (countdownElement) {
                    countdownElement.textContent = countdown;
                }
                
                if (countdown <= 0) {
                    clearInterval(countdownInterval);
                    successModal.hide();
                    window.location.href = "{{ route('clients.index') }}";
                }
            }, 1000);

            // Arrêter le compte à rebours si l'utilisateur ferme manuellement
            document.getElementById('successModal').addEventListener('hidden.bs.modal', function () {
                clearInterval(countdownInterval);
            });
        @endif

        // Animation d'apparition des sections
        const sections = document.querySelectorAll('.border-start');
        sections.forEach((section, index) => {
            section.style.opacity = '0';
            section.style.transform = 'translateX(-20px)';
            
            setTimeout(() => {
                section.style.transition = 'all 0.5s ease';
                section.style.opacity = '1';
                section.style.transform = 'translateX(0)';
            }, index * 200);
        });

        // Prévisualisation des modifications
        const originalValues = {};
        inputs.forEach(input => {
            originalValues[input.name] = input.value;
        });

        // Indicateur de modifications non sauvegardées
        let hasChanges = false;
        inputs.forEach(input => {
            input.addEventListener('change', function() {
                hasChanges = (this.value !== originalValues[this.name]);
                updateSaveButton();
            });
        });

        function updateSaveButton() {
            if (hasChanges) {
                submitBtn.classList.add('btn-warning');
                submitBtn.classList.remove('btn-anyxtech');
                btnText.innerHTML = '<i class="fas fa-exclamation-triangle me-2"></i>Sauvegarder les modifications';
            } else {
                submitBtn.classList.remove('btn-warning');
                submitBtn.classList.add('btn-anyxtech');
                btnText.innerHTML = '<i class="fas fa-save me-2"></i>Mettre à jour';
            }
        }

        // Confirmation avant de quitter avec des modifications non sauvegardées
        window.addEventListener('beforeunload', function(e) {
            if (hasChanges) {
                e.preventDefault();
                e.returnValue = 'Vous avez des modifications non sauvegardées. Êtes-vous sûr de vouloir quitter ?';
                return e.returnValue;
            }
        });
    });
</script>
@endsection