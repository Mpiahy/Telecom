<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckGuest
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
        if (Auth::check() && !Auth::user()->isAdmin) {
            // L'utilisateur est un invité
            return $next($request);
        }        

        // Redirection si l'utilisateur est admin
        return redirect('/index')->with('error', 'Accès réservé aux invités.');
    }
}
