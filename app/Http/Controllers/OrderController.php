<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Order;
use App\Models\AddOn;
use App\Services\DeliveryZoneService;
use App\Services\FirebasePushService;

class OrderController extends Controller
{
    public function addToCart(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'size'       => 'nullable|in:mala,velika',
            'sos'        => 'nullable|string',
            'meat'       => 'nullable|string',
            'addons'     => 'nullable|array',
            'quantity'   => 'required|integer|min:1',
            'notes'      => 'nullable|string|max:255',
            'mix_rice'   => 'nullable|string',
            'cutlery'    => 'nullable|in:stapici,plasticni,bez',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
            ], 422);
        }

        $product = Product::findOrFail($request->product_id);

        $cart = session('cart', []);
        $cart[] = [
            'product_id' => $product->id,
            'quantity'   => $request->quantity,
            'details'    => [
                'size'     => $request->size,
                'sos'      => $request->sos,
                'meat'     => $request->meat,
                'addons'   => $request->addons ?? [],
                'notes'    => $request->notes,
                'mix_rice' => $request->mix_rice,
                'cutlery'  => $request->cutlery ?? 'bez',
            ],
        ];

        session(['cart' => $cart]);

        return response()->json([
            'success'    => true,
            'cart_count' => count($cart),
        ]);
    }

    public function showCart()
    {
        return view('order.cart', [
            'cart' => session('cart', [])
        ]);
    }

    public function checkout()
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('order.cart');
        }

        $productsTotal = $this->calculateCartProductsTotal($cart);

        return view('order.checkout', [
            'cart'          => $cart,
            'user'          => auth()->user(),
            'productsTotal' => $productsTotal,
        ]);
    }

    public function submitOrder(Request $request)
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->back()->with('error', 'Korpa je prazna.');
        }

        DB::beginTransaction();

        try {
            $orderType = session('order_type', 'delivery');

            $deliveryPrice = 0;
            $deliveryZone  = null;

            if ($orderType === 'delivery') {
                if (empty($request->adresa)) {
                    DB::rollBack();
                    return redirect()->back()->withErrors([
                        'adresa' => 'Adresa je obavezna za dostavu.'
                    ]);
                }

                $coords = $this->getCoordinatesFromAddress($request->adresa);
                if (!$coords) {
                    DB::rollBack();
                    return redirect()->back()->withErrors([
                        'adresa' => 'Nije moguće odrediti lokaciju sa unete adrese.'
                    ]);
                }

                $zoneService = new DeliveryZoneService();
                $zone = $zoneService->getZoneForCoordinates($coords[0], $coords[1]);

                if (!$zone) {
                    DB::rollBack();

                    return redirect()->back()->withErrors([
                        'adresa' => 'Ne vršimo dostavu na ovu adresu.'
                    ]);
                }

                $productsTotal = $this->calculateCartProductsTotal($cart);

                if ($productsTotal < $zone['minimum']) {
                    DB::rollBack();

                    return redirect()->back()->withErrors([
                        'minimum' => 'Minimalni iznos za zonu ' .
                                    $zone['name'] .
                                    ' je ' . $zone['minimum'] . ' RSD'
                    ]);
                }

                // --- GLAVNA IZMJENA ZA DOSTAVU PO LOKALIMA ---

                $restaurantId = session('restaurant_id');

                if ($restaurantId == 1) {
                    $deliveryPrice = $zone['price'];
                } else {
                    $deliveryPrice = 0;
                }

                $deliveryZone = $zone['name'];
            }

            $order = Order::create([
                'user_id'        => auth()->id(),
                'restaurant_id'  => session('restaurant_id'),
                'status'         => 'primljena',
                'order_type'     => $orderType,
                'total_price'    => 0,
                'delivery_zone'  => $deliveryZone,
                'delivery_price' => $deliveryPrice,
                'delivery_info'  => [
                    'ime'      => $request->ime,
                    'telefon'  => $orderType === 'delivery' ? $request->telefon : null,
                    'adresa'   => $orderType === 'delivery' ? $request->adresa : null,
                    'napomena' => $request->napomena,
                ],
            ]);

            foreach ($cart as $item) {
                $order->orderProducts()->create([
                    'product_id' => $item['product_id'],
                    'quantity'   => $item['quantity'],
                    'details'    => $item['details'],
                ]);
            }

            $order->refresh()->load('orderProducts.product');

            $order->update([
                'total_price' => $order->calculateTotalPrice()
            ]);

            DB::commit();

            try {
                FirebasePushService::sendNewOrderNotification($order->id);
            } catch (\Throwable $e) {
                \Log::error('FCM push failed', [
                    'order_id' => $order->id,
                    'error'    => $e->getMessage(),
                ]);
            }

            session()->forget('cart');

            return redirect()->route('order.thankyou');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    private function calculateCartProductsTotal(array $cart): float
    {
        $total = 0;

        foreach ($cart as $item) {
            $product = Product::find($item['product_id']);
            if (!$product) continue;

            $price = $product->price;

            $details = $item['details'] ?? [];

            if (($details['size'] ?? null) === 'velika') {
                $price += 200;
            }

            if (!empty($details['addons'])) {
                $price += AddOn::whereIn('id', $details['addons'])->sum('price');
            }

            $total += $price * $item['quantity'];
        }

        return $total;
    }

    private function getCoordinatesFromAddress($address)
    {
        $query = urlencode($address . ', Beograd, Srbija');
        $url = "https://nominatim.openstreetmap.org/search?q={$query}&format=json&limit=1";

        $context = stream_context_create([
            'http' => [
                'header' => "User-Agent: MisterWangApp/1.0\r\n"
            ]
        ]);

        $response = @file_get_contents($url, false, $context);
        if (!$response) return null;

        $data = json_decode($response, true);
        if (empty($data)) return null;

        return [
            (float) $data[0]['lat'],
            (float) $data[0]['lon']
        ];
    }

    public function checkDeliveryZone(Request $request)
    {
        if (!$request->filled('address')) {
            return response()->json(['success' => false]);
        }

        $coords = $this->getCoordinatesFromAddress($request->address);
        if (!$coords) {
            return response()->json([
                'success' => false,
                'message' => 'Adresa nije pronađena'
            ]);
        }

        $zoneService = new DeliveryZoneService();
        $zone = $zoneService->getZoneForCoordinates($coords[0], $coords[1]);

        if (!$zone) {
            return response()->json([
                'success' => false,
                'message' => 'Ne vršimo dostavu na ovu adresu'
            ]);
        }

        $restaurantId = session('restaurant_id');

        $price = ($restaurantId == 1) ? $zone['price'] : 0;

        return response()->json([
            'success' => true,
            'zone'    => $zone['name'],
            'price'   => $price,
            'minimum' => $zone['minimum']
        ]);
    }

    public function removeFromOrder($index)
    {
        $cart = session()->get('cart', []);

        if (!isset($cart[$index])) {
            return redirect()->route('order.cart')
                ->with('error', 'Stavka ne postoji u korpi.');
        }

        unset($cart[$index]);
        $cart = array_values($cart);

        session()->put('cart', $cart);

        return redirect()->route('order.cart')
            ->with('success', 'Proizvod je uklonjen iz korpe.');
    }

    public function thankyou()
    {
        return view('order.thankyou');
    }
}
