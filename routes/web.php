<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TechController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\IncidentController;
use App\Http\Controllers\NotificationController;
use App\Models\ResourceCategory;
use App\Models\Reservation;

/*
|--------------------------------------------------------------------------
| 1. ROUTES PUBLIQUES
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    $categories = ResourceCategory::with([
        'resources' => function ($query) {
            $query->where('status', 'available')->orderBy('name');
        }
    ])->get();

    return view('welcome', compact('categories'));
})->name('welcome');

// Afficher toutes les ressources (public)
Route::get('/resources', [ResourceController::class, 'index'])->name('resources.index');

/*
|--------------------------------------------------------------------------
| 2. AUTHENTIFICATION (GUEST)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

/*
|--------------------------------------------------------------------------
| 3. ROUTES PROTÉGÉES (AUTH)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Redirection /home → /
    Route::redirect('/home', '/');

    /*
    |--------------------------------------------------------------------------
    | ADMIN (ROLE: Admin)
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:Admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        // Gestion utilisateurs
        Route::patch('/users/{user}/role', [AdminController::class, 'updateRole'])->name('users.role');
        Route::patch('/users/{user}/toggle', [AdminController::class, 'toggleUserStatus'])->name('users.toggle');

        // Gestion ressources
        Route::patch('/resources/{resource}/maintenance', [AdminController::class, 'toggleMaintenance'])->name('resources.maintenance');
        Route::get('/resources/create', [ResourceController::class, 'create'])->name('resources.create');
        Route::post('/resources', [ResourceController::class, 'store'])->name('resources.store');
        Route::get('/resources/{resource}', [ResourceController::class, 'show'])->name('resources.show');
        Route::get('/resources/{resource}/edit', [ResourceController::class, 'edit'])->name('resources.edit');
        Route::put('/resources/{resource}', [ResourceController::class, 'update'])->name('resources.update');
        Route::delete('/resources/{resource}', [ResourceController::class, 'destroy'])->name('resources.destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | TECHNICIEN (ROLE: Responsable Technique)
    |--------------------------------------------------------------------------
    */
    Route::put('/reservations/{reservation}/update', [ReservationController::class, 'update'])->name('reservations.update');
    Route::middleware('role:Responsable Technique')->prefix('tech')->name('tech.')->group(function () {
        Route::get('/dashboard', [TechController::class, 'dashboard'])->name('dashboard');
        // Gestion des incidents
        Route::get('/incidents', [IncidentController::class, 'index'])->name('incidents.index');
        Route::delete('/incidents/{incident}', [IncidentController::class, 'destroy'])->name('incidents.destroy');
        Route::get('/incidents/{incident}', [IncidentController::class, 'show'])->name('incidents.show');
    });

    /*
    |--------------------------------------------------------------------------
    | UTILISATEUR INTERNE (ROLE: Utilisateur Interne)
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:Utilisateur Interne')->prefix('user')->name('user.')->group(function () {
        Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
        Route::get('/historique', [UserController::class, 'historique'])->name('historique');
        Route::get('/resources', [UserController::class, 'resources'])->name('resources');
    });

    /*
    |--------------------------------------------------------------------------
    | ROUTES COMMUNES À TOUS LES UTILISATEURS AUTHENTIFIÉS
    |--------------------------------------------------------------------------
    */

    // Réservations
    Route::get('/reservations', [ReservationController::class, 'index'])->name('reservations.index');
    Route::get('/reservations/create', [ReservationController::class, 'create'])->name('reservations.create');
    Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store');
    Route::delete('/reservations/{reservation}', [ReservationController::class, 'destroy'])->name('reservations.destroy');

    // Incidents
    Route::get('/incidents/create/{resource_id}', [IncidentController::class, 'create'])->name('incidents.create');
    Route::post('/incidents', [IncidentController::class, 'store'])->name('incidents.store');

    // Notifications
    Route::post('/notifications/mark-read', [NotificationController::class, 'markRead'])->name('notifications.markRead');

    // API pour consulter une réservation
    Route::middleware('auth:sanctum')->get('/reservations/{reservation}', function (Reservation $reservation) {
        if (Auth::id() !== $reservation->user_id && !Auth::user()->isAdmin()) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }
        return response()->json($reservation->load('resource.category'));
    });
});
