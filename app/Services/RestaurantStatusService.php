<?php

namespace App\Services;

use App\Models\DeliveryZone;

class DeliveryZoneService
{
    public function getZoneForCoordinates($lat, $lng)
    {
        $restaurantId = session('restaurant_id');

        if (!$restaurantId) {
            return null;
        }

        $zones = DeliveryZone::where('restaurant_id', $restaurantId)->get();

        foreach ($zones as $zone) {

            $distance = $this->calculateDistance(
                $lat,
                $lng,
                $zone->center_lat,
                $zone->center_lng
            );

            if ($distance <= $zone->radius) {
                return [
                    'name'  => $zone->name,
                    'price' => $zone->price,
                ];
            }
        }

        return null;
    }

    private function calculateDistance($lat1, $lng1, $lat2, $lng2)
    {
        $earthRadius = 6371000;

        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);

        $a =
            sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) *
            cos(deg2rad($lat2)) *
            sin($dLng / 2) * sin($dLng / 2);

        return $earthRadius * (2 * atan2(sqrt($a), sqrt(1 - $a)));
    }
}
