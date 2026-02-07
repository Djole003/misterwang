<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\RestaurantContact;
use App\Models\DeliveryZone;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    /**
     * Prikaz kontakt stranice – PO LOKALU
     */
    public function show()
    {
        $restaurantId = session('restaurant_id');

        // zaštita ako neko uđe bez izabranog lokala
        if (!$restaurantId) {
            return redirect()->route('select.restaurant');
        }

        // kontakt podaci za izabrani lokal
        $contact = RestaurantContact::where('restaurant_id', $restaurantId)->first();

        if (!$contact) {
            return redirect()->route('select.restaurant')
                ->with('error', 'Kontakt podaci za izabrani lokal nisu pronađeni.');
        }

        // zone dostave za taj restoran
        $zones = DeliveryZone::where('restaurant_id', $restaurantId)->get();

        // recenzije – trenutno globalne
        $reviews = Review::latest()->take(5)->get();

        return view('contact.contact', [
            'contact' => $contact,
            'reviews' => $reviews,
            'zones'   => $zones
        ]);
    }

    /**
     * Čuvanje recenzije
     */
    public function submitReview(Request $request)
    {
        $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'message' => 'required|string|max:1000',
        ]);

        Review::create([
            'user_id' => Auth::id(),
            'rating'  => $request->rating,
            'comment' => $request->message,
        ]);

        return redirect()->back()->with('success', 'Hvala na recenziji!');
    }
}
