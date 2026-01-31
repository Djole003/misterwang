<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Restaurant;

class RestaurantSelectController extends Controller
{
    // Prikaz stranice sa lokalima
    public function index()
    {
        $restaurants = Restaurant::where('is_active', 1)->get();
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
