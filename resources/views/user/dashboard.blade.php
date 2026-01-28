@extends('layouts.app')

@section('title', 'Mon Espace | DataCenter Pro')

@push('styles')
    <style>
        /* ============================================
           USER DASHBOARD SPECIFIC STYLES
        ============================================ */

        /* Cartes de statistiques user */
        .user-stat-card.total::before {
            background: var(--primary);
        }

        .user-stat-card.active::before {
            background: var(--success);
        }

        .user-stat-card.pending::before {
            background: var(--warning);
        }

        .user-stat-card.rejected::before {
            background: var(--danger);
        }

        .stat-icon-wrapper.total {
            background: rgba(59, 130, 246, 0.1);
            color: var(--primary);
        }

        .stat-icon-wrapper.active {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success);
        }

        .stat-icon-wrapper.pending {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning);
        }

        .stat-icon-wrapper.rejected {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger);
        }

        /* Badges de réservation */
        .badge-validated {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success);
        }

        .badge-pending {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning);
        }

        .badge-rejected {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger);
        }

        .badge-finished {
            background: rgba(100, 116, 139, 0.1);
            color: #64748b;
        }

        /* Boutons d'action user */
        .btn-action-user.primary {
            background: var(--primary);
            color: white;
        }

        .btn-action-user.primary:hover {
            background: #2563eb;
        }

        .btn-action-user.secondary {
            background: var(--border);
            color: var(--text-main);
        }

        .btn-action-user.secondary:hover {
            background: rgba(0, 0, 0, 0.1);
        }

        .btn-action-user.danger {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger);
        }

        .btn-action-user.danger:hover {
            background: rgba(239, 68, 68, 0.2);
        }

        /* Icônes de ressources */
        .resource-icon-base {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            background: rgba(59, 130, 246, 0.1);
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        /* En-tête de carte de réservation */
        .reservation-header-base {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .reservation-title-base {
            font-weight: 700;
            font-size: 1.1rem;
            margin-bottom: 0.25rem;
        }

        .reservation-subtitle-base {
            color: var(--text-muted);
            font-size: 0.85rem;
        }

        /* Corps de carte de réservation */
        .reservation-body-base {
            padding: 1.25rem 1.5rem;
        }

        .reservation-info-base {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }

        .info-item-base {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.9rem;
        }

        .info-item-base i {
            color: var(--primary);
            width: 20px;
            text-align: center;
        }

        /* Actions de réservation */
        .reservation-actions-base {
            display: flex;
            gap: 0.75rem;
            padding-top: 1rem;
            border-top: 1px dashed var(--border);
        }

        /* Titre de ressource */
        .resource-title-base {
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        /* Spécifications de ressource */
        .resource-specs-base {
            color: var(--text-muted);
            font-size: 0.85rem;
            margin-bottom: 1rem;
        }

        /* Détails de réservation */
        .detail-section-title-base {
            font-size: 1.1rem;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--border);
            color: var(--text-main);
        }

        .detail-grid-base {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }

        .detail-item-base {
            background: rgba(0, 0, 0, 0.02);
            border-radius: 8px;
            padding: 1rem;
            border: 1px solid var(--border);
        }

        .detail-label-base {
            font-size: 0.85rem;
            color: var(--text-muted);
            margin-bottom: 0.5rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .detail-value-base {
            font-size: 1rem;
            color: var(--text-main);
            font-weight: 500;
        }

        .detail-value-base i {
            color: var(--primary);
            margin-right: 0.5rem;
        }

        /* Carte d'info de ressource */
        .resource-info-card-base {
            background: var(--bg-body);
            border-radius: 8px;
            padding: 1rem;
            border: 1px solid var(--border);
        }

        /* Loading spinner */
        .loading-spinner-base {
            text-align: center;
            padding: 2rem;
            color: var(--text-muted);
        }

        .loading-spinner-base i {
            font-size: 2rem;
            margin-bottom: 1rem;
        }

        /* Message d'erreur */
        .error-message-base {
            text-align: center;
            padding: 2rem;
        }

        .error-message-base i {
            font-size: 3rem;
            color: var(--danger);
            margin-bottom: 1rem;
        }
    </style>
@endpush

@section('content')
    <div class="container">
        <!-- User Header -->
        <div class="page-header-base" style="background: linear-gradient(135deg, var(--primary) 0%, #8b5cf6 100%);">
            <h1><i class='bx bx-user-circle'></i> Bonjour, {{ Auth::user()->name }}</h1>
            <p class="page-header-subtitle">Gérez vos réservations, ressources et incidents techniques</p>
            <div style="position: absolute; bottom: 1rem; right: 1.5rem; z-index: 2;">
                <span class="badge" style="background: rgba(255,255,255,0.2); color: white; font-size: 0.8rem;">
                    <i class='bx bx-check-shield'></i> Utilisateur Interne
                </span>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="quick-actions-base">
           
            <a href="{{ route('resources.index') }}" class="btn btn-success">
                <i class='bx bx-search-alt'></i> Explorer les ressources
            </a>
            <a href="{{ route('user.historique') }}" class="btn btn-secondary">
                <i class='bx bx-history'></i> Voir l'historique
            </a>
            <a href="{{ route('welcome') }}" class="btn btn-primary">
                <i class='bx bx-home'></i> Retour à l'accueil
            </a>
        </div>

        <!-- Quick Stats -->
        <div class="stats-grid">
            <div class="stat-card-base user-stat-card total">
                <div class="stat-icon-base stat-icon-wrapper total">
                    <i class='bx bx-calendar'></i>
                </div>
                <div class="stat-number-base">{{ $stats['total'] ?? 0 }}</div>
                <div class="stat-label-base">Réservations totales</div>
                <div class="text-muted small mt-2">
                    Dernière: {{ $lastReservationDate ?? 'Jamais' }}
                </div>
            </div>

            <div class="stat-card-base user-stat-card active">
                <div class="stat-icon-base stat-icon-wrapper active">
                    <i class='bx bx-check-circle'></i>
                </div>
                <div class="stat-number-base">{{ $stats['validated'] ?? 0 }}</div>
                <div class="stat-label-base">Validées</div>
                <div class="text-muted small mt-2">
                    Actuellement actives
                </div>
            </div>

            <div class="stat-card-base user-stat-card pending">
                <div class="stat-icon-base stat-icon-wrapper pending">
                    <i class='bx bx-time-five'></i>
                </div>
                <div class="stat-number-base">{{ $stats['pending'] ?? 0 }}</div>
                <div class="stat-label-base">En attente</div>
                <div class="text-muted small mt-2">
                    En cours de validation
                </div>
            </div>

            <div class="stat-card-base user-stat-card rejected">
                <div class="stat-icon-base stat-icon-wrapper rejected">
                    <i class='bx bx-x-circle'></i>
                </div>
                <div class="stat-number-base">{{ $stats['rejected'] ?? 0 }}</div>
                <div class="stat-label-base">Refusées</div>
                <div class="text-muted small mt-2">
                    Peut être modifié
                </div>
            </div>
        </div>

        <!-- Mes réservations actives -->
        <div class="section-base">
            <div class="section-header-base">
                <h2><i class='bx bx-calendar-check'></i> Mes réservations actives</h2>
                <span class="section-badge-base">{{ $reservations->whereIn('status', ['VALIDÉE', 'EN ATTENTE'])->count() }}
                    active(s)</span>
            </div>

            @if($reservations->whereIn('status', ['VALIDÉE', 'EN ATTENTE'])->count() > 0)
                <div class="reservations-grid-base">
                    @foreach($reservations->whereIn('status', ['VALIDÉE', 'EN ATTENTE']) as $reservation)
                        <div class="reservation-card-base">
                            <div class="reservation-header-base">
                                <div>
                                    <div class="reservation-title-base">{{ $reservation->resource->name }}</div>
                                    <div class="reservation-subtitle-base">
                                        <i class='bx bx-category'></i>
                                        {{ $reservation->resource->category->name ?? 'Non catégorisé' }}
                                    </div>
                                </div>
                                <div>
                                    @php
                                        $badgeClass = match ($reservation->status) {
                                            'VALIDÉE' => 'badge-validated',
                                            'EN ATTENTE' => 'badge-pending',
                                            'REFUSÉE' => 'badge-rejected',
                                            'TERMINÉE' => 'badge-finished',
                                            default => ''
                                        };
                                    @endphp
                                    <span class="reservation-badge {{ $badgeClass }}">
                                        {{ $reservation->status }}
                                    </span>
                                </div>
                            </div>

                            <div class="reservation-body-base">
                                <div class="reservation-info-base">
                                    <div class="info-item-base">
                                        <i class='bx bx-calendar'></i>
                                        <span>Début:
                                            <strong>{{ \Carbon\Carbon::parse($reservation->start_date)->format('d/m/Y H:i') }}</strong></span>
                                    </div>
                                    <div class="info-item-base">
                                        <i class='bx bx-calendar'></i>
                                        <span>Fin:
                                            <strong>{{ \Carbon\Carbon::parse($reservation->end_date)->format('d/m/Y H:i') }}</strong></span>
                                    </div>
                                    <div class="info-item-base">
                                        <i class='bx bx-time'></i>
                                        <span>Durée:
                                            <strong>{{ \Carbon\Carbon::parse($reservation->start_date)->diffForHumans(\Carbon\Carbon::parse($reservation->end_date), true) }}</strong></span>
                                    </div>
                                    @if($reservation->resource->location)
                                        <div class="info-item-base">
                                            <i class='bx bx-map'></i>
                                            <span>Localisation: {{ $reservation->resource->location }}</span>
                                        </div>
                                    @endif
                                </div>

                                <div class="reservation-actions-base">
                                    @if($reservation->status == 'VALIDÉE')
                                        <a href="{{ route('incidents.create', ['resource_id' => $reservation->resource_id]) }}"
                                            class="btn-action-base btn-action-user danger" title="Signaler un problème">
                                            <i class='bx bx-error-alt'></i> Incident
                                        </a>
                                        <button class="btn-action-base btn-action-user secondary"
                                            onclick="showReservationDetails({{ $reservation->id }})">
                                            <i class='bx bx-info-circle'></i> Détails
                                        </button>
                                    @elseif($reservation->status == 'EN ATTENTE')
                                        <form action="{{ route('reservations.destroy', $reservation) }}" method="POST" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn-action-base btn-action-user danger"
                                                onclick="return confirm('Annuler cette réservation ?')">
                                                <i class='bx bx-trash'></i> Annuler
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="no-data-base">
                    <i class='bx bx-calendar-x bx-lg'></i>
                    <h4>Aucune réservation active</h4>
                    <p class="text-muted">Créez votre première réservation pour commencer</p>
                </div>
            @endif
        </div>

        <!-- Section des notifications -->
        <div class="section-base">
            <div class="section-header-base">
                <h2><i class='bx bx-bell'></i> Notifications récentes</h2>
                @if($notifications->where('is_read', false)->count() > 0)
                    <span class="section-badge-base" style="background: var(--danger); color: var(--text-main);">
                        {{ $notifications->where('is_read', false)->count() }} non lue(s)
                    </span>
                @endif
            </div>

            @if($notifications->count() > 0)
                <div class="notifications-list-base">
                    @foreach($notifications as $notification)
                        <div class="notification-item-base {{ !$notification->is_read ? 'unread' : '' }}">
                            <div class="notification-title">
                                {{ $notification->title }}
                            </div>
                            <div class="notification-message">
                                {{ $notification->message }}
                            </div>
                            <div class="notification-time">
                                <i class='bx bx-time'></i>
                                {{ $notification->created_at->diffForHumans() }}
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="no-data-base">
                    <i class='bx bx-bell-off bx-lg'></i>
                    <h4>Aucune notification</h4>
                    <p class="text-muted">Vous serez notifié des mises à jour de vos réservations</p>
                </div>
            @endif
        </div>

        <!-- Ressources récemment utilisées -->
        @if($recentResources->count() > 0)
            <div class="section-base">
                <div class="section-header-base">
                    <h2><i class='bx bx-server'></i> Ressources récemment utilisées</h2>
                    <a href="{{ route('resources.index') }}" class="btn btn-sm btn-primary">
                        <i class='bx bx-search-alt'></i> Voir toutes
                    </a>
                </div>

                <div class="resources-grid-base">
                    @foreach($recentResources as $resource)
                        <div class="resource-card-base">
                            <div class="resource-icon-base">
                                <i class='bx bx-server'></i>
                            </div>
                            <h4 class="resource-title-base">{{ $resource->name }}</h4>
                            <div class="resource-specs-base">
                                @if($resource->cpu)
                                    <div><i class='bx bx-microchip'></i> {{ $resource->cpu }}</div>
                                @endif
                                @if($resource->ram)
                                    <div><i class='bx bx-memory-card'></i> {{ $resource->ram }}</div>
                                @endif
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge {{ $resource->status == 'available' ? 'badge-success' : 'badge-warning' }}">
                                    {{ $resource->status == 'available' ? 'Disponible' : 'Occupé' }}
                                </span>
                                <a href="{{ route('reservations.create', ['resource_id' => $resource->id]) }}"
                                    class="btn btn-sm btn-primary">
                                    <i class='bx bx-calendar'></i> Réserver
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <!-- Modal pour les détails de réservation -->
    <div class="custom-modal-base" id="reservationModal" style="display: none;">
        <div class="custom-modal-overlay-base" onclick="closeReservationModal()"></div>
        <div class="custom-modal-content-base">
            <div class="custom-modal-header-base">
                <h3 class="custom-modal-title-base">
                    <i class='bx bx-calendar'></i> Détails de la réservation
                </h3>
                <button type="button" class="custom-modal-close-base" onclick="closeReservationModal()">
                    <i class='bx bx-x'></i>
                </button>
            </div>
            <div class="custom-modal-body-base" id="reservationDetails">
                <!-- Les détails seront chargés ici -->
                <div class="loading-spinner-base">
                    <i class='bx bx-loader-alt bx-spin'></i>
                    <p>Chargement des détails...</p>
                </div>
            </div>
            <div class="custom-modal-footer-base">
                <button type="button" class="btn btn-secondary" onclick="closeReservationModal()">
                    <i class='bx bx-x'></i> Fermer
                </button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Afficher les détails d'une réservation
        function showReservationDetails(reservationId) {
            // Afficher le modal
            const modal = document.getElementById('reservationModal');
            modal.style.display = 'flex';

            // Afficher le loader
            document.getElementById('reservationDetails').innerHTML = `
                <div class="loading-spinner-base">
                    <i class='bx bx-loader-alt bx-spin'></i>
                    <p>Chargement des détails...</p>
                </div>
            `;

            // Charger les données
            fetch(`/api/reservations/${reservationId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Erreur réseau');
                    }
                    return response.json();
                })
                .then(data => {
                    let statusBadge = '';
                    let statusColor = '';

                    switch (data.status) {
                        case 'VALIDÉE':
                            statusBadge = 'VALIDÉE';
                            statusColor = 'var(--success)';
                            break;
                        case 'EN ATTENTE':
                            statusBadge = 'EN ATTENTE';
                            statusColor = 'var(--warning)';
                            break;
                        case 'REFUSÉE':
                            statusBadge = 'REFUSÉE';
                            statusColor = 'var(--danger)';
                            break;
                        default:
                            statusBadge = data.status;
                            statusColor = 'var(--text-muted)';
                    }

                    // Formater les dates
                    const startDate = new Date(data.start_date);
                    const endDate = new Date(data.end_date);
                    const createdAt = new Date(data.created_at);

                    // Calculer la durée
                    const durationMs = endDate - startDate;
                    const durationHours = Math.floor(durationMs / (1000 * 60 * 60));
                    const durationMinutes = Math.floor((durationMs % (1000 * 60 * 60)) / (1000 * 60));

                    document.getElementById('reservationDetails').innerHTML = `
                        <div class="reservation-detail-section">
                            <h4 class="detail-section-title-base">Informations générales</h4>
                            <div class="detail-grid-base">
                                <div class="detail-item-base">
                                    <div class="detail-label-base">Statut</div>
                                    <div class="detail-value-base">
                                        <span class="reservation-badge" style="background: ${statusColor}20; color: ${statusColor}; border: 1px solid ${statusColor};">
                                            ${statusBadge}
                                        </span>
                                    </div>
                                </div>
                                <div class="detail-item-base">
                                    <div class="detail-label-base">Créée le</div>
                                    <div class="detail-value-base">
                                        <i class='bx bx-calendar'></i>
                                        ${createdAt.toLocaleDateString('fr-FR')} 
                                        <br>
                                        <i class='bx bx-time'></i>
                                        ${createdAt.toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' })}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="reservation-detail-section">
                            <h4 class="detail-section-title-base">Ressource réservée</h4>
                            <div class="resource-info-card-base">
                                <div class="resource-header">
                                    <i class='bx bx-server'></i>
                                    <div>
                                        <strong>${data.resource.name}</strong>
                                        <div class="text-muted small">${data.resource.category?.name || 'Non catégorisé'}</div>
                                    </div>
                                </div>
                                ${data.resource.cpu || data.resource.ram ? `
                                    <div class="resource-specs">
                                        ${data.resource.cpu ? `<div><i class='bx bx-microchip'></i> ${data.resource.cpu}</div>` : ''}
                                        ${data.resource.ram ? `<div><i class='bx bx-memory-card'></i> ${data.resource.ram}</div>` : ''}
                                        ${data.resource.capacity ? `<div><i class='bx bx-hdd'></i> ${data.resource.capacity}</div>` : ''}
                                        ${data.resource.location ? `<div><i class='bx bx-map'></i> ${data.resource.location}</div>` : ''}
                                    </div>
                                ` : ''}
                            </div>
                        </div>

                        <div class="reservation-detail-section">
                            <h4 class="detail-section-title-base">Période de réservation</h4>
                            <div class="detail-grid-base">
                                <div class="detail-item-base">
                                    <div class="detail-label-base">Date de début</div>
                                    <div class="detail-value-base">
                                        <i class='bx bx-calendar'></i>
                                        ${startDate.toLocaleDateString('fr-FR')}
                                        <br>
                                        <i class='bx bx-time'></i>
                                        ${startDate.toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' })}
                                    </div>
                                </div>
                                <div class="detail-item-base">
                                    <div class="detail-label-base">Date de fin</div>
                                    <div class="detail-value-base">
                                        <i class='bx bx-calendar'></i>
                                        ${endDate.toLocaleDateString('fr-FR')}
                                        <br>
                                        <i class='bx bx-time'></i>
                                        ${endDate.toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' })}
                                    </div>
                                </div>
                                <div class="detail-item-base">
                                    <div class="detail-label-base">Durée totale</div>
                                    <div class="detail-value-base">
                                        <i class='bx bx-timer'></i>
                                        ${durationHours}h ${durationMinutes}min
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    document.getElementById('reservationDetails').innerHTML = `
                        <div class="error-message-base">
                            <i class='bx bx-error-circle'></i>
                            <h4>Erreur de chargement</h4>
                            <p class="text-muted">Impossible de charger les détails de la réservation</p>
                            <button class="btn btn-primary mt-2" onclick="showReservationDetails(${reservationId})">
                                <i class='bx bx-refresh'></i> Réessayer
                            </button>
                        </div>
                    `;
                });
        }

        // Fermer le modal
        function closeReservationModal() {
            const modal = document.getElementById('reservationModal');
            modal.style.display = 'none';
        }

        // Fermer le modal avec la touche Escape
        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                closeReservationModal();
            }
        });

        // Marquer les notifications comme lues
        document.addEventListener('DOMContentLoaded', function () {
            const unreadNotifications = document.querySelectorAll('.notification-item-base.unread');

            unreadNotifications.forEach(notification => {
                notification.addEventListener('click', function () {
                    if (this.classList.contains('unread')) {
                        this.classList.remove('unread');

                        // Vous pouvez ajouter ici une requête AJAX pour marquer la notification comme lue
                        // fetch('/notifications/mark-as-read', { method: 'POST', ... })
                    }
                });
            });
        });
    </script>
@endpush