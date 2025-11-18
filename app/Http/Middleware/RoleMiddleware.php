<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    /**
     * Gérer l'accès selon le rôle.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $role)
    {
        $user = auth()->user();

        // Non connecté
        if (!$user) {
            return redirect()->route('login');
        }

        // L'utilisateur est admin => accès autorisé partout
        if ($user->role === 'admin') {
            return $next($request);
        }

        // Vérifier le rôle spécifique
        if ($user->role !== $role) {
            abort(403, "Accès refusé.");
        }

        return $next($request);
    }
}
