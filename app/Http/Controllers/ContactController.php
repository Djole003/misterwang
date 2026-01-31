<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\RestaurantContact;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    /**
     * Prikaz kontakt stranice – PO LOKALU
     */
    public function show()
    {
        $restaurantId = session('restaurant_id');

        // zaštita ako neko uđe bez lokala
        if (!$restaurantId) {
            return redirect()->route('select.restaurant');
        }

        // uzimamo kontakt SAMO tog lokala
        $contact = RestaurantContact::where('restaurant_id', $restaurantId)->first();

        // recenzije ostaju globalne (ili kasnije možeš po lokalu)
        $reviews = Review::latest()->take(5)->get();

        return view('contact.contact', compact('contact', 'reviews'));
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
