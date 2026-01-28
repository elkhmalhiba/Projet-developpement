<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Resource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReservationController extends Controller
{
    public function index()
    {
        $reservations = Reservation::where('user_id', Auth::id())
            ->with('resource')
            ->latest()
            ->get();

        return view('user.dashboard', compact('reservations'));
    }

    public function create(Request $request)
    {
        $resourceId = $request->query('resource_id');
        $resource = Resource::findOrFail($resourceId);

        $existingReservations = Reservation::where('resource_id', $resourceId)
            ->whereIn('status', ['VALIDÉE', 'EN ATTENTE']) 
            ->orderBy('start_date', 'asc')
            ->get();

        return view('reservations.create', compact('resource', 'existingReservations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'resource_id' => 'required|exists:resources,id',
            'start_date'  => 'required|date|after:now',
            'end_date'    => 'required|date|after:start_date',
        ]);

        $overlap = Reservation::where('resource_id', $request->resource_id)
            ->whereIn('status', ['VALIDÉE', 'EN ATTENTE'])
            ->where(function ($query) use ($request) {
                $query->where('start_date', '<', $request->end_date)
                      ->where('end_date', '>', $request->start_date);
            })->exists();

        if ($overlap) {
            return back()->withInput()->withErrors([
                'overlap_error' => 'Cet équipement est déjà réservé ou en attente sur ce créneau.'
            ]);
        }

        Reservation::create([
            'user_id' => auth()->id(),
            'resource_id' => $request->resource_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => 'EN ATTENTE'
        ]);

        return redirect()->route('user.dashboard')->with('success', 'Demande envoyée !');
    }

    public function update(Request $request, Reservation $reservation)
    {
        $request->validate([
            'status' => 'required|in:VALIDÉE,REFUSÉE'
        ]);

        $oldStatus = $reservation->status;
        
        // 1. On met à jour en base de données
        $reservation->update([
            'status' => $request->status
        ]);

        // 2. LOGIQUE DE NOTIFICATION (Placée AVANT le return)
        if ($oldStatus !== $request->status) {
            $title = "Mise à jour de votre réservation";
            $message = $request->status == 'VALIDÉE' 
                ? "Félicitations ! Votre demande pour [{$reservation->resource->name}] a été validée."
                : "Désolé, votre demande pour [{$reservation->resource->name}] a été refusée.";

            if (method_exists($reservation->user, 'addNotification')) {
                $reservation->user->addNotification($title, $message);
            }
        }

        // 3. Enfin, on redirige
        return back()->with('success', 'Le statut a été mis à jour et l\'utilisateur notifié.');
    }

    public function historique()
    {
        $reservations = Reservation::where('user_id', Auth::id())
            ->with('resource')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user.historique', compact('reservations'));
    }

    public function destroy(Reservation $reservation)
    {
        if (Auth::id() !== $reservation->user_id && !Auth::user()->is_admin) {
            return back()->with('error', 'Action non autorisée.');
        }

        $reservation->delete();
        return back()->with('success', 'Réservation supprimée.');
    }
}