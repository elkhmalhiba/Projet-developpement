<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Resource;
use App\Models\Role;
use App\Models\Reservation;
use App\Models\Incident;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Statistiques
        $totalResources = Resource::count();
        $totalUsers = User::count();

        $stats = [
            'total_users' => $totalUsers,
            'total_resources' => $totalResources,
            'occupied_rate' => $totalResources > 0
                ? round((Resource::where('status', 'occupied')->count() / $totalResources) * 100)
                : 0,
            'maintenance_count' => Resource::where('status', 'maintenance')->count(),
            'pending_reservations' => Reservation::where('status', 'EN ATTENTE')->count(),
            'open_incidents' => Incident::where('status', 'ouvert')->count(),
            'active_users' => User::where('status', 'active')->count(),
            'inactive_users' => User::where('status', 'inactive')->count(),
            'available_resources' => Resource::where('status', 'available')->count(),
            'occupied_resources' => Resource::where('status', 'occupied')->count(),
        ];

        // Données pour les tableaux
        $users = User::with('role')->get();
        $resources = Resource::with('category')->get();
        $roles = Role::all();
        $recentReservations = Reservation::with(['user', 'resource'])->get();
        $recentIncidents = Incident::with(['user', 'resource'])->get();

        // Récupérer les catégories
        $categories = \App\Models\ResourceCategory::all();

        return view('admin.dashboard', compact(
            'stats',
            'users',
            'resources',
            'roles',
            'recentReservations',
            'recentIncidents',
            'categories'
        ));
    }

    // Gérer le rôle d'un utilisateur
    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id'
        ]);

        $user->update(['role_id' => $request->role_id]);

        return back()->with('success', 'Rôle mis à jour avec succès.');
    }

    // Activer ou Désactiver un compte
    public function toggleUserStatus(User $user)
    {
        $user->status = ($user->status === 'active') ? 'inactive' : 'active';
        $user->save();
        return back()->with('success', 'Statut du compte modifié.');
    }

    // Mettre un serveur en maintenance
    public function toggleMaintenance(Resource $resource)
    {
        $resource->status = ($resource->status === 'maintenance') ? 'available' : 'maintenance';
        $resource->save();
        return back()->with('success', 'État de maintenance mis à jour.');
    }

    // Vue pour gérer toutes les réservations
    public function reservations()
    {
        $reservations = Reservation::with(['user', 'resource'])
            ->latest()
            ->get();

        return view('admin.reservations', compact('reservations'));
    }

    // Vue pour gérer tous les incidents
    public function incidents()
    {
        $incidents = Incident::with(['user', 'resource'])
            ->latest()
            ->get();
        return view('admin.incidents', compact('incidents'));
    }
}