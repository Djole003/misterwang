<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\RestaurantStatusService; // ⬅️ OVO JE FALILO

class CheckRestaurantOpen
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!RestaurantStatusService::isOpen()) {
            return redirect()->back()->with(
                'error',
                '⏰ Restoran trenutno ne radi. Poručivanje je onemogućeno.'
            );
        }

        return $next($request);
    }
}
