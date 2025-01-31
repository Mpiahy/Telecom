<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
// use Symfony\Component\HttpFoundation\Response;


class CheckSession
{
    /**
     * Vérifiez si l'utilisateur est authentifié ou si la session contient des informations valides.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Vérifie si l'utilisateur est authentifié ou si la session contient un login
        if (Auth::check() || session('login')) {
            return $next($request);
        }

        // Si l'utilisateur n'est pas authentifié, redirigez vers la page d'accueil ou la page de connexion
        return redirect('/login'); // Par exemple, la page de login
    }
}
