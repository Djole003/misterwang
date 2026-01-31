<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureRestaurantSelected
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Ako je korisnik ulogovan
        if (auth()->check()) {
            $user = auth()->user();

            /*
            |--------------------------------------------------------------------------
            | ADMIN – automatski mu dodeljujemo njegov restoran
            |--------------------------------------------------------------------------
            */
            if ($user->role === 'admin') {

                if (!$user->restaurant_id) {
                    abort(403, 'Admin nema dodeljen restoran.');
                }

                session([
                    'restaurant_id' => $user->restaurant_id
                ]);

                return $next($request);
            }

            /*
            |--------------------------------------------------------------------------
            | EDITOR – ne forsiramo izbor (koristiće switch)
            |--------------------------------------------------------------------------
            */
            if ($user->role === 'editor') {
                return $next($request);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | AKO JE LOKAL VEĆ IZABRAN (guest / user)
        |--------------------------------------------------------------------------
        */
        if (session()->has('restaurant_id')) {
            return $next($request);
        }

        /*
        |--------------------------------------------------------------------------
        | DOZVOLI STRANICU ZA IZBOR LOKALA
        |--------------------------------------------------------------------------
        */
        if (
            $request->routeIs('select.restaurant') ||
            $request->routeIs('select.restaurant.store')
        ) {
            return $next($request);
        }

        /*
        |--------------------------------------------------------------------------
        | SVI OSTALI → NA IZBOR LOKALA
        |--------------------------------------------------------------------------
        */
        return redirect()->route('select.restaurant');
    }
}
