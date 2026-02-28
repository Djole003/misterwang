<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Restaurant;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class RestaurantProductSeeder extends Seeder
{
    public function run(): void
    {
        $restaurants = Restaurant::all();
        $products = Product::all();

        foreach ($restaurants as $restaurant) {

            foreach ($products as $product) {

                $priceDelivery = $product->price_delivery;
                $priceTakeaway = $product->price_takeaway;
                $isAvailable = true;

                // Ako je piće
                if ($product->category_id == 8) {

                    // 🟢 MILJAKOVAC
                    if ($restaurant->slug === 'miljakovac') {

                        if ($product->name === 'Voda') {
                            $priceDelivery = 70;
                            $priceTakeaway = 70;
                        }
                        elseif (str_contains($product->name, 'Monster')) {
                            $isAvailable = false;
                        }
                        else {
                            $priceDelivery = 100;
                            $priceTakeaway = 100;
                        }

                    }

                    // 🔵 OSTALI LOKALI
                    else {

                        if ($product->name === 'Voda') {
                            $priceDelivery = 100;
                            $priceTakeaway = 100;
                        }
                        elseif (str_contains($product->name, 'Monster')) {
                            $priceDelivery = 250;
                            $priceTakeaway = 250;
                        }
                        else {
                            $priceDelivery = 150;
                            $priceTakeaway = 150;
                        }

                    }
                }

                DB::table('restaurant_product_status')->insert([
                    'restaurant_id' => $restaurant->id,
                    'product_id' => $product->id,
                    'is_available' => $isAvailable,
                    'price_delivery' => $priceDelivery,
                    'price_takeaway' => $priceTakeaway,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
