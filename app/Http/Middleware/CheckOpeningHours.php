<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CheckOpeningHours
{
    public function handle(Request $request, Closure $next)
    {
        // Uzmi vreme u Beogradu
        $now = Carbon::now('Europe/Belgrade');
        $day = $now->format('l'); // naziv dana: Monday, Tuesday...
        $hour = $now->hour;

        $message = null;

        if ($day === 'Saturday') {
            $message = 'Nažalost, lokal je zatvoren subotom.';
        } elseif ($day === 'Sunday' && ($hour < 11 || $hour >= 20)) {
            $message = 'Nažalost, lokal je trenutno zatvoren. Radno vreme nedeljom je 11:00 - 20:00h.';
        } elseif ($day !== 'Saturday' && $day !== 'Sunday' && ($hour < 9 || $hour >= 22)) {
            $message = 'Nažalost, lokal je trenutno zatvoren. Radno vreme je 9:00 - 22:00h.';
        }

        // Prosledi poruku svim view-ovima
        view()->share('openingMessage', $message);

        return $next($request);
    }
}
