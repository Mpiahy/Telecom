<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->isAdmin) {
            // L'utilisateur est un administrateur
            return $next($request);
        }        

        // Redirection si l'utilisateur n'est pas admin
        return redirect('/index')->with('error', 'Accès réservé aux administrateurs.');
    }
}
