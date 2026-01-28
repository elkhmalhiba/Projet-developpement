@extends('layouts.app')

@section('title', 'Dashboard Responsable Technique')

@push('styles')
<style>
    /* ============================================
       TECH DASHBOARD SPECIFIC STYLES
    ============================================ */
    
    /* Cartes de statistiques tech */
    .tech-stat-card.pending::before { background: var(--warning); }
    .tech-stat-card.incidents::before { background: var(--danger); }
    .tech-stat-card.resources::before { background: var(--success); }
    .tech-stat-card.maintenance::before { background: #06b6d4; }

    .stat-icon-wrapper.pending { background: rgba(245, 158, 11, 0.1); color: var(--warning); }
    .stat-icon-wrapper.incidents { background: rgba(239, 68, 68, 0.1); color: var(--danger); }
    .stat-icon-wrapper.resources { background: rgba(16, 185, 129, 0.1); color: var(--success); }
    .stat-icon-wrapper.maintenance { background: rgba(6, 182, 212, 0.1); color: #06b6d4; }

    /* Badges tech spécifiques */
    .badge-tech.primary { background: rgba(14, 165, 233, 0.1); color: #0ea5e9; }
    .badge-tech.warning { background: rgba(245, 158, 11, 0.1); color: var(--warning); }
    .badge-tech.danger { background: rgba(239, 68, 68, 0.1); color: var(--danger); }
    .badge-tech.success { background: rgba(16, 185, 129, 0.1); color: var(--success); }

    /* Boutons d'action tech */
    .btn-action-tech.success { background: var(--success); color: white; }
    .btn-action-tech.success:hover { background: #0da271; transform: translateY(-2px); }

    .btn-action-tech.danger { background: var(--danger); color: white; }
    .btn-action-tech.danger:hover { background: #dc2626; transform: translateY(-2px); }

    .btn-action-tech.warning { background: var(--warning); color: white; }
    .btn-action-tech.warning:hover { background: #d97706; transform: translateY(-2px); }
</style>
@endpush

@section('content')
<div class="container">
    <!-- En-tête tech -->
    <div class="page-header-base" style="background: linear-gradient(135deg, #0ea5e9 0%, #8b5cf6 100%);">
        <h1><i class='bx bx-wrench'></i> Dashboard Responsable Technique</h1>
        <p class="page-header-subtitle">
            Gestion des réservations, incidents et équipements
            <span class="badge-enhanced primary ms-2">
                <i class='bx bx-cog'></i> Responsable Technique
            </span>
        </p>
    </div>

    <!-- Actions rapides -->
    <div class="quick-actions-base">
        <a href="{{ route('resources.index') }}" class="btn btn-success">
            <i class='bx bx-server'></i> Voir toutes les ressources
        </a>
        <a href="{{ route('logout') }}" class="btn btn-danger" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class='bx bx-log-out'></i> Déconnexion
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    </div>

    <!-- Cartes de statistiques -->
    <div class="stats-grid">
        <div class="stat-card-base tech-stat-card pending">
            <div class="stat-icon-base stat-icon-wrapper pending">
                <i class='bx bx-time-five'></i>
            </div>
            <div class="stat-number-base">{{ $stats['pending_reservations'] ?? 0 }}</div>
            <div class="stat-label-base">Réservations en attente</div>
            <div class="text-muted small mt-2">
                <a href="#reservations-section" class="text-warning">Voir en détail</a>
            </div>
        </div>

        <div class="stat-card-base tech-stat-card incidents">
            <div class="stat-icon-base stat-icon-wrapper incidents">
                <i class='bx bx-error-circle'></i>
            </div>
            <div class="stat-number-base">{{ $stats['open_incidents'] ?? 0 }}</div>
            <div class="stat-label-base">Incidents ouverts</div>
            <div class="text-muted small mt-2">
                <a href="#incidents-section" class="text-danger">Intervenir</a>
            </div>
        </div>

        <div class="stat-card-base tech-stat-card resources">
            <div class="stat-icon-base stat-icon-wrapper resources">
                <i class='bx bx-server'></i>
            </div>
            <div class="stat-number-base">{{ $stats['total_resources'] ?? 0 }}</div>
            <div class="stat-label-base">Ressources IT</div>
            <div class="text-muted small mt-2">
                {{ $stats['available_resources'] ?? 0 }} disponibles
            </div>
        </div>

        <div class="stat-card-base tech-stat-card maintenance">
            <div class="stat-icon-base stat-icon-wrapper maintenance">
                <i class='bx bx-wrench'></i>
            </div>
            <div class="stat-number-base">{{ $stats['maintenance_resources'] ?? 0 }}</div>
            <div class="stat-label-base">En maintenance</div>
            <div class="text-muted small mt-2">
                <a href="#resources-section" class="text-info">Vérifier</a>
            </div>
        </div>
    </div>

    <!-- Section Réservations en attente -->
    <div class="section-base" id="reservations-section">
        <div class="section-header-base">
            <h2><i class='bx bx-time-five'></i> Réservations en attente de validation</h2>
            <span class="section-badge-base">{{ $pendingReservations->count() }} en attente</span>
        </div>
        
        <div class="table-responsive">
            <table class="table-base">
                <thead>
                    <tr>
                        <th>Utilisateur</th>
                        <th>Ressource</th>
                        <th>Période</th>
                        <th>Demandée le</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendingReservations as $reservation)
                    <tr>
                        <td>
                            <div class="d-flex align-center gap-2">
                                <div style="width: 40px; height: 40px; border-radius: 50%; background: #0ea5e9; color: white; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 1rem;">
                                    {{ strtoupper(substr($reservation->user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <strong>{{ $reservation->user->name }}</strong>
                                    <div class="text-muted small">{{ $reservation->user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <strong>{{ $reservation->resource->name }}</strong>
                            <div class="text-muted small">{{ $reservation->resource->category->name }}</div>
                        </td>
                        <td>
                            <div class="small">
                                Début: <strong>{{ $reservation->start_date->format('d/m H:i') }}</strong><br>
                                Fin: <strong>{{ $reservation->end_date->format('d/m H:i') }}</strong>
                            </div>
                        </td>
                        <td>{{ $reservation->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <div class="filter-actions-base">
                                <form action="{{ route('reservations.update', $reservation) }}" method="POST" class="d-inline">
                                    @csrf @method('PUT')
                                    <input type="hidden" name="status" value="VALIDÉE">
                                    <button type="submit" class="btn-action-base btn-action-tech success" onclick="return confirm('Accepter cette réservation ?')">
                                        <i class='bx bx-check'></i> Accepter
                                    </button>
                                </form>
                                
                                <form action="{{ route('reservations.update', $reservation) }}" method="POST" class="d-inline">
                                    @csrf @method('PUT')
                                    <input type="hidden" name="status" value="REFUSÉE">
                                    <button type="submit" class="btn-action-base btn-action-tech danger" onclick="return confirm('Refuser cette réservation ?')">
                                        <i class='bx bx-x'></i> Refuser
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        @if($pendingReservations->count() == 0)
        <div class="no-data-base">
            <i class='bx bx-check-circle bx-lg'></i>
            <h4>Aucune réservation en attente</h4>
            <p class="text-muted">Toutes les réservations ont été traitées</p>
        </div>
        @endif
    </div>

    <!-- Section Incidents ouverts -->
    <div class="section-base" id="incidents-section">
        <div class="section-header-base">
            <h2><i class='bx bx-error-circle'></i> Incidents en attente d'intervention</h2>
            <span class="section-badge-base">{{ $openIncidents->count() }} ouverts</span>
            <a href="{{ route('tech.incidents.index') }}" class="btn btn-primary">Ouvrir</a>
        </div>
        
        <div class="table-responsive">
            <table class="table-base">
                <thead>
                    <tr>
                        <th>Signalé par</th>
                        <th>Ressource</th>
                        <th>Description</th>
                        <th>Signalé le</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($openIncidents as $incident)
                    <tr>
                        <td>
                            <div class="d-flex align-center gap-2">
                                <div style="width: 40px; height: 40px; border-radius: 50%; background: var(--danger); color: white; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 1rem;">
                                    {{ strtoupper(substr($incident->user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <strong>{{ $incident->user->name }}</strong>
                                    <div class="text-muted small">{{ $incident->user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <strong>{{ $incident->resource->name }}</strong>
                            <div class="text-muted small">{{ $incident->resource->category->name }}</div>
                        </td>
                        <td>
                            <div class="small text-truncate" style="max-width: 300px;" 
                                 title="{{ $incident->description }}">
                                {{ Str::limit($incident->description, 100) }}
                            </div>
                        </td>
                        <td>{{ $incident->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <span class="badge-enhanced danger">
                                <i class='bx bx-error'></i>
                                {{ ucfirst($incident->status) }}
                            </span>
                        </td>
                        <td>
                            <div class="filter-actions-base">
                                <form action="{{ route('tech.incidents.destroy', $incident) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-action-base btn-action-tech warning" onclick="return confirm('Marquer cet incident comme résolu ?')">
                                        <i class='bx bx-check'></i> Résolu
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        @if($openIncidents->count() == 0)
        <div class="no-data-base">
            <i class='bx bx-check-shield bx-lg'></i>
            <h4>Aucun incident ouvert</h4>
            <p class="text-muted">Tous les incidents ont été résolus</p>
        </div>
        @endif
    </div>

    <!-- Section Ressources nécessitant attention -->
    <div class="section-base" id="resources-section">
        <div class="section-header-base">
            <h2><i class='bx bx-alarm'></i> Ressources nécessitant attention</h2>
            <span class="section-badge-base">{{ $attentionResources->count() }} ressources</span>
        </div>
        
        <div class="table-responsive">
            <table class="table-base">
                <thead>
                    <tr>
                        <th>Équipement</th>
                        <th>Catégorie</th>
                        <th>Spécifications</th>
                        <th>Statut</th>
                        <th>Localisation</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($attentionResources as $resource)
                    <tr>
                        <td>
                            <strong>{{ $resource->name }}</strong>
                            @if($resource->os)
                            <div class="text-muted small">{{ $resource->os }}</div>
                            @endif
                        </td>
                        <td>
                            <span class="badge-enhanced primary">
                                {{ $resource->category->name }}
                            </span>
                        </td>
                        <td>
                            <div class="small">
                                @if($resource->cpu)<div>CPU: {{ $resource->cpu }}</div>@endif
                                @if($resource->ram)<div>RAM: {{ $resource->ram }}</div>@endif
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
                            @if($resource->location)
                            <div class="text-muted small">
                                <i class='bx bx-map'></i> {{ $resource->location }}
                            </div>
                            @else
                            <span class="text-muted">Non spécifiée</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        @if($attentionResources->count() == 0)
        <div class="no-data-base">
            <i class='bx bx-check-circle bx-lg'></i>
            <h4>Toutes les ressources sont opérationnelles</h4>
            <p class="text-muted">Aucune intervention nécessaire pour le moment</p>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Confirmation pour les actions
    document.addEventListener('DOMContentLoaded', function() {
        // Confirmation pour les actions de réservation
        const reservationForms = document.querySelectorAll('form[action*="reservations"]');
        reservationForms.forEach(form => {
            const button = form.querySelector('button[type="submit"]');
            if (button) {
                button.addEventListener('click', function(e) {
                    const action = this.classList.contains('success') ? 'accepter' : 'refuser';
                    if (!confirm(`Êtes-vous sûr de vouloir ${action} cette réservation ?`)) {
                        e.preventDefault();
                    }
                });
            }
        });
        
        // Confirmation pour les incidents
        const incidentForms = document.querySelectorAll('form[action*="incidents"]');
        incidentForms.forEach(form => {
            const button = form.querySelector('button[type="submit"]');
            if (button) {
                button.addEventListener('click', function(e) {
                    if (!confirm('Marquer cet incident comme résolu ?')) {
                        e.preventDefault();
                    }
                });
            }
        });
    });
</script>
@endpush