<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statusi = ['na čekanju', 'odobrena', 'otkazana'];

        $users = User::all();

        foreach ($users as $user) {
            // Nasumičan status
            $status = $statusi[array_rand($statusi)];

            // Kreiraj narudžbinu
            $order = Order::create([
                'user_id' => $user->id,
                'status' => $status,
                'total_price' => 0, // Privremena vrednost, biće ažurirana kasnije
            ]);

            // Poveži proizvode sa narudžbinom
            $orderProducts = DB::table('order_product')
                ->where('order_id', $order->id)
                ->get();

            $totalPrice = 0;

            foreach ($orderProducts as $orderProduct) {
                // Nabavi proizvod na osnovu ID-a
                $product = Product::find($orderProduct->product_id);
                
                // Izračunaj ukupnu cenu za taj proizvod
                $totalPrice += $product->price * $orderProduct->quantity;
            }

            // Ažuriraj total_price u narudžbini
            $order->update([
                'total_price' => $totalPrice,
            ]);
        }
    }

}
