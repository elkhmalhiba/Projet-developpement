@extends('layouts.app')

@section('title', 'Panel Admin')

@push('styles')
    <style>
        /* ============================================
           ADMIN DASHBOARD SPECIFIC STYLES
        ============================================ */

        /* Cartes de statistiques admin */
        .admin-stat-card.users::before { background: var(--primary); }
        .admin-stat-card.resources::before { background: var(--success); }
        .admin-stat-card.occupied::before { background: var(--warning); }
        .admin-stat-card.maintenance::before { background: var(--danger); }

        .stat-icon-wrapper.users { 
            background: rgba(59, 130, 246, 0.1); 
            color: var(--primary);
        }
        .stat-icon-wrapper.resources { 
            background: rgba(16, 185, 129, 0.1); 
            color: var(--success);
        }
        .stat-icon-wrapper.occupied { 
            background: rgba(245, 158, 11, 0.1); 
            color: var(--warning);
        }
        .stat-icon-wrapper.maintenance { 
            background: rgba(239, 68, 68, 0.1); 
            color: var(--danger);
        }

        /* Badges admin spécifiques */
        .badge-admin.primary { background: rgba(59, 130, 246, 0.1); color: var(--primary); }
        .badge-admin.success { background: rgba(16, 185, 129, 0.1); color: var(--success); }
        .badge-admin.warning { background: rgba(245, 158, 11, 0.1); color: var(--warning); }
        .badge-admin.danger { background: rgba(239, 68, 68, 0.1); color: var(--danger); }

        /* Style spécifique pour le tableau admin */
        .admin-section .table-base th {
            background: var(--bg-card);
        }

        /* Bouton de suppression dans le tableau */
        .btn-delete-table {
            padding: 0.4rem 0.6rem;
            font-size: 0.75rem;
        }
    </style>
@endpush

@section('content')
    <div class="container">
        <!-- En-tête admin -->
        <div class="page-header-base" style="background: linear-gradient(135deg, var(--primary) 0%, #8b5cf6 100%);">
            <h1><i class='bx bxs-dashboard'></i> Panel d'Administration</h1>
            <p class="page-header-subtitle">
                Gestion complète des utilisateurs, ressources et incidents
                <span class="badge-enhanced primary ms-2">
                    <i class='bx bx-shield-quarter'></i> Admin
                </span>
            </p>
        </div>

        <!-- Actions rapides -->
        <div class="quick-actions-base">
            <a href="/admin/resources/create" class="btn btn-primary">
                <i class='bx bx-plus'></i> Nouvelle ressource
            </a>
            <a href="{{ route('logout') }}" class="btn btn-danger"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class='bx bx-log-out'></i> Déconnexion
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </div>

        <!-- Cartes de statistiques -->
        <div class="stats-grid">
            <div class="stat-card-base admin-stat-card users">
                <div class="stat-icon-base stat-icon-wrapper users">
                    <i class='bx bx-user'></i>
                </div>
                <div class="stat-number-base">{{ $stats['total_users'] ?? 0 }}</div>
                <div class="stat-label-base">Utilisateurs</div>
                <div class="text-muted small mt-2">
                    {{ $stats['active_users'] ?? 0 }} actifs • {{ $stats['inactive_users'] ?? 0 }} inactifs
                </div>
            </div>

            <div class="stat-card-base admin-stat-card resources">
                <div class="stat-icon-base stat-icon-wrapper resources">
                    <i class='bx bx-server'></i>
                </div>
                <div class="stat-number-base">{{ $stats['total_resources'] ?? 0 }}</div>
                <div class="stat-label-base">Ressources IT</div>
                <div class="text-muted small mt-2">
                    {{ $stats['available_resources'] ?? 0 }} disponibles • {{ $stats['occupied_resources'] ?? 0 }} occupées
                </div>
            </div>

            <div class="stat-card-base admin-stat-card occupied">
                <div class="stat-icon-base stat-icon-wrapper occupied">
                    <i class='bx bx-trending-up'></i>
                </div>
                <div class="stat-number-base">{{ $stats['occupied_rate'] ?? 0 }}%</div>
                <div class="stat-label-base">Taux d'occupation</div>
                <div class="text-muted small mt-2">
                    Dernière mise à jour: {{ now()->format('H:i') }}
                </div>
            </div>

            <div class="stat-card-base admin-stat-card maintenance">
                <div class="stat-icon-base stat-icon-wrapper maintenance">
                    <i class='bx bx-wrench'></i>
                </div>
                <div class="stat-number-base">{{ $stats['maintenance_count'] ?? 0 }}</div>
                <div class="stat-label-base">En maintenance</div>
                <div class="text-muted small mt-2">
                    <i class='bx bx-info-circle'></i> Nécessite attention
                </div>
            </div>
        </div>

        <!-- Section Utilisateurs -->
        <div class="section-base admin-section">
            <div class="section-header-base">
                <h2><i class='bx bx-group'></i> Gestion des Utilisateurs</h2>
                <span class="section-badge-base">{{ $users->count() }} utilisateurs</span>
            </div>

            <div class="table-responsive">
                <table class="table-base">
                    <thead>
                        <tr>
                            <th>Utilisateur</th>
                            <th>Email</th>
                            <th>Rôle</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>
                                    <div class="d-flex align-center gap-2">
                                        <div
                                            style="width: 40px; height: 40px; border-radius: 50%; background: var(--primary); color: white; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 1rem;">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <strong>{{ $user->name }}</strong>
                                            <div class="text-muted small">
                                                Inscrit le {{ $user->created_at->format('d/m/Y') }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <form action="{{ route('admin.users.role', $user) }}" method="POST" class="d-inline">
                                        @csrf @method('PATCH')
                                        <select name="role_id" onchange="this.form.submit()"
                                            style="padding: 0.5rem; border-radius: 6px; border: 1px solid var(--border); background: var(--bg-card); color: var(--text-main);">
                                            @foreach($roles as $role)
                                                <option value="{{ $role->id }}" {{ $user->role_id == $role->id ? 'selected' : '' }}>
                                                    {{ $role->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </form>
                                </td>
                                <td>
                                    <span class="badge-enhanced {{ $user->status === 'active' ? 'success' : 'danger' }}">
                                        <i class='bx bx-{{ $user->status === 'active' ? 'check-circle' : 'x-circle' }}'></i>
                                        {{ strtoupper($user->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <form action="{{ route('admin.users.toggle', $user) }}" method="POST" class="d-inline">
                                            @csrf @method('PATCH')
                                            <button type="submit"
                                                class="btn btn-sm {{ $user->status === 'active' ? 'btn-danger' : 'btn-success' }}"
                                                title="{{ $user->status === 'active' ? 'Désactiver' : 'Activer' }}">
                                                <i class='bx bx-{{ $user->status === 'active' ? 'block' : 'user-check' }}'></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($users->count() == 0)
                <div class="no-data-base">
                    <i class='bx bx-user-x bx-lg text-muted mb-2'></i>
                    <p class="text-muted">Aucun utilisateur trouvé</p>
                </div>
            @endif
        </div>

        <!-- Section Ressources avec filtres -->
        <div class="section-base admin-section">
            <div class="section-header-base">
                <h2><i class='bx bx-server'></i> Gestion des Ressources</h2>
                <div class="d-flex align-center gap-2">
                    <span class="section-badge-base" id="resourceCount">{{ $resources->count() }} équipements</span>
                </div>
            </div>

            <!-- Filtres avec select -->
            <div class="filter-container-base">
                <div class="filter-row-base">
                    <div class="filter-group-base">
                        <label for="categoryFilter"><i class='bx bx-category'></i> Filtrer par catégorie</label>
                        <select id="categoryFilter" class="filter-select-base" onchange="applyFilters()">
                            <option value="all">Toutes les catégories</option>
                            @php
                                // Récupérer toutes les catégories
                                $categories = \App\Models\ResourceCategory::all();
                            @endphp
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="filter-group-base">
                        <label for="statusFilter"><i class='bx bx-check-circle'></i> Filtrer par statut</label>
                        <select id="statusFilter" class="filter-select-base" onchange="applyFilters()">
                            <option value="all">Tous les statuts</option>
                            <option value="available">Disponible</option>
                            <option value="occupied">Occupé</option>
                            <option value="maintenance">Maintenance</option>
                        </select>
                    </div>

                    <div class="filter-group-base">
                        <label for="sortFilter"><i class='bx bx-sort'></i> Trier par</label>
                        <select id="sortFilter" class="filter-select-base" onchange="sortResources()">
                            <option value="name_asc">Nom (A-Z)</option>
                            <option value="name_desc">Nom (Z-A)</option>
                            <option value="category_asc">Catégorie (A-Z)</option>
                            <option value="category_desc">Catégorie (Z-A)</option>
                            <option value="status_asc">Statut (A-Z)</option>
                            <option value="status_desc">Statut (Z-A)</option>
                        </select>
                    </div>

                    <div class="filter-actions-base">
                        <button class="filter-btn-base" onclick="resetFilters()">
                            <i class='bx bx-reset'></i> Réinitialiser
                        </button>
                        <button class="filter-btn-base primary" onclick="applyFilters()">
                            <i class='bx bx-filter-alt'></i> Appliquer les filtres
                        </button>
                    </div>
                </div>

                <!-- Statistiques de filtrage -->
                <div class="filter-stats-base" id="filterStats">
                    <div class="filter-stat-item-base">
                        <i class='bx bx-server'></i>
                        <span>Total: <strong id="totalCount">{{ $resources->count() }}</strong></span>
                    </div>
                    <div class="filter-stat-item-base">
                        <i class='bx bx-check-circle'></i>
                        <span>Disponible: <strong id="availableCount">{{ $availableCount ?? 0 }}</strong></span>
                    </div>
                    <div class="filter-stat-item-base">
                        <i class='bx bx-time-five'></i>
                        <span>Occupé: <strong id="occupiedCount">{{ $occupiedCount ?? 0 }}</strong></span>
                    </div>
                    <div class="filter-stat-item-base">
                        <i class='bx bx-wrench'></i>
                        <span>Maintenance: <strong id="maintenanceCount">{{ $maintenanceCount ?? 0 }}</strong></span>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table-base">
                    <thead>
                        <tr>
                            <th>Équipement</th>
                            <th>Catégorie</th>
                            <th>Spécifications</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="resourcesTableBody">
                        @php
                            // Compter les ressources par statut
                            $availableCount = 0;
                            $occupiedCount = 0;
                            $maintenanceCount = 0;
                        @endphp
                        @foreach($resources as $resource)
                            @php
                                // Compter par statut
                                if ($resource->status == 'available')
                                    $availableCount++;
                                if ($resource->status == 'occupied')
                                    $occupiedCount++;
                                if ($resource->status == 'maintenance')
                                    $maintenanceCount++;
                            @endphp
                            <tr class="resource-row" 
                                data-category="{{ $resource->resource_category_id }}"
                                data-status="{{ $resource->status }}" 
                                data-name="{{ strtolower($resource->name) }}"
                                data-category-name="{{ strtolower($resource->category->name ?? '') }}"
                                data-resource-id="{{ $resource->id }}"
                                data-resource-name="{{ $resource->name }}"
                                data-category-name-display="{{ $resource->category->name ?? 'Non catégorisé' }}">
                                <td>
                                    <strong>{{ $resource->name }}</strong>
                                    @if($resource->os)
                                        <div class="text-muted small">{{ $resource->os }}</div>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge-enhanced primary">
                                        {{ $resource->category->name ?? 'Non catégorisé' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="small">
                                        @if($resource->cpu)
                                        <div>CPU: {{ $resource->cpu }}</div>@endif
                                        @if($resource->ram)
                                        <div>RAM: {{ $resource->ram }}</div>@endif
                                        @if($resource->location)
                                        <div><i class='bx bx-map'></i> {{ $resource->location }}</div>@endif
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $statusInfo = [
                                            'available' => ['class' => 'success', 'icon' => 'check-circle', 'label' => 'Disponible'],
                                            'occupied' => ['class' => 'warning', 'icon' => 'time-five', 'label' => 'Occupé'],
                                            'maintenance' => ['class' => 'danger', 'icon' => 'wrench', 'label' => 'Maintenance']
                                        ];
                                        $info = $statusInfo[$resource->status] ?? $statusInfo['available'];
                                    @endphp
                                    <span class="badge-enhanced {{ $info['class'] }}">
                                        <i class='bx bx-{{ $info['icon'] }}'></i>
                                        {{ $info['label'] }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <form action="{{ route('admin.resources.maintenance', $resource) }}" method="POST"
                                            class="d-inline">
                                            @csrf @method('PATCH')
                                            <button type="submit"
                                                class="btn btn-sm {{ $resource->status === 'maintenance' ? 'btn-success' : 'btn-warning' }}"
                                                title="{{ $resource->status === 'maintenance' ? 'Remettre en ligne' : 'Mettre en maintenance' }}">
                                                <i class='bx bx-{{ $resource->status === 'maintenance' ? 'check-circle' : 'wrench' }}'></i>
                                            </button>
                                        </form>

                                        <button type="button" 
                                                class="btn btn-sm btn-danger btn-delete-table"
                                                onclick="confirmDeleteResource({{ $resource->id }}, '{{ addslashes($resource->name) }}', '{{ addslashes($resource->category->name ?? 'ressource') }}')"
                                                title="Supprimer">
                                            <i class='bx bx-trash'></i>
                                        </button>

                                        <a href="/admin/resources/{{ $resource->id }}/edit"
                                            class="btn btn-sm btn-primary"
                                            title="Modifier">
                                            <i class='bx bx-edit'></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Message quand aucun résultat -->
            <div id="noResults" class="no-data-base" style="display: none;">
                <i class='bx bx-search-alt bx-lg'></i>
                <h4>Aucune ressource trouvée</h4>
                <p class="text-muted">Essayez de modifier vos critères de filtrage</p>
                <button class="btn btn-primary mt-2" onclick="resetFilters()">
                    <i class='bx bx-reset'></i> Réinitialiser les filtres
                </button>
            </div>

            @if($resources->count() == 0)
                <div class="no-data-base">
                    <i class='bx bx-server bx-lg text-muted mb-2'></i>
                    <p class="text-muted">Aucune ressource trouvée</p>
                    <a href="/admin/resources/create" class="btn btn-primary">
                        <i class='bx bx-plus'></i> Ajouter une ressource
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Variables pour compter les ressources par statut
        let totalResources = {{ $resources->count() }};
        let availableResources = {{ $availableCount ?? 0 }};
        let occupiedResources = {{ $occupiedCount ?? 0 }};
        let maintenanceResources = {{ $maintenanceCount ?? 0 }};

        // Fonction pour confirmer la suppression d'une ressource
        function confirmDeleteResource(resourceId, resourceName, resourceType) {
            if (confirm(`ATTENTION : Êtes-vous sûr de vouloir supprimer la ${resourceType} "${resourceName}" ?\n\nCette action supprimera également :\n• Toutes les réservations associées\n• Les incidents signalés\n• L'historique d'utilisation\n\nCette action est IRREVERSIBLE.`)) {
                // Demander une confirmation supplémentaire
                if (confirm('DERNIÈRE CONFIRMATION : Tapez "SUPPRIMER" pour confirmer la suppression définitive.')) {
                    const confirmation = prompt('Tapez "SUPPRIMER" (en majuscules) pour confirmer :');
                    if (confirmation === 'SUPPRIMER') {
                        // Créer un formulaire de suppression dynamique
                        const deleteForm = document.createElement('form');
                        deleteForm.method = 'POST';
                        deleteForm.action = `/admin/resources/${resourceId}`;
                        deleteForm.style.display = 'none';
                        
                        // Ajouter les tokens CSRF et la méthode DELETE
                        const csrfToken = document.createElement('input');
                        csrfToken.type = 'hidden';
                        csrfToken.name = '_token';
                        csrfToken.value = '{{ csrf_token() }}';
                        
                        const methodInput = document.createElement('input');
                        methodInput.type = 'hidden';
                        methodInput.name = '_method';
                        methodInput.value = 'DELETE';
                        
                        deleteForm.appendChild(csrfToken);
                        deleteForm.appendChild(methodInput);
                        document.body.appendChild(deleteForm);
                        deleteForm.submit();
                    } else {
                        alert('Suppression annulée. Le texte ne correspond pas.');
                    }
                }
            }
        }

        // Appliquer les filtres
        function applyFilters() {
            const categoryFilter = document.getElementById('categoryFilter').value;
            const statusFilter = document.getElementById('statusFilter').value;
            const rows = document.querySelectorAll('.resource-row');

            let visibleCount = 0;
            let availableCount = 0;
            let occupiedCount = 0;
            let maintenanceCount = 0;

            rows.forEach(row => {
                const categoryMatch = categoryFilter === 'all' ||
                    row.getAttribute('data-category') == categoryFilter;
                const statusMatch = statusFilter === 'all' ||
                    row.getAttribute('data-status') === statusFilter;

                if (categoryMatch && statusMatch) {
                    row.style.display = 'table-row';
                    visibleCount++;

                    // Compter les statuts
                    const status = row.getAttribute('data-status');
                    if (status === 'available') availableCount++;
                    if (status === 'occupied') occupiedCount++;
                    if (status === 'maintenance') maintenanceCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            // Mettre à jour les compteurs
            document.getElementById('resourceCount').textContent = `${visibleCount} équipements`;
            document.getElementById('totalCount').textContent = visibleCount;
            document.getElementById('availableCount').textContent = availableCount;
            document.getElementById('occupiedCount').textContent = occupiedCount;
            document.getElementById('maintenanceCount').textContent = maintenanceCount;

            // Afficher/masquer le message "aucun résultat"
            const noResults = document.getElementById('noResults');
            if (visibleCount === 0 && rows.length > 0) {
                noResults.style.display = 'block';
            } else {
                noResults.style.display = 'none';
            }
        }

        // Trier les ressources
        function sortResources() {
            const sortValue = document.getElementById('sortFilter').value;
            const tbody = document.getElementById('resourcesTableBody');
            const rows = Array.from(tbody.querySelectorAll('.resource-row'));

            rows.sort((a, b) => {
                switch (sortValue) {
                    case 'name_asc':
                        return a.getAttribute('data-name').localeCompare(b.getAttribute('data-name'));
                    case 'name_desc':
                        return b.getAttribute('data-name').localeCompare(a.getAttribute('data-name'));
                    case 'category_asc':
                        return a.getAttribute('data-category-name').localeCompare(b.getAttribute('data-category-name'));
                    case 'category_desc':
                        return b.getAttribute('data-category-name').localeCompare(a.getAttribute('data-category-name'));
                    case 'status_asc':
                        return a.getAttribute('data-status').localeCompare(b.getAttribute('data-status'));
                    case 'status_desc':
                        return b.getAttribute('data-status').localeCompare(a.getAttribute('data-status'));
                    default:
                        return 0;
                }
            });

            // Réorganiser les lignes dans le tableau
            rows.forEach(row => tbody.appendChild(row));
        }

        // Réinitialiser tous les filtres
        function resetFilters() {
            document.getElementById('categoryFilter').value = 'all';
            document.getElementById('statusFilter').value = 'all';
            document.getElementById('sortFilter').value = 'name_asc';

            // Afficher toutes les ressources
            const rows = document.querySelectorAll('.resource-row');
            rows.forEach(row => row.style.display = 'table-row');

            // Mettre à jour les compteurs
            document.getElementById('resourceCount').textContent = `${totalResources} équipements`;
            document.getElementById('totalCount').textContent = totalResources;
            document.getElementById('availableCount').textContent = availableResources;
            document.getElementById('occupiedCount').textContent = occupiedResources;
            document.getElementById('maintenanceCount').textContent = maintenanceResources;

            // Masquer le message "aucun résultat"
            document.getElementById('noResults').style.display = 'none';

            // Re-trier par défaut
            sortResources();
        }

        // Initialiser les filtres au chargement
        document.addEventListener('DOMContentLoaded', function () {
            // Initialiser les compteurs
            document.getElementById('availableCount').textContent = availableResources;
            document.getElementById('occupiedCount').textContent = occupiedResources;
            document.getElementById('maintenanceCount').textContent = maintenanceResources;

            // Appliquer le tri par défaut
            sortResources();
        });
    </script>
@endpush