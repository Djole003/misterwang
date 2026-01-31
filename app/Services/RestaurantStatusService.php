<?php

namespace App\Services;

use App\Models\Restaurant;
use Carbon\Carbon;

class RestaurantStatusService
{
    public function isRestaurantOpen(Restaurant $restaurant): bool
    {
        if (!$restaurant->is_active) {
            return false;
        }

        if (!$restaurant->opens_at || !$restaurant->closes_at) {
            return true;
        }

        $now = Carbon::now()->format('H:i');

        return $now >= $restaurant->opens_at &&
               $now <= $restaurant->closes_at;
    }
}
