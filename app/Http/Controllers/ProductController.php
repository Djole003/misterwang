<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\AddOn;
use App\Models\Category;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Početna stranica
     */
    public function index()
    {
        return view('index');
    }

    /**
     * Jelovnik sa grupisanjem po kategorijama
     */
    public function jelovnikPoKategorijama()
    {
        $restaurantId = session('restaurant_id');

        if (!$restaurantId) {
            abort(404);
        }

        $restaurant = Restaurant::with([
            'products.category',
            'products.category.addOns'
        ])->findOrFail($restaurantId);

        $products = $restaurant->products;

        // grupisanje po kategorijama
        $productsByCategory = $products->groupBy(function ($product) {
            return $product->category->id;
        });

        // sve kategorije + addons
        $categories = Category::with('addOns')->get();

        $najprodavanija = $restaurant->products->whereIn('name', [
            'Mesano povrće',
            'Hrskava piletina',
            'Kung pao piletina',
            'Kraljevska Piletina',
            'Meso sa sampinjonima'
        ]);

        return view('jelovnik.jelovnik', compact(
            'productsByCategory',
            'categories',
            'najprodavanija'
        ));
    }

    /**
     * Detalji jela sa predlozima
     */
    public function showWithSuggestions($id)
    {
        $restaurantId = session('restaurant_id');

        if (!$restaurantId) {
            abort(404);
        }

        $restaurant = Restaurant::with('products.category')
            ->findOrFail($restaurantId);

        $jelo = $restaurant->products->where('id', $id)->firstOrFail();

        $orderType = session('order_type', 'delivery');

        $pice = $restaurant->products
            ->where('category.name', 'Piće')
            ->where('id', '!=', $id)
            ->take(3);

        $dezerti = $restaurant->products
            ->where('category.name', 'Dezerti')
            ->where('id', '!=', $id)
            ->take(3);

        $preporuceno = $restaurant->products
            ->where('id', '!=', $id)
            ->shuffle()
            ->take(4);

        return view('jelovnik.show_with_suggestions', compact(
            'jelo',
            'pice',
            'dezerti',
            'preporuceno',
            'orderType'
        ));
    }

    /**
     * Prikaz proizvoda po kategoriji
     */
    public function showCategory($slug)
    {
        $restaurantId = session('restaurant_id');

        if (!$restaurantId) {
            abort(404);
        }

        $restaurant = Restaurant::with('products.category')
            ->findOrFail($restaurantId);

        // 👇 UČITAVAMO I ADDONS RELACIJU
        $category = Category::where('slug', $slug)
            ->with('addOns')
            ->firstOrFail();

        $products = $restaurant->products
            ->where('category_id', $category->id);

        return view('jelovnik.kategorija', compact('category', 'products'));
    }
}