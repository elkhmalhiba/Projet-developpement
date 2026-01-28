@extends('layouts.app')

@section('title', 'Créer une nouvelle ressource')

@push('styles')
    <style>
        /* ============================================
               CREATE RESOURCE PAGE SPECIFIC STYLES
            ============================================ */

        /* Style spécifique pour les options de statut */
        .status-option-base.available.active {
            background: rgba(16, 185, 129, 0.1);
            border-color: var(--success);
        }

        .status-option-base.occupied.active {
            background: rgba(245, 158, 11, 0.1);
            border-color: var(--warning);
        }

        .status-option-base.maintenance.active {
            background: rgba(239, 68, 68, 0.1);
            border-color: var(--danger);
        }

        /* Prévisualisation spécifique */
        .preview-badge-base.success {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success);
            border: 1px solid rgba(16, 185, 129, 0.2);
        }

        .preview-badge-base.warning {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning);
            border: 1px solid rgba(245, 158, 11, 0.2);
        }

        .preview-badge-base.danger {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger);
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        /* Icônes de catégories */
        .category-icon-server {
            color: var(--primary);
        }

        .category-icon-cloud {
            color: #06b6d4;
        }

        .category-icon-storage {
            color: var(--success);
        }

        .category-icon-network {
            color: #8b5cf6;
        }

        .category-icon-security {
            color: var(--danger);
        }

        .category-icon-compute {
            color: var(--warning);
        }

        .category-icon-data {
            color: #3b82f6;
        }

        .category-icon-analytics {
            color: #8b5cf6;
        }

        /* Champs spécifiques pour la création */
        .form-control-enhanced:focus,
        .form-select-enhanced:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        /* Label spécifique */
        .form-label-enhanced {
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--text-main);
            font-size: 0.95rem;
        }

        /* Petite aide sous les champs */
        .form-help-text {
            font-size: 0.85rem;
            color: var(--text-muted);
            margin-top: 0.25rem;
            display: block;
        }
    </style>
@endpush

@section('content')
    <div class="container">
        <!-- En-tête de la page -->
        <div class="page-header-base" style="background: linear-gradient(135deg, var(--primary) 0%, #8b5cf6 100%);">
            <h1><i class='bx bx-plus-circle'></i> Créer une nouvelle ressource</h1>
            <p class="page-header-subtitle">Ajoutez un nouvel équipement au catalogue IT</p>
        </div>

        <!-- Formulaire de création -->
        <form action="{{ url('/admin/resources') }}" method="POST" class="create-form-base" id="createResourceForm">
            @csrf
            <input type="hidden" name="resource_category_id" id="resource_category_id">

            <!-- Section 1: Informations de base -->
            <div class="form-section-base">
                <h3><i class='bx bx-info-circle'></i> Informations de base</h3>

                <div class="form-grid-base">
                    <div class="form-group-enhanced">
                        <label for="name" class="form-label-enhanced">Nom de la ressource *</label>
                        <input type="text" class="form-control form-control-enhanced" id="name" name="name" required
                            placeholder="ex: Serveur Dell PowerEdge R750" value="{{ old('name') }}">
                        <span class="form-help-text">Nom descriptif de l'équipement</span>
                    </div>
                </div>

                <!-- Sélection visuelle des catégories -->
                <div class="form-help-base">
                    <h4><i class='bx bx-help-circle'></i> Choisissez le type d'équipement</h4>
                </div>

                <div class="category-options-base" id="categoryVisual">
                    @php
                        $categoryIcons = [
                            1 => 'bx bxs-server category-icon-server',
                            2 => 'bx bxs-cloud category-icon-cloud',
                            3 => 'bx bxs-hdd category-icon-storage',
                            4 => 'bx bx-transfer-alt category-icon-network',
                            5 => 'bx bxs-shield category-icon-security',
                            6 => 'bx bx-loader-circle category-icon-compute',
                            7 => 'bx bx-data category-icon-data',
                            8 => 'bx bx-line-chart category-icon-analytics'
                        ];
                    @endphp
                    @foreach($categories as $category)
                        <div class="category-option-base" data-category-id="{{ $category->id }}">
                            <i class='{{ $categoryIcons[$category->id] ?? "bx bx-category" }}'></i>
                            <span>{{ $category->name }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Section 2: Spécifications techniques -->
            <div class="form-section-base">
                <h3><i class='bx bx-cog'></i> Spécifications techniques</h3>

                <div class="form-grid-base">
                    <div class="form-group-enhanced">
                        <label for="cpu" class="form-label-enhanced">CPU</label>
                        <input type="text" class="form-control form-control-enhanced" id="cpu" name="cpu"
                            placeholder="ex: 2x Intel Xeon Silver 4314" value="{{ old('cpu') }}">
                        <span class="form-help-text">Processeur(s)</span>
                    </div>

                    <div class="form-group-enhanced">
                        <label for="ram" class="form-label-enhanced">Mémoire RAM</label>
                        <input type="text" class="form-control form-control-enhanced" id="ram" name="ram"
                            placeholder="ex: 128 Go DDR4" value="{{ old('ram') }}">
                        <span class="form-help-text">Mémoire vive</span>
                    </div>

                    <div class="form-group-enhanced">
                        <label for="capacity" class="form-label-enhanced">Capacité de stockage</label>
                        <input type="text" class="form-control form-control-enhanced" id="capacity" name="capacity"
                            placeholder="ex: 4 To SSD NVMe" value="{{ old('capacity') }}">
                        <span class="form-help-text">Stockage principal</span>
                    </div>

                    <div class="form-group-enhanced">
                        <label for="bandwidth" class="form-label-enhanced">Bande passante</label>
                        <input type="text" class="form-control form-control-enhanced" id="bandwidth" name="bandwidth"
                            placeholder="ex: 10 Gbps" value="{{ old('bandwidth') }}">
                        <span class="form-help-text">Débit réseau</span>
                    </div>

                    <div class="form-group-enhanced">
                        <label for="os" class="form-label-enhanced">Système d'exploitation</label>
                        <input type="text" class="form-control form-control-enhanced" id="os" name="os"
                            placeholder="ex: Ubuntu Server 22.04 LTS" value="{{ old('os') }}">
                        <span class="form-help-text">OS installé</span>
                    </div>

                    <div class="form-group-enhanced">
                        <label for="location" class="form-label-enhanced">Localisation physique</label>
                        <input type="text" class="form-control form-control-enhanced" id="location" name="location"
                            placeholder="ex: Rack-A-24, Salle 102" value="{{ old('location') }}">
                        <span class="form-help-text">Emplacement dans le datacenter</span>
                    </div>
                </div>
            </div>

            <!-- Section 3: Statut et disponibilité -->
            <div class="form-section-base">
                <h3><i class='bx bx-check-circle'></i> Statut et disponibilité</h3>

                <div class="form-group-enhanced">
                    <label class="form-label-enhanced">Statut initial *</label>

                    <div class="status-options-base" id="statusVisual">
                        <div class="status-option-base available" data-status="available">
                            <i class='bx bx-check-circle'></i>
                            <div>
                                <strong>Disponible</strong>
                                <div class="text-muted small">Prêt à être réservé</div>
                            </div>
                        </div>

                        <div class="status-option-base occupied" data-status="occupied">
                            <i class='bx bx-time-five'></i>
                            <div>
                                <strong>Occupé</strong>
                                <div class="text-muted small">Déjà en utilisation</div>
                            </div>
                        </div>

                        <div class="status-option-base maintenance" data-status="maintenance">
                            <i class='bx bx-wrench'></i>
                            <div>
                                <strong>Maintenance</strong>
                                <div class="text-muted small">Non disponible</div>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" id="status" name="status" value="available" required>
                </div>
            </div>

            <!-- Section 4: Notes et informations complémentaires -->
            <div class="form-section-base">
                <h3><i class='bx bx-note'></i> Informations complémentaires</h3>

                <div class="form-group-enhanced">
                    <label for="notes" class="form-label-enhanced">Notes internes (optionnel)</label>
                    <textarea class="form-control form-control-enhanced" id="notes" name="notes" rows="3"
                        placeholder="Informations complémentaires pour l'équipe technique...">{{ old('notes') }}</textarea>
                    <span class="form-help-text">Ces notes ne sont visibles que par les administrateurs</span>
                </div>

                <div class="form-check-enhanced mt-3">
                    <input class="form-check-input" type="checkbox" id="monitoring_enabled" name="monitoring_enabled"
                        value="1" checked>
                    <label class="form-check-label" for="monitoring_enabled">
                        Activer le monitoring pour cette ressource
                    </label>
                </div>
            </div>

            <!-- Aperçu en temps réel -->
            <div class="preview-card-base">
                <div class="preview-header-base">
                    <div class="preview-title-base">
                        <i class='bx bx-show'></i> Aperçu de la ressource
                    </div>
                    <span class="preview-badge-base success" id="previewStatus">Disponible</span>
                </div>

                <div id="previewContent">
                    <h4 id="previewName" class="mb-2">Nouvelle ressource</h4>
                    <div class="text-muted small mb-3" id="previewCategory">Catégorie: -</div>

                    <div class="preview-specs-base" id="previewSpecs">
                        <div class="spec-item-base"><i class='bx bx-microchip'></i> <span id="previewCpu">CPU: -</span>
                        </div>
                        <div class="spec-item-base"><i class='bx bx-memory-card'></i> <span id="previewRam">RAM: -</span>
                        </div>
                        <div class="spec-item-base"><i class='bx bx-hdd'></i> <span id="previewCapacity">Stockage: -</span>
                        </div>
                        <div class="spec-item-base"><i class='bx bx-wifi'></i> <span id="previewBandwidth">Bande passante:
                                -</span></div>
                    </div>

                    <div class="mt-3">
                        <div class="spec-item-base"><i class='bx bx-map'></i> <span id="previewLocation">Localisation:
                                -</span></div>
                        <div class="spec-item-base"><i class='bx bx-laptop'></i> <span id="previewOs">OS: -</span></div>
                    </div>
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
                    <button type="reset" class="btn btn-warning">
                        <i class='bx bx-reset'></i> Réinitialiser
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class='bx bx-save'></i> Créer la ressource
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        // ================================
        // Variables globales
        // ================================
        let selectedCategoryName = '-';

        // ================================
        // Sélection visuelle des catégories
        // ================================
        document.querySelectorAll('.category-option-base').forEach(option => {
            option.addEventListener('click', function () {

                // Supprimer l'état actif
                document.querySelectorAll('.category-option-base').forEach(opt => {
                    opt.classList.remove('active');
                });

                // Activer la catégorie sélectionnée
                this.classList.add('active');

                // Récupérer les données
                const categoryId = this.getAttribute('data-category-id');
                selectedCategoryName = this.querySelector('span').innerText;

                // Mettre à jour le champ caché
                document.getElementById('resource_category_id').value = categoryId;

                // Mettre à jour l'aperçu
                updatePreview();
            });
        });

        // ================================
        // Sélection visuelle du statut
        // ================================
        document.querySelectorAll('.status-option-base').forEach(option => {
            option.addEventListener('click', function () {

                // Supprimer l'état actif
                document.querySelectorAll('.status-option-base').forEach(opt => {
                    opt.classList.remove('active');
                });

                // Activer le statut sélectionné
                this.classList.add('active');

                // Mettre à jour le champ caché
                const status = this.getAttribute('data-status');
                document.getElementById('status').value = status;

                // Mettre à jour l'aperçu
                updatePreview();
            });
        });

        // ================================
        // Mise à jour de l'aperçu en temps réel
        // ================================
        function updatePreview() {

            // Nom
            const name = document.getElementById('name').value || 'Nouvelle ressource';
            document.getElementById('previewName').textContent = name;

            // Catégorie (corrigé)
            document.getElementById('previewCategory').textContent =
                `Catégorie: ${selectedCategoryName}`;

            // Spécifications
            document.getElementById('previewCpu').textContent =
                `CPU: ${document.getElementById('cpu').value || '-'}`;

            document.getElementById('previewRam').textContent =
                `RAM: ${document.getElementById('ram').value || '-'}`;

            document.getElementById('previewCapacity').textContent =
                `Stockage: ${document.getElementById('capacity').value || '-'}`;

            document.getElementById('previewBandwidth').textContent =
                `Bande passante: ${document.getElementById('bandwidth').value || '-'}`;

            document.getElementById('previewLocation').textContent =
                `Localisation: ${document.getElementById('location').value || '-'}`;

            document.getElementById('previewOs').textContent =
                `OS: ${document.getElementById('os').value || '-'}`;

            // Statut
            const status = document.getElementById('status').value;

            const statusText = {
                available: 'Disponible',
                occupied: 'Occupé',
                maintenance: 'Maintenance'
            }[status] || 'Disponible';

            const statusClass = {
                available: 'success',
                occupied: 'warning',
                maintenance: 'danger'
            }[status] || 'success';

            const previewBadge = document.getElementById('previewStatus');
            previewBadge.textContent = statusText;
            previewBadge.className = `preview-badge-base ${statusClass}`;
        }

        // ================================
        // Écoute des changements des champs
        // ================================
        const formFields = [
            'name',
            'cpu',
            'ram',
            'capacity',
            'bandwidth',
            'location',
            'os'
        ];

        formFields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            field.addEventListener('input', updatePreview);
            field.addEventListener('change', updatePreview);
        });

        // ================================
        // Initialisation au chargement
        // ================================
        document.addEventListener('DOMContentLoaded', function () {

            // Activer la première catégorie par défaut
            const firstCategory = document.querySelector('.category-option-base');
            if (firstCategory) {
                firstCategory.classList.add('active');
                selectedCategoryName = firstCategory.querySelector('span').innerText;
                document.getElementById('resource_category_id').value =
                    firstCategory.getAttribute('data-category-id');
            }

            // Activer le statut "Disponible" par défaut
            const availableStatus = document.querySelector('.status-option-base.available');
            if (availableStatus) {
                availableStatus.classList.add('active');
                document.getElementById('status').value = 'available';
            }

            updatePreview();
        });

        // ================================
        // Validation du formulaire
        // ================================
        document.getElementById('createResourceForm').addEventListener('submit', function (e) {

            const name = document.getElementById('name').value.trim();
            const category = document.getElementById('resource_category_id').value;

            if (!name) {
                e.preventDefault();
                alert('Veuillez saisir un nom pour la ressource.');
                document.getElementById('name').focus();
                return;
            }

            if (!category) {
                e.preventDefault();
                alert('Veuillez sélectionner une catégorie.');
                return;
            }

            if (!confirm('Confirmez-vous la création de cette ressource ?')) {
                e.preventDefault();
            }
        });
    </script>

@endpush