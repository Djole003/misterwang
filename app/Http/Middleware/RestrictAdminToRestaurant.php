<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RestrictAdminToRestaurant
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        if (!$user) {
            return $next($request);
        }

        // EDITOR – vidi sve
        if ($user->role === 'editor') {
            return $next($request);
        }

        // ADMIN – zakucan za restoran
        if ($user->role === 'admin') {

            if (!$user->restaurant_id) {
                abort(403, 'Admin nema dodeljen restoran.');
            }

            session([
                'restaurant_id' => $user->restaurant_id
            ]);
        }

        return $next($request);
    }
}
