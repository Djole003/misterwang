<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\RestaurantStatusService;
use App\Models\Restaurant;

class CheckRestaurantOpen
{
    protected $restaurantStatusService;

    public function __construct(RestaurantStatusService $restaurantStatusService)
    {
        $this->restaurantStatusService = $restaurantStatusService;
    }

    public function handle(Request $request, Closure $next)
    {
        $restaurantId = session('restaurant_id');

        // Ako nije izabran restoran – dozvoli prolaz
        if (!$restaurantId) {
            return $next($request);
        }

        $restaurant = Restaurant::find($restaurantId);

        // Ako restoran ne postoji – dozvoli prolaz
        if (!$restaurant) {
            return $next($request);
        }

        // Provera da li je restoran otvoren
        if (!$this->restaurantStatusService->isRestaurantOpen($restaurant)) {

            return redirect()
                ->route('order.cart')
                ->with(
                    'error',
                    '⏰ Restoran trenutno ne radi. Poručivanje je onemogućeno.'
                );
        }

        return $next($request);
    }
}
