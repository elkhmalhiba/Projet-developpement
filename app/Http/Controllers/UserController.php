<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\ResourceCategory;
use Carbon\Carbon;
use App\Models\Resource;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Affiche l'espace personnel de l'utilisateur interne.
     */
    public function dashboard(Request $request)
    {
        $user = Auth::user();

        // Récupérer les réservations de l'utilisateur
        $reservations = Reservation::where('user_id', $user->id)
            ->with(['resource.category'])
            ->latest()
            ->get();

        // Récupérer les notifications
        $notifications = $user->notifications()
            ->latest()
            ->take(10)
            ->get();

        // Statistiques
        $stats = [
            'total' => $reservations->count(),
            'validated' => $reservations->where('status', 'VALIDÉE')->count(),
            'pending' => $reservations->where('status', 'EN ATTENTE')->count(),
            'rejected' => $reservations->where('status', 'REFUSÉE')->count(),
        ];

        // Date de la dernière réservation
        $lastReservation = $reservations->first();
        $lastReservationDate = $lastReservation ? $lastReservation->created_at->diffForHumans() : 'Jamais';

        // Ressources récemment réservées
        $recentResources = Resource::whereIn(
            'id',
            $reservations->where('status', 'VALIDÉE')->pluck('resource_id')
        )->take(4)->get();

        return view('user.dashboard', compact(
            'reservations',
            'stats',
            'notifications',
            'recentResources',
            'lastReservationDate'
        ));
    }

    /**
     * Historique des réservations
     */
    public function historique(Request $request)
    {
        $user = Auth::user();

        $query = Reservation::with(['resource.category'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc');

        // إحصائيات
        $totalReservations = $query->count();
        $validatedCount = Reservation::where('user_id', $user->id)
            ->where('status', 'VALIDÉE')
            ->count();
        $rejectedCount = Reservation::where('user_id', $user->id)
            ->where('status', 'REFUSÉE')
            ->count();

        // حساب مجموع الساعات
        $totalHours = Reservation::where('user_id', $user->id)
            ->where('status', 'VALIDÉE')
            ->get()
            ->sum(function ($res) {
                return Carbon::parse($res->end_date)
                    ->diffInHours(Carbon::parse($res->start_date));
            });

        // Pagination
        $reservations = $query->paginate(10);

        // الفئات للفلترة
        $categories = ResourceCategory::all();

        return view('user.historique', compact(
            'reservations',
            'totalReservations',
            'validatedCount',
            'rejectedCount',
            'totalHours',
            'categories'
        ));
    }

    /**
     * Afficher les ressources disponibles
     */
    public function resources()
    {
        $resources = Resource::where('status', 'available')
            ->with('category')
            ->orderBy('resource_category_id')
            ->get();

        $categories = ResourceCategory::all();

        return view('user.resources', compact('resources', 'categories'));
    }

    /**
     * Afficher le profil utilisateur
     */
    public function profile()
    {
        $user = Auth::user();
        return view('user.profile', compact('user'));
    }

    /**
     * Mettre à jour le profil utilisateur
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->update($request->only('name', 'email'));

        return redirect()->route('user.profile')
            ->with('success', 'Profil mis à jour avec succès.');
    }
}