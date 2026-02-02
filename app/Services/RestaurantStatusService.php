<?php

namespace App\Services;

use App\Models\Restaurant;
use App\Models\RadnoVreme;
use Carbon\Carbon;

class RestaurantStatusService
{
    public function isRestaurantOpen(Restaurant $restaurant): bool
    {
        // Ako je restoran označen kao neaktivan
        if (!$restaurant->is_active) {
            return false;
        }

        $now = Carbon::now();
        $currentDay = $now->dayOfWeek;   // 0 = nedelja, 1 = ponedeljak...

        // Pronađi radno vreme za danas
        $radnoVreme = RadnoVreme::where('restaurant_id', $restaurant->id)
            ->where('dan', $currentDay)
            ->first();

        // Ako nema unosa za taj dan → restoran ne radi taj dan
        if (!$radnoVreme) {
            return false;
        }

        $opens = Carbon::createFromFormat('H:i:s', $radnoVreme->otvara_se);
        $closes = Carbon::createFromFormat('H:i:s', $radnoVreme->zatvara_se);

        return $now->between($opens, $closes);
    }

    public function getOpeningTime(Restaurant $restaurant): ?string
    {
        $currentDay = Carbon::now()->dayOfWeek;

        $radnoVreme = RadnoVreme::where('restaurant_id', $restaurant->id)
            ->where('dan', $currentDay)
            ->first();

        if (!$radnoVreme) {
            return null;
        }

        return substr($radnoVreme->otvara_se, 0, 5);
    }

    /**
     * Vraća informaciju o statusu restorana
     * koristi se na stranici "izaberi lokal"
     */
    public function getStatusMessage(Restaurant $restaurant): array
    {
        $isOpen = $this->isRestaurantOpen($restaurant);

        if ($isOpen) {
            return [
                'open' => true,
                'message' => '🟢 Otvoreno'
            ];
        }

        $openingTime = $this->getOpeningTime($restaurant);

        // Ako nema radnog vremena za danas
        if (!$openingTime) {
            return [
                'open' => false,
                'message' => '🔴 Danas ne radi'
            ];
        }

        return [
            'open' => false,
            'message' => '🔴 Zatvoreno – otvara se u ' . $openingTime
        ];
    }
}
