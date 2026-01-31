<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\AddOn;
use App\Models\Category;
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
        // Svi proizvodi sa kategorijama učitanim
        $products = Product::with('category')->get();

        // Dodaci
        $addons = AddOn::all();

        // Grupisanje po kategorijama
        $productsByCategory = $products->groupBy(function($product){
            return $product->category->name;
        });

        // Lista kategorija
        $categories = Category::all();

        return view('jelovnik.jelovnik', compact('productsByCategory', 'addons', 'categories'));
    }

    /**
     * Detalji jela sa predlozima
     */
    public function showWithSuggestions($id)
    {
        $orderType = session('order_type', 'delivery'); // uzimamo tip porudžbine iz sessiona

        $jelo = Product::findOrFail($id);

        // Prava cena zavisno od tipa porudžbine
        $jelo->display_price = $orderType === 'delivery' ? $jelo->price_delivery : $jelo->price_takeaway;

        $pice = Product::whereHas('category', function($q){
                    $q->where('name', 'Piće');
                })->where('id', '!=', $id)
                ->take(3)
                ->get();

        $dezerti = Product::whereHas('category', function($q){
                    $q->where('name', 'Dezerti');
                })->where('id', '!=', $id)
                ->take(3)
                ->get();

        $preporuceno = Product::where('id', '!=', $id)
            ->inRandomOrder()
            ->take(4)
            ->get();

        return view('jelovnik.show_with_suggestions', compact('jelo', 'pice', 'dezerti', 'preporuceno', 'orderType'));
    }


    /**
     * Prikaz proizvoda po kategoriji
     */
    public function showCategory($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        $products = $category->products;
        $addons = AddOn::all();
        return view('jelovnik.kategorija', compact('category', 'products', 'addons'));
    }
}
