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

            if (!$zone->polygon) {
                continue;
            }

            if ($this->pointInPolygon([$lat, $lng], $zone->polygon)) {
                return [
                    'name'    => $zone->name,
                    'price'   => $zone->price,
                    'minimum' => $zone->minimum_amount,
                ];
            }
        }

        return null;
    }

    private function pointInPolygon($point, $polygon)
    {
        $x = $point[0];
        $y = $point[1];
        $inside = false;

        $count = count($polygon);

        for ($i = 0, $j = $count - 1; $i < $count; $j = $i++) {

            $xi = $polygon[$i]['lat'];
            $yi = $polygon[$i]['lng'];
            $xj = $polygon[$j]['lat'];
            $yj = $polygon[$j]['lng'];

            $intersect = (($yi > $y) != ($yj > $y)) &&
                ($x < ($xj - $xi) * ($y - $yi) / ($yj - $yi) + $xi);

            if ($intersect) {
                $inside = !$inside;
            }
        }

        return $inside;
    }
}