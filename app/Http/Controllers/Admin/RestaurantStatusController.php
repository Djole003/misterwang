<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;

class RestaurantStatusController extends Controller
{
    public function toggle(): RedirectResponse
    {
        $current = DB::table('restaurant_status')->value('is_open');

        DB::table('restaurant_status')->update([
            'is_open' => !$current,
            'updated_at' => now(),
        ]);

        return redirect()->back()->with(
            'success',
            $current
                ? '⛔ Restoran je zatvoren'
                : '✅ Restoran je otvoren'
        );
    }
}
