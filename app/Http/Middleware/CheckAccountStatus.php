<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckAccountStatus
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Si l'utilisateur est connecté mais que son statut n'est pas 'active'
        if (Auth::check() && Auth::user()->status !== 'active') {
            Auth::logout();

            // On invalide la session pour plus de sécurité
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withErrors([
                'email' => 'Votre compte a été désactivé par un administrateur.'
            ]);
        }

        return $next($request);
    }
}