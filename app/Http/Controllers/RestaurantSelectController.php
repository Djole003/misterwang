<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Restaurant;
use App\Services\RestaurantStatusService;

class RestaurantSelectController extends Controller
{
    // Prikaz stranice sa lokalima
    public function index(RestaurantStatusService $service)
    {
        $restaurants = Restaurant::where('is_active', 1)->get();

        // Za svaki restoran dodaj status
        $restaurants->map(function ($restaurant) use ($service) {
            $restaurant->status = $service->getStatusMessage($restaurant);
            return $restaurant;
        });

        return view('select-restaurant', compact('restaurants'));
    }

    // Čuvanje izbora lokala
    public function select(Request $request)
    {
        $request->validate([
            'restaurant_id' => 'required|exists:restaurants,id',
        ]);

        session([
            'restaurant_id' => $request->restaurant_id
        ]);

        return redirect()->route('index'); // /pocetna
    }
}
