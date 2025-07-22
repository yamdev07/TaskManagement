@extends('layouts.app')

@section('title', 'Ajouter un client')

@section('content')
<div class="container-fluid px-4 py-5">
    {{-- Header avec gradient AnyxTech --}}
    <div class="row mb-5">
        <div class="col-12">
            <div class="bg-gradient-anyxtech rounded-4 p-4 text-white shadow-lg">
                <div class="d-flex align-items-center justify-content-between flex-wrap">
                    <div>
                        <h1 class="display-6 fw-bold mb-2">
                            <i class="fas fa-user-plus me-3"></i>
                            Ajouter un client
                        </h1>
                        <p class="lead mb-0 opacity-90">Remplissez les informations requises</p>
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

    {{-- Formulaire moderne --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <form action="{{ route('clients.store') }}" method="POST">
                @csrf

                <div class="row g-4">
                    {{-- Section Identité --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nom_client" class="form-label fw-semibold">
                                <i class="fas fa-user me-2 text-anyxtech"></i>
                                Nom du client <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="nom_client" class="form-control form-input" 
                                   value="{{ old('nom_client') }}" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="contact" class="form-label fw-semibold">
                                <i class="fas fa-phone me-2 text-anyxtech"></i>
                                Contact <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="contact" class="form-control form-input" 
                                   value="{{ old('contact') }}" required>
                        </div>
                    </div>

                    {{-- Section Professionnelle --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="sites_relais" class="form-label fw-semibold">
                                <i class="fas fa-map-marker-alt me-2 text-anyxtech"></i>
                                Site relais
                            </label>
                            <input type="text" name="sites_relais" class="form-control form-input" 
                                   value="{{ old('sites_relais') }}">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="statut" class="form-label fw-semibold">
                                <i class="fas fa-circle-notch me-2 text-anyxtech"></i>
                                Statut
                            </label>
                            <select name="statut" class="form-select form-input">
                                <option value="">-- Sélectionner --</option>
                                <option value="actif" {{ old('statut') === 'actif' ? 'selected' : '' }}>Actif</option>
                                <option value="inactif" {{ old('statut') === 'inactif' ? 'selected' : '' }}>Inactif</option>
                                <option value="suspendu" {{ old('statut') === 'suspendu' ? 'selected' : '' }}>Suspendu</option>
                            </select>
                        </div>
                    </div>

                    {{-- Section Abonnement --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="categorie" class="form-label fw-semibold">
                                <i class="fas fa-tag me-2 text-anyxtech"></i>
                                Catégorie
                            </label>
                            <input type="text" name="categorie" class="form-control form-input" 
                                   value="{{ old('categorie') }}">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="jour_reabonnement" class="form-label fw-semibold">
                                <i class="fas fa-calendar-day me-2 text-anyxtech"></i>
                                Jour de réabonnement <span class="text-danger">*</span>
                            </label>
                            <input type="number" name="jour_reabonnement" class="form-control form-input" 
                                   min="1" max="31" value="{{ old('jour_reabonnement') }}" required>
                            <small class="form-text text-muted">Exemple : 5 => tous les 5 du mois</small>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="montant" class="form-label fw-semibold">
                                <i class="fas fa-euro-sign me-2 text-anyxtech"></i>
                                Montant <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">€</span>
                                <input type="number" name="montant" class="form-control form-input" 
                                       min="0" value="{{ old('montant') }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 d-flex align-items-center">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="a_paye" 
                                   id="a_paye" value="1" {{ old('a_paye') ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="a_paye">
                                <i class="fas fa-check-circle me-2 text-anyxtech"></i>
                                Le client a payé ?
                            </label>
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="form-actions mt-5 pt-4 border-top">
                    <a href="{{ route('clients.index') }}" class="btn btn-secondary btn-lg px-4">
                        <i class="fas fa-arrow-left me-2"></i>
                        Annuler
                    </a>
                    <button type="submit" class="btn btn-anyxtech btn-lg px-4 float-end">
                        <i class="fas fa-save me-2"></i>
                        Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
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

    .card {
        border-radius: 16px;
        overflow: hidden;
    }

    .form-input, .form-select {
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 0.75rem 1rem;
        transition: all 0.3s;
    }

    .form-input:focus, .form-select:focus {
        border-color: var(--anyxtech-secondary);
        box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.25);
        outline: none;
    }

    .form-actions {
        display: flex;
        justify-content: space-between;
    }

    .form-check-input:checked {
        background-color: var(--anyxtech-primary);
        border-color: var(--anyxtech-primary);
    }

    .form-switch .form-check-input {
        width: 3em;
        height: 1.5em;
        margin-right: 0.5em;
    }

    .badge {
        border-radius: 12px;
    }

    @media (max-width: 768px) {
        .container-fluid {
            padding-left: 1rem;
            padding-right: 1rem;
        }
        
        .form-actions {
            flex-direction: column;
            gap: 1rem;
        }
        
        .btn {
            width: 100%;
        }
        
        .float-end {
            float: none !important;
        }
    }
</style>

{{-- Scripts --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Animation des champs lorsqu'ils reçoivent le focus
        const inputs = document.querySelectorAll('.form-input, .form-select');
        
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('input-focused');
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('input-focused');
            });
        });

        // Validation avant soumission
        const form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
            let isValid = true;
            const requiredFields = this.querySelectorAll('[required]');
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    field.style.borderColor = '#dc3545';
                    isValid = false;
                    
                    // Ajouter un message d'erreur
                    if (!field.nextElementSibling?.classList.contains('text-danger')) {
                        const errorDiv = document.createElement('div');
                        errorDiv.className = 'text-danger mt-1 small';
                        errorDiv.textContent = 'Ce champ est obligatoire';
                        field.parentNode.appendChild(errorDiv);
                    }
                } else {
                    field.style.borderColor = '#dee2e6';
                    const errorDiv = field.nextElementSibling;
                    if (errorDiv?.classList.contains('text-danger')) {
                        errorDiv.remove();
                    }
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                
                // Scroll vers le premier champ invalide
                const firstInvalid = this.querySelector('[required]:invalid');
                if (firstInvalid) {
                    firstInvalid.scrollIntoView({ 
                        behavior: 'smooth', 
                        block: 'center' 
                    });
                }
            }
        });
    });
</script>
@endsection