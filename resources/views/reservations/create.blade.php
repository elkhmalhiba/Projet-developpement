@extends('layouts.app')

@section('title', 'Réserver ' . $resource->name)

@push('styles')
<style>
    /* Retour link */
    .back-link-base {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--text-main);
        text-decoration: none;
        margin-bottom: 1.5rem;
        transition: color 0.2s ease;
    }
    
    .back-link-base:hover {
        color: var(--primary);
    }
    
    .back-link-base i {
        font-size: 1.2rem;
    }
    
    /* Resource info card */
    .resource-info-card-reservation {
        background: linear-gradient(135deg, var(--primary) 0%, #8b5cf6 100%);
        color: white;
        border: none;
    }
    
    .resource-info-card-reservation h3 {
        color: white;
        margin-bottom: 0.25rem;
    }
    
    .resource-info-card-reservation p {
        opacity: 0.9;
        margin-bottom: 0;
    }
    
    /* Badges spécifiques */
    .badge-occupied {
        background: rgba(239, 68, 68, 0.1);
        color: var(--danger);
        border: 1px solid rgba(239, 68, 68, 0.2);
    }
    
    .badge-pending {
        background: rgba(245, 158, 11, 0.1);
        color: var(--warning);
        border: 1px solid rgba(245, 158, 11, 0.2);
    }
    
    /* Table de réservation */
    .reservation-table-base th {
        background: rgba(0, 0, 0, 0.02);
        font-weight: 600;
    }
    
    .reservation-table-base td {
        vertical-align: middle;
    }
    
    /* Alert warning spécifique */
    .alert-reservation {
        background: rgba(245, 158, 11, 0.1);
        border-color: rgba(245, 158, 11, 0.2);
        color: var(--warning);
    }
</style>
@endpush

@section('content')
<div class="container mt-4">
    <!-- Info ressource -->
    <div class="card mb-4 resource-info-card-reservation">
        <div class="card-body">
            <h3 class="mb-1">
                <i class='bx bx-server'></i>
                {{ $resource->name }}
            </h3>
            <p class="text-muted" style="color: rgba(255,255,255,0.9) !important">Équipement sélectionné</p>
        </div>
    </div>

    <!-- Plages occupées -->
    <div class="card mb-4">
        <div class="section-header-base">
            <h4>
                <i class='bx bx-calendar-event'></i>
                Plages Horaires Occupées
            </h4>
        </div>

        <div class="table-responsive">
            <table class="table-base reservation-table-base">
                <thead>
                    <tr>
                        <th>Date Début</th>
                        <th>Date Fin</th>
                        <th>Heures</th>
                        <th>État</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($existingReservations as $res)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($res->start_date)->format('d M Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($res->end_date)->format('d M Y') }}</td>
                            <td>
                                {{ \Carbon\Carbon::parse($res->start_date)->format('H:i') }}
                                -
                                {{ \Carbon\Carbon::parse($res->end_date)->format('H:i') }}
                            </td>
                            <td>
                                @if($res->status === 'VALIDÉE')
                                    <span class="badge badge-occupied">
                                        <i class='bx bx-check-circle'></i> Occupé
                                    </span>
                                @else
                                    <span class="badge badge-pending">
                                        <i class='bx bx-timer'></i> En attente
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">
                                <i class='bx bx-select-multiple'></i>
                                Aucun créneau réservé
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Nouvelle réservation -->
    <div class="card">
        <div class="section-header-base">
            <h4>
                <i class='bx bx-edit-alt'></i>
                Nouvelle Réservation
            </h4>
        </div>

        <div class="card-body">
            <form action="{{ route('reservations.store') }}" method="POST">
                @csrf

                <input type="hidden" name="resource_id" value="{{ $resource->id }}">

                <div class="form-group">
                    <label class="form-label">Date & Heure de début</label>
                    <input type="datetime-local" name="start_date" class="form-control" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Date & Heure de fin</label>
                    <input type="datetime-local" name="end_date" class="form-control" required>
                </div>

                <div class="alert alert-reservation">
                    <strong>Attention :</strong>
                    Assurez-vous que votre créneau ne chevauche pas une réservation existante.
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class='bx bx-send'></i>
                    Confirmer la réservation
                </button>
            </form>
        </div>
    </div>

</div>
@endsection