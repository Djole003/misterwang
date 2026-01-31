<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\AddOn;

class UserOrderController extends Controller
{
    /**
     * 📦 Prikaz svih narudžbina za prijavljenog korisnika
     */
    public function index()
    {
        $user = Auth::user();

        // Mapiranje dodataka: [id => naziv]
        $addonsMap = AddOn::pluck('name', 'id')->toArray();

        // Učitavanje porudžbina sa stavkama i proizvodima
        $orders = Order::with('orderProducts.product')
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        return view('user.orders.index', compact('orders', 'addonsMap'));
    }

    /**
     * 🔁 Ponovi porudžbinu (kopira stavke u korpu)
     */
    public function repeat(Order $order)
    {
        // 🔐 Sigurnost: korisnik može ponoviti samo SVOJU porudžbinu
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        // 🧹 Očisti postojeću korpu
        session()->forget('cart');

        $cart = [];

        // 📋 Prebaci stavke iz porudžbine u korpu
        foreach ($order->orderProducts as $item) {
            $cart[] = [
                'product_id' => $item->product_id,
                'quantity'   => $item->quantity,
                'details'    => $item->details ?? [],
            ];
        }

        // 🛒 Upis u session
        session(['cart' => $cart]);

        return redirect()
            ->route('order.cart')
            ->with('success', '🔁 Porudžbina je ponovo dodata u korpu.');
    }
}
