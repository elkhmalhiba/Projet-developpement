@extends('layouts.app')

@section('title', 'Modifier la ressource')

@push('styles')
<style>
    /* ============================================
       EDIT RESOURCE PAGE SPECIFIC STYLES
    ============================================ */

    /* Bouton de suppression spécifique */
    .btn-delete-confirm {
        position: relative;
        overflow: hidden;
    }

    .btn-delete-confirm:hover::before {
        content: '⚠️ ';
        position: absolute;
        left: 10px;
        animation: pulse 1s infinite;
    }

    @keyframes pulse {
        0% { opacity: 0.5; }
        50% { opacity: 1; }
        100% { opacity: 0.5; }
    }

    /* Alertes spécifiques pour l'édition */
    .alert-edit-warning {
        background: rgba(245, 158, 11, 0.1);
        border-color: rgba(245, 158, 11, 0.2);
        color: var(--warning);
        border-left: 4px solid var(--warning);
    }

    /* Style pour les champs modifiés */
    .form-control-modified {
        border-left: 3px solid var(--primary) !important;
    }

    /* Badge d'état actuel */
    .current-status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: var(--radius);
        font-weight: 600;
        margin-bottom: 1rem;
    }

    .current-status-badge.available {
        background: rgba(16, 185, 129, 0.1);
        color: var(--success);
        border: 1px solid rgba(16, 185, 129, 0.2);
    }

    .current-status-badge.occupied {
        background: rgba(245, 158, 11, 0.1);
        color: var(--warning);
        border: 1px solid rgba(245, 158, 11, 0.2);
    }

    .current-status-badge.maintenance {
        background: rgba(239, 68, 68, 0.1);
        color: var(--danger);
        border: 1px solid rgba(239, 68, 68, 0.2);
    }

    /* Indicateur de modification */
    .modified-indicator {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        font-size: 0.75rem;
        color: var(--warning);
        margin-left: 0.5rem;
    }
</style>
@endpush

@section('content')
<div class="container">
    <!-- En-tête de la page -->
    <div class="page-header-base" style="background: linear-gradient(135deg, var(--primary) 0%, #8b5cf6 100%);">
        <h1><i class='bx bx-edit'></i> Modifier la ressource</h1>
        <p class="page-header-subtitle">Modifiez les informations de l'équipement</p>
    </div>

    <!-- Formulaire d'édition -->
    <form action="{{ url("/admin/resources/{$resource->id}") }}" method="POST" class="create-form-base" id="editResourceForm">
        @csrf
        @method('PUT')

        <!-- Section 1: Informations de base -->
        <div class="form-section-base">
            <h3><i class='bx bx-info-circle'></i> Informations de base</h3>
            
            <div class="form-grid-base">
                <div class="form-group-enhanced">
                    <label for="name" class="form-label-enhanced">Nom de la ressource *</label>
                    <input type="text" 
                           class="form-control form-control-enhanced" 
                           id="name" 
                           name="name" 
                           required
                           value="{{ $resource->name }}"
                           oninput="markAsModified(this)">
                </div>

                <div class="form-group-enhanced">
                    <label for="resource_category_id" class="form-label-enhanced">Catégorie *</label>
                    <select class="form-select form-select-enhanced" 
                            id="resource_category_id" 
                            name="resource_category_id" 
                            required
                            onchange="markAsModified(this)">
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $resource->resource_category_id == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Section 2: Spécifications techniques -->
        <div class="form-section-base">
            <h3><i class='bx bx-cog'></i> Spécifications techniques</h3>
            
            <div class="form-grid-base">
                <div class="form-group-enhanced">
                    <label for="cpu" class="form-label-enhanced">CPU</label>
                    <input type="text" 
                           class="form-control form-control-enhanced" 
                           id="cpu" 
                           name="cpu" 
                           value="{{ $resource->cpu }}"
                           oninput="markAsModified(this)"
                           placeholder="ex: 2x Intel Xeon Silver 4314">
                </div>

                <div class="form-group-enhanced">
                    <label for="ram" class="form-label-enhanced">Mémoire RAM</label>
                    <input type="text" 
                           class="form-control form-control-enhanced" 
                           id="ram" 
                           name="ram" 
                           value="{{ $resource->ram }}"
                           oninput="markAsModified(this)"
                           placeholder="ex: 128 Go DDR4">
                </div>

                <div class="form-group-enhanced">
                    <label for="capacity" class="form-label-enhanced">Capacité de stockage</label>
                    <input type="text" 
                           class="form-control form-control-enhanced" 
                           id="capacity" 
                           name="capacity" 
                           value="{{ $resource->capacity }}"
                           oninput="markAsModified(this)"
                           placeholder="ex: 4 To SSD NVMe">
                </div>

                <div class="form-group-enhanced">
                    <label for="bandwidth" class="form-label-enhanced">Bande passante</label>
                    <input type="text" 
                           class="form-control form-control-enhanced" 
                           id="bandwidth" 
                           name="bandwidth" 
                           value="{{ $resource->bandwidth }}"
                           oninput="markAsModified(this)"
                           placeholder="ex: 10 Gbps">
                </div>

                <div class="form-group-enhanced">
                    <label for="os" class="form-label-enhanced">Système d'exploitation</label>
                    <input type="text" 
                           class="form-control form-control-enhanced" 
                           id="os" 
                           name="os" 
                           value="{{ $resource->os }}"
                           oninput="markAsModified(this)"
                           placeholder="ex: Ubuntu Server 22.04 LTS">
                </div>

                <div class="form-group-enhanced">
                    <label for="location" class="form-label-enhanced">Localisation physique</label>
                    <input type="text" 
                           class="form-control form-control-enhanced" 
                           id="location" 
                           name="location" 
                           value="{{ $resource->location }}"
                           oninput="markAsModified(this)"
                           placeholder="ex: Rack-A-24, Salle 102">
                </div>
            </div>
        </div>

        <!-- Section 3: Statut -->
        <div class="form-section-base">
            <h3><i class='bx bx-check-circle'></i> Statut</h3>
            
            <div class="form-group-enhanced">
                <label class="form-label-enhanced">Statut actuel</label>
                <div class="alert alert-edit-warning mb-3">
                    <i class='bx bx-info-circle'></i>
                    Le changement de statut affectera immédiatement la disponibilité de la ressource.
                </div>
                <select class="form-select form-select-enhanced" 
                        id="status" 
                        name="status" 
                        required
                        onchange="markAsModified(this)">
                    <option value="available" {{ $resource->status == 'available' ? 'selected' : '' }}>Disponible</option>
                    <option value="occupied" {{ $resource->status == 'occupied' ? 'selected' : '' }}>Occupé</option>
                    <option value="maintenance" {{ $resource->status == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                </select>
            </div>
        </div>

        <!-- Actions du formulaire -->
        <div class="form-actions-base">
            <div>
                <a href="{{ url('/admin/dashboard') }}" class="btn btn-secondary">
                    <i class='bx bx-x'></i> Annuler
                </a>
            </div>
            
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary" id="submitBtn">
                    <i class='bx bx-save'></i> Enregistrer les modifications
                </button>
                
                <button type="button" 
                        class="btn btn-danger btn-delete-confirm" 
                        onclick="confirmDelete()">
                    <i class='bx bx-trash'></i> Supprimer
                </button>
            </div>
        </div>
    </form>

    <!-- Formulaires de suppression séparés -->
    <form id="deleteForm" action="{{ url("/admin/resources/{$resource->id}") }}" method="POST" class="d-none">
        @csrf
        @method('DELETE')
    </form>
</div>

@push('scripts')
<script>
    // Marquer un champ comme modifié
    function markAsModified(element) {
        const originalValue = element.defaultValue || element.dataset.originalValue;
        const currentValue = element.value;
        
        if (originalValue !== currentValue) {
            element.classList.add('form-control-modified');
        } else {
            element.classList.remove('form-control-modified');
        }
        
        updateSubmitButton();
    }

    // Mettre à jour le texte du bouton d'envoi
    function updateSubmitButton() {
        const modifiedFields = document.querySelectorAll('.form-control-modified');
        const submitBtn = document.getElementById('submitBtn');
        
        if (modifiedFields.length > 0) {
            submitBtn.innerHTML = `<i class='bx bx-save'></i> Enregistrer (${modifiedFields.length} modification${modifiedFields.length > 1 ? 's' : ''})`;
        } else {
            submitBtn.innerHTML = `<i class='bx bx-save'></i> Enregistrer les modifications`;
        }
    }

    // Confirmation de suppression
    function confirmDelete() {
        const resourceName = "{{ $resource->name }}";
        const resourceType = "{{ $resource->category->name ?? 'ressource' }}";
        
        if (confirm(`ATTENTION : Êtes-vous sûr de vouloir supprimer la ${resourceType} "${resourceName}" ?\n\nCette action supprimera également :\n• Toutes les réservations associées\n• Les incidents signalés\n• L'historique d'utilisation\n\nCette action est IRREVERSIBLE.`)) {
            // Demander une confirmation supplémentaire
            if (confirm('DERNIÈRE CONFIRMATION : Tapez "SUPPRIMER" pour confirmer la suppression définitive.')) {
                const confirmation = prompt('Tapez "SUPPRIMER" (en majuscules) pour confirmer :');
                if (confirmation === 'SUPPRIMER') {
                    document.getElementById('deleteForm').submit();
                } else {
                    alert('Suppression annulée. Le texte ne correspond pas.');
                }
            }
        }
    }

    // Initialiser les valeurs originales
    document.addEventListener('DOMContentLoaded', function() {
        // Stocker les valeurs originales pour tous les champs
        document.querySelectorAll('input, select, textarea').forEach(element => {
            element.dataset.originalValue = element.value;
        });

        // Empêcher la navigation accidentelle si des modifications ont été faites
        let formModified = false;
        const form = document.getElementById('editResourceForm');
        
        form.addEventListener('input', function() {
            formModified = true;
        });

        window.addEventListener('beforeunload', function(e) {
            if (formModified) {
                const message = 'Vous avez des modifications non enregistrées. Êtes-vous sûr de vouloir quitter ?';
                e.returnValue = message;
                return message;
            }
        });

        // Empêcher la soumission du formulaire si aucun changement
        form.addEventListener('submit', function(e) {
            const modifiedFields = document.querySelectorAll('.form-control-modified');
            if (modifiedFields.length === 0) {
                e.preventDefault();
                alert('Aucune modification détectée. Aucune mise à jour nécessaire.');
                return false;
            }
            
            // Confirmation standard
            if (!confirm('Confirmez-vous la mise à jour de cette ressource ?')) {
                e.preventDefault();
                return false;
            }
        });
    });
</script>
@endpush
@endsection