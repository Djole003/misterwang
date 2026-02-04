<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\RadnoVreme;

class CheckOpeningHours
{
    public function handle(Request $request, Closure $next)
    {
        $now = Carbon::now('Europe/Belgrade');

        // Uzimamo ID restorana iz sesije – TAČNO kao u tvom kontroleru
        $restaurantId = session('restaurant_id');

        // Ako korisnik još nije izabrao lokal, nema provere
        if (!$restaurantId) {
            return $next($request);
        }

        $dayNumber = $now->dayOfWeek; // 0 = nedelja, 6 = subota
        $currentTime = $now->format('H:i');

        $radnoVreme = RadnoVreme::where('restaurant_id', $restaurantId)
            ->where('dan', $dayNumber)
            ->first();

        $message = null;

        // Ako za taj dan nema radnog vremena ili je null → lokal ne radi
        if (!$radnoVreme || !$radnoVreme->otvara_se || !$radnoVreme->zatvara_se) {
            $message = 'Nažalost, lokal danas ne radi.';
        } else {
            $otvara = substr($radnoVreme->otvara_se, 0, 5);
            $zatvara = substr($radnoVreme->zatvara_se, 0, 5);

            if ($currentTime < $otvara || $currentTime >= $zatvara) {
                $message = "Nažalost, lokal je trenutno zatvoren. Radno vreme danas je {$otvara} - {$zatvara}h.";
            }
        }

        view()->share('openingMessage', $message);

        return $next($request);
    }
}
