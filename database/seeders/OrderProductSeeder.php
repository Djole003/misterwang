<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\Product;

class OrderProductSeeder extends Seeder
{
    public function run()
    {
        $orders = Order::all();
        $products = Product::all();

        foreach ($orders as $order) {
            // Poveži 1-2 proizvoda sa narudžbinom za test
            $selectedProducts = $products->take(2);

            foreach ($selectedProducts as $product) {
                $order->orderProducts()->create([
                    'product_id' => $product->id,
                    'quantity' => 1,   // jednostavno po 1 komad
                    'details' => null, // opcioni dodaci
                ]);
            }

            // Ne računa se total_price, ostaje 0
        }
    }
}
