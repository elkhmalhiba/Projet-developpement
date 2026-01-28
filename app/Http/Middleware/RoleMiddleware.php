<?php


namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // pour le helper auth() type-safe

class RoleMiddleware
{
    /**
     * Vérifie que l'utilisateur connecté a le rôle requis.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $role)
{
    // On vérifie si l'utilisateur est connecté ET s'il a un rôle
    if (!Auth::check() || !Auth::user()->role) {
        abort(403, "Vous n'avez pas de rôle assigné.");
    }

    if (Auth::user()->role->name !== $role) {
        abort(403, "Accès interdit");
    }

    return $next($request);
}
}