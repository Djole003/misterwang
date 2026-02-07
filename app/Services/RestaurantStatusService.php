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

        // Ako nema unosa ili su vremena NULL → restoran ne radi taj dan
        if (
            !$radnoVreme ||
            empty($radnoVreme->otvara_se) ||
            empty($radnoVreme->zatvara_se)
        ) {
            return false;
        }

        try {
            $opens = Carbon::createFromFormat('H:i:s', $radnoVreme->otvara_se);
            $closes = Carbon::createFromFormat('H:i:s', $radnoVreme->zatvara_se);
        } catch (\Exception $e) {
            return false;
        }

        return $now->between($opens, $closes);
    }

    public function getOpeningTime(Restaurant $restaurant): ?string
    {
        $currentDay = Carbon::now()->dayOfWeek;

        $radnoVreme = RadnoVreme::where('restaurant_id', $restaurant->id)
            ->where('dan', $currentDay)
            ->first();

        // Ako nema zapisa ili je vreme NULL
        if (
            !$radnoVreme ||
            empty($radnoVreme->otvara_se)
        ) {
            return null;
        }

        // Vraća samo HH:MM format
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
