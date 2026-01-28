<?php
namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Incident;
use App\Models\Resource;

class TechController extends Controller {
    public function dashboard(){
        // Statistiques
        $totalReservations = Reservation::count();
        $totalIncidents = Incident::count();
        $totalResources = Resource::count();

        $stats = [
            'pending_reservations' => Reservation::where('status', 'EN ATTENTE')->count(),
            'open_incidents' => Incident::where('status', 'ouvert')->count(),
            'total_resources' => $totalResources,
            'maintenance_resources' => Resource::where('status', 'maintenance')->count(),
            'available_resources' => Resource::where('status', 'available')->count(),
        ];

        // Récupérer les réservations en attente
        $pendingReservations = Reservation::with(['user', 'resource.category'])
            ->where('status', 'EN ATTENTE')->get();

        // Récupérer les incidents ouverts
        $openIncidents = Incident::with(['user', 'resource.category'])
            ->where('status', 'ouvert')->get();

        // Ressources nécessitant attention
        $attentionResources = Resource::where('status', 'maintenance')
            ->orWhere('status', 'occupied')->with('category')->get();

        return view('responsable.dashboard', compact(
            'stats',
            'pendingReservations',
            'openIncidents',
            'attentionResources'
        ));
    }

    // Valider une réservation (cette méthode existe déjà dans ReservationController)
    // Gardez-la seulement si vous voulez une logique spécifique au tech

    // Résoudre un incident
    public function resolveIncident(Incident $incident){
        $incident->update(['status' => 'résolu']);

        // Envoyer une notification
        $incident->user->notifications()->create([
            'title' => 'Incident résolu',
            'message' => "Votre incident concernant {$incident->resource->name} a été résolu.",
            'is_read' => false
        ]);

        return back()->with('success', 'Incident marqué comme résolu.');
    }

    // Valider une réservation
    public function approveReservation(Reservation $reservation){
        $reservation->update(['status' => 'VALIDÉE']);

        // Envoyer une notification
        $reservation->user->notifications()->create([
            'title' => 'Réservation approuvée',
            'message' => "Votre réservation pour {$reservation->resource->name} a été approuvée.",
            'is_read' => false
        ]);

        return back()->with('success', 'Réservation approuvée.');
    }

    // Refuser une réservation
    public function rejectReservation(Reservation $reservation){
        $reservation->update(['status' => 'REFUSÉE']);
        // Envoyer une notification
        $reservation->user->notifications()->create([
            'title' => 'Réservation refusée',
            'message' => "Votre réservation pour {$reservation->resource->name} a été refusée.",
            'is_read' => false
        ]);
        return back()->with('success', 'Réservation refusée.');
    }
}