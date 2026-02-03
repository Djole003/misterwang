<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CheckOpeningHours
{
    public function handle(Request $request, Closure $next)
{
    $now = Carbon::now('Europe/Belgrade');
    $day = $now->format('l');
    $currentTime = $now->format('H:i');

    $message = null;

    if ($day === 'Saturday') {
        $message = 'Nažalost, lokal je zatvoren subotom.';
    } 
    elseif ($day === 'Sunday') {
        if ($currentTime < '11:00' || $currentTime >= '19:45') {
            $message = 'Nažalost, lokal je trenutno zatvoren. Radno vreme nedeljom je 11:00 - 19:45h.';
        }
    } 
    else {
        // Radnim danima
        if ($currentTime < '09:00' || $currentTime >= '21:45') {
            $message = 'Nažalost, lokal je trenutno zatvoren. Radno vreme je 09:00 - 21:45h.';
        }
    }

    view()->share('openingMessage', $message);

    return $next($request);
}

}
