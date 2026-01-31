<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\RestaurantProductStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->get();
        return view('admin.products.product', compact('products'));
    }

    public function create()
    {
        return view('admin.products.product_create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string',
            'description' => 'required',
            'price'       => 'required|numeric',
            'category'    => 'required|string',
            'image'       => 'nullable|image|max:2048',
        ]);

        $product = new Product($request->except('image'));

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $product->image_path = $path;
        }

        $product->save();

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Proizvod dodat.');
    }

    public function edit(Product $product)
    {
        return view('admin.products.product_edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name'        => 'required|string',
            'description' => 'required',
            'price'       => 'required|numeric',
            'category'    => 'required|string',
            'image'       => 'nullable|image|max:2048',
        ]);

        $product->update($request->except('image'));

        if ($request->hasFile('image')) {
            if ($product->image_path) {
                Storage::disk('public')->delete($product->image_path);
            }
            $product->image_path = $request->file('image')->store('products', 'public');
        }

        $product->save();

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Proizvod izmenjen.');
    }

    public function destroy(Product $product)
    {
        if ($product->image_path) {
            Storage::disk('public')->delete($product->image_path);
        }

        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Proizvod obrisan.');
    }

    /**
     * ==================================================
     * TOGGLE DOSTUPNOSTI PROIZVODA PO LOKALU
     * ==================================================
     */
    public function toggleAvailability(Product $product)
    {
        $restaurantId = session('restaurant_id');

        if (!$restaurantId) {
            return response()->json([
                'error' => 'Restaurant not selected'
            ], 400);
        }

        $status = RestaurantProductStatus::firstOrCreate(
            [
                'restaurant_id' => $restaurantId,
                'product_id'    => $product->id,
            ],
            [
                'is_available' => 1
            ]
        );

        // toggle status
        $status->is_available = ! $status->is_available;
        $status->save();

        return response()->json([
            'is_available' => (bool) $status->is_available
        ]);
    }
}
