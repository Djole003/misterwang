<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;

class AdminReviewController extends Controller
{
    public function index() {
        $reviews = Review::with('user')->latest()->get();
        return view('admin.reviews.index', compact('reviews'));
    }

    public function edit(Review $review) {
        return view('admin.reviews.edit', compact('review'));
    }

    public function update(Request $request, Review $review) {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string',
        ]);

        $review->update($request->only('rating', 'comment'));

        return redirect()->route('admin.reviews.index')->with('success', 'Recenzija izmenjena.');
    }

    public function destroy(Review $review) {
        $review->delete();
        return redirect()->route('admin.reviews.index')->with('success', 'Recenzija obrisana.');
    }
}

