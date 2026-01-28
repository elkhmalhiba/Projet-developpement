@extends('layouts.app')

@section('title', 'Historique IT | DataCenter Pro')

@push('styles')
<style>
    /* ============================================
       HISTORIQUE PAGE SPECIFIC STYLES
    ============================================ */

    /* Header personnalisé */
    .historique-header-base {
        background: linear-gradient(135deg, #06b6d4 0%, #8b5cf6 100%);
        color: white;
    }

    .historique-header-base h1 {
        color: white;
    }

    .historique-header-base p {
        color: rgba(255, 255, 255, 0.9);
    }

    /* Icônes de statistiques spécifiques */
    .stat-icon-historique.total { 
        background: linear-gradient(135deg, var(--primary), #8b5cf6); 
    }
    .stat-icon-historique.validated { 
        background: linear-gradient(135deg, var(--success), #22c55e); 
    }
    .stat-icon-historique.rejected { 
        background: linear-gradient(135deg, var(--danger), #dc2626); 
    }
    .stat-icon-historique.hours { 
        background: linear-gradient(135deg, var(--warning), #f59e0b); 
    }

    /* Badges d'historique spécifiques */
    .badge-historique.validee {
        background: rgba(34, 197, 94, 0.1);
        color: #22c55e;
        border: 1px solid rgba(34, 197, 94, 0.2);
    }

    .badge-historique.en-attente {
        background: rgba(245, 158, 11, 0.1);
        color: #f59e0b;
        border: 1px solid rgba(245, 158, 11, 0.2);
    }

    .badge-historique.refusee {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
        border: 1px solid rgba(239, 68, 68, 0.2);
    }

    /* Icône de ressource */
    .resource-icon-historique {
        background: rgba(56, 189, 248, 0.1);
        color: var(--primary);
    }

    /* Boutons d'action spécifiques */
    .btn-action-historique.delete {
        background: rgba(239, 68, 68, 0.1);
        color: var(--danger);
        border: 1px solid rgba(239, 68, 68, 0.2);
    }

    .btn-action-historique.delete:hover {
        background: var(--danger);
        color: white;
        transform: scale(1.1);
    }

    .btn-action-historique.view {
        background: rgba(56, 189, 248, 0.1);
        color: var(--primary);
        border: 1px solid rgba(56, 189, 248, 0.2);
    }

    .btn-action-historique.view:hover {
        background: var(--primary);
        color: white;
        transform: scale(1.1);
    }

    /* Badge d'en-tête */
    .historique-badge {
        background: rgba(255, 255, 255, 0.1);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    /* Animation pour les lignes du tableau */
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(-10px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .historique-table tbody tr {
        animation: slideIn 0.3s ease forwards;
        opacity: 0;
    }

    /* Délais d'animation pour chaque ligne */
    .historique-table tbody tr:nth-child(1) { animation-delay: 0.1s; }
    .historique-table tbody tr:nth-child(2) { animation-delay: 0.2s; }
    .historique-table tbody tr:nth-child(3) { animation-delay: 0.3s; }
    .historique-table tbody tr:nth-child(4) { animation-delay: 0.4s; }
    .historique-table tbody tr:nth-child(5) { animation-delay: 0.5s; }
    .historique-table tbody tr:nth-child(6) { animation-delay: 0.6s; }
    .historique-table tbody tr:nth-child(7) { animation-delay: 0.7s; }
    .historique-table tbody tr:nth-child(8) { animation-delay: 0.8s; }
    .historique-table tbody tr:nth-child(9) { animation-delay: 0.9s; }
    .historique-table tbody tr:nth-child(10) { animation-delay: 1s; }

    /* Style pour les dates */
    .date-display-base {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .date-main-base {
        font-weight: 600;
        color: var(--text-main);
    }

    .date-range-base {
        color: var(--text-muted);
        font-size: 0.85rem;
    }

    /* Informations de ressource */
    .resource-info-base {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .resource-details-base h4 {
        font-weight: 600;
        margin-bottom: 0.25rem;
        color: var(--text-main);
    }

    .category-base {
        color: var(--text-muted);
        font-size: 0.85rem;
    }
</style>
@endpush

@section('content')
<div class="container">
    <!-- Header Section -->
    <div class="page-header-base historique-header-base">
        <h1><i class='bx bx-history'></i> Archives & Historique</h1>
        <p class="page-header-subtitle">Consultez l'historique complet de vos réservations et interactions DataCenter</p>
    </div>

    <!-- Statistiques rapides -->
    <div class="stats-grid">
        <div class="stat-card-base">
            <div class="stat-icon-base stat-icon-historique total">
                <i class='bx bx-calendar'></i>
            </div>
            <div class="stat-number-base">{{ $reservations->count() }}</div>
            <div class="stat-label-base">Réservations totales</div>
            <div class="text-muted small mt-2">
                {{ now()->format('d/m/Y') }}
            </div>
        </div>

        <div class="stat-card-base">
            <div class="stat-icon-base stat-icon-historique validated">
                <i class='bx bx-check-circle'></i>
            </div>
            <div class="stat-number-base">{{ $validatedCount ?? 0 }}</div>
            <div class="stat-label-base">Validées</div>
            <div class="text-muted small mt-2">
                Réservations acceptées
            </div>
        </div>

        <div class="stat-card-base">
            <div class="stat-icon-base stat-icon-historique rejected">
                <i class='bx bx-x-circle'></i>
            </div>
            <div class="stat-number-base">{{ $rejectedCount ?? 0 }}</div>
            <div class="stat-label-base">Refusées</div>
            <div class="text-muted small mt-2">
                Réservations rejetées
            </div>
        </div>

        <div class="stat-card-base">
            <div class="stat-icon-base stat-icon-historique hours">
                <i class='bx bx-time'></i>
            </div>
            <div class="stat-number-base">{{ $totalHours ?? 0 }}h</div>
            <div class="stat-label-base">Heures totales</div>
            <div class="text-muted small mt-2">
                Temps d'utilisation
            </div>
        </div>
    </div>

    <!-- Tableau principal -->
    <div class="section-base">
        <div class="section-header-base">
            <h2><i class='bx bx-time-five'></i> Journal des Réservations</h2>
            <span class="section-badge-base historique-badge" id="reservationCount">
                {{ $reservations->count() }} réservations
            </span>
        </div>

        <div class="table-responsive">
            <table class="table-base">
                <thead>
                    <tr>
                        <th>Équipement</th>
                        <th>Période</th>
                        <th>Statut</th>
                        <th>Demandée le</th>
                        <th style="text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody id="reservationsTableBody">
                    @forelse($reservations as $res)
                    <tr class="reservation-row" 
                        data-status="{{ strtolower(str_replace(' ', '-', $res->status)) }}"
                        data-category="{{ $res->resource->resource_category_id }}"
                        data-date="{{ $res->created_at->format('Y-m-d') }}"
                        data-resource="{{ strtolower($res->resource->name) }}">
                        <td>
                            <div class="resource-info-base">
                                <div class="resource-icon-historique" style="width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
                                    <i class='bx bx-hdd'></i>
                                </div>
                                <div class="resource-details-base">
                                    <h4>{{ $res->resource->name }}</h4>
                                    <span class="category-base">{{ $res->resource->category->name ?? 'Non catégorisé' }}</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="date-display-base">
                                <span class="date-main-base">
                                    {{ \Carbon\Carbon::parse($res->start_date)->format('d M Y') }}
                                </span>
                                <span class="date-range-base">
                                    <i class='bx bx-time'></i>
                                    {{ \Carbon\Carbon::parse($res->start_date)->format('H:i') }} - 
                                    {{ \Carbon\Carbon::parse($res->end_date)->format('H:i') }}
                                </span>
                            </div>
                        </td>
                        <td>
                            @php
                                $badgeClass = match($res->status) {
                                    'VALIDÉE' => 'validee',
                                    'EN ATTENTE' => 'en-attente',
                                    'REFUSÉE' => 'refusee',
                                    default => ''
                                };
                                $icon = match($res->status) {
                                    'VALIDÉE' => 'bx-check-double',
                                    'EN ATTENTE' => 'bx-loader-circle',
                                    'REFUSÉE' => 'bx-error-circle',
                                    default => 'bx-loader-circle'
                                };
                            @endphp
                            <span class="badge-historique {{ $badgeClass }}">
                                <i class='bx {{ $icon }}'></i> {{ $res->status }}
                            </span>
                        </td>
                        <td>
                            {{ $res->created_at->format('d/m/Y H:i') }}
                            <div class="text-muted small">
                                Il y a {{ $res->created_at->diffForHumans() }}
                            </div>
                        </td>
                        <td>
                            <div class="filter-actions-base">
                                <form action="{{ route('reservations.destroy', $res->id) }}" 
                                      method="POST" 
                                      class="d-inline"
                                      onsubmit="return confirm('Voulez-vous vraiment supprimer cette archive ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="btn-action-base btn-action-historique delete"
                                            title="Supprimer l'archive">
                                        <i class='bx bx-trash-alt'></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5">
                            <div class="no-data-base">
                                <div class="no-data-icon">
                                    <i class='bx bx-folder-open'></i>
                                </div>
                                <h4>Aucune archive trouvée</h4>
                                <p>Vous n'avez pas encore effectué de réservation. Commencez par réserver votre première ressource.</p>
                                <a href="{{ route('resources.index') }}" class="btn btn-primary">
                                    <i class='bx bx-search-alt'></i> Explorer les ressources
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($reservations->hasPages())
        <div class="historique-pagination">
            <div class="pagination-info">
                Affichage de {{ $reservations->firstItem() }} à {{ $reservations->lastItem() }} sur {{ $reservations->total() }} résultats
            </div>
            <div>
                {{ $reservations->links() }}
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Variables globales
    let allReservations = @json($reservations->items());
    let totalReservations = {{ $reservations->count() }};
    let validatedCount = {{ $validatedCount ?? 0 }};
    let rejectedCount = {{ $rejectedCount ?? 0 }};

    // Appliquer les filtres
    function applyFilters() {
        const statusFilter = document.getElementById('statusFilter').value;
        const periodFilter = document.getElementById('periodFilter').value;
        const sortFilter = document.getElementById('sortFilter').value;
        const rows = document.querySelectorAll('.reservation-row');

        let visibleCount = 0;

        rows.forEach(row => {
            const statusMatch = statusFilter === 'all' || 
                row.getAttribute('data-status') === statusFilter;
            
            const dateMatch = periodFilter === 'all' || 
                checkDatePeriod(row.getAttribute('data-date'), periodFilter);

            if (statusMatch && dateMatch) {
                row.style.display = 'table-row';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        // Mettre à jour le compteur
        document.getElementById('reservationCount').textContent = `${visibleCount} réservations`;

        // Trier les résultats
        sortReservations(sortFilter);

        // Afficher/masquer le message "aucun résultat"
        if (visibleCount === 0 && rows.length > 0) {
            showNoResults();
        }
    }

    // Trier les réservations
    function sortReservations(sortValue) {
        const tbody = document.getElementById('reservationsTableBody');
        const rows = Array.from(tbody.querySelectorAll('.reservation-row:not([style*="display: none"])'));

        rows.sort((a, b) => {
            switch (sortValue) {
                case 'newest':
                    return new Date(b.getAttribute('data-date')) - new Date(a.getAttribute('data-date'));
                case 'oldest':
                    return new Date(a.getAttribute('data-date')) - new Date(b.getAttribute('data-date'));
                case 'resource':
                    return a.getAttribute('data-resource').localeCompare(b.getAttribute('data-resource'));
                case 'status':
                    return a.getAttribute('data-status').localeCompare(b.getAttribute('data-status'));
                default:
                    return 0;
            }
        });

        // Réorganiser les lignes
        rows.forEach(row => tbody.appendChild(row));
    }

    // Vérifier si une date correspond à la période
    function checkDatePeriod(dateString, period) {
        const date = new Date(dateString);
        const now = new Date();
        
        switch(period) {
            case 'today':
                return date.toDateString() === now.toDateString();
            case 'week':
                const weekStart = new Date(now);
                weekStart.setDate(now.getDate() - now.getDay());
                return date >= weekStart;
            case 'month':
                return date.getMonth() === now.getMonth() && 
                       date.getFullYear() === now.getFullYear();
            case 'year':
                return date.getFullYear() === now.getFullYear();
            default:
                return true;
        }
    }

    // Réinitialiser les filtres
    function resetFilters() {
        document.getElementById('statusFilter').value = 'all';
        document.getElementById('periodFilter').value = 'all';
        document.getElementById('sortFilter').value = 'newest';

        // Afficher toutes les réservations
        const rows = document.querySelectorAll('.reservation-row');
        rows.forEach(row => row.style.display = 'table-row');

        // Mettre à jour le compteur
        document.getElementById('reservationCount').textContent = `${totalReservations} réservations`;

        // Re-trier par défaut
        sortReservations('newest');
    }

    // Afficher le message "aucun résultat"
    function showNoResults() {
        const tbody = document.getElementById('reservationsTableBody');
        if (!document.getElementById('noResultsRow')) {
            const noResultsRow = document.createElement('tr');
            noResultsRow.id = 'noResultsRow';
            noResultsRow.innerHTML = `
                <td colspan="5">
                    <div class="no-data-base">
                        <i class='bx bx-search-alt bx-lg'></i>
                        <h4>Aucun résultat trouvé</h4>
                        <p class="text-muted">Essayez de modifier vos critères de filtrage</p>
                        <button class="btn btn-primary mt-2" onclick="resetFilters()">
                            <i class='bx bx-reset'></i> Réinitialiser les filtres
                        </button>
                    </div>
                </td>
            `;
            tbody.appendChild(noResultsRow);
        }
    }

    // Initialiser les filtres au chargement
    document.addEventListener('DOMContentLoaded', function() {
        // Si des paramètres de filtrage sont dans l'URL, les appliquer
        const urlParams = new URLSearchParams(window.location.search);
        const status = urlParams.get('status');
        const period = urlParams.get('period');
        
        if (status) {
            document.getElementById('statusFilter').value = status;
        }
        if (period) {
            document.getElementById('periodFilter').value = period;
        }
        
        // Appliquer les filtres si au moins un est défini
        if (status || period) {
            applyFilters();
        }
    });
</script>
@endpush