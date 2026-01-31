<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminOrderController extends Controller
{
    /**
     * ============================
     * BASE QUERY (PROFI FILTRIRANJE)
     * ============================
     */
    private function baseOrderQuery()
    {
        $query = Order::with('orderProducts.product');

        // ADMIN → samo svoj restoran
        if (auth()->user()->role === 'admin') {
            $query->where('restaurant_id', auth()->user()->restaurant_id);
        }

        // EDITOR → vidi sve (bez filtera)
        return $query;
    }

    /**
     * ============================
     * LIVE NARUDŽBINE (KUHINJA)
     * /admin/orders
     * ============================
     */
    public function index()
    {
        $waitingOrders = $this->baseOrderQuery()
            ->where('status', 'primljena')
            ->orderBy('created_at', 'asc')
            ->get();

        $preparingOrders = $this->baseOrderQuery()
            ->where('status', 'u_pripremi')
            ->orderBy('ready_at', 'asc')
            ->get();

        return view('admin.orders.index', compact(
            'waitingOrders',
            'preparingOrders'
        ));
    }

    /**
     * ============================
     * PREGLED / ISTORIJA
     * /admin/orders/history
     * ============================
     */
    public function history(Request $request)
    {
        $query = $this->baseOrderQuery()
            ->whereIn('status', [
                'zavrsena',
                'dostavlja_se'
            ]);

        // 📅 PERIODI
        switch ($request->period) {
            case 'today':
                $query->whereDate('created_at', Carbon::today());
                break;

            case 'yesterday':
                $query->whereDate('created_at', Carbon::yesterday());
                break;

            case '7days':
                $query->where('created_at', '>=', Carbon::now()->subDays(7));
                break;

            case '30days':
                $query->where('created_at', '>=', Carbon::now()->subDays(30));
                break;
        }

        // 📅 OD–DO
        if ($request->filled('from') && $request->filled('to')) {
            $query->whereBetween('created_at', [
                Carbon::parse($request->from)->startOfDay(),
                Carbon::parse($request->to)->endOfDay(),
            ]);
        }

        // 💰 UKUPAN PAZAR
        $totalRevenue = (clone $query)->sum('total_price');

        $orders = $query
            ->orderBy('created_at', 'desc')
            ->paginate(20)
            ->withQueryString();

        return view('admin.orders.history', compact(
            'orders',
            'totalRevenue'
        ));
    }

    /**
     * ============================
     * PRIHVATANJE NARUDŽBINE
     * ============================
     */
    public function accept(Request $request, Order $order)
    {
        // 🔒 ZAŠTITA: admin ne sme tuđu porudžbinu
        if (
            auth()->user()->role === 'admin' &&
            $order->restaurant_id !== auth()->user()->restaurant_id
        ) {
            abort(403);
        }

        if ($order->status !== 'primljena') {
            return response()->json([
                'error' => 'Narudžbina nije u statusu primljena'
            ], 400);
        }

        $request->validate([
            'minutes' => 'required|integer|min:5|max:60',
        ]);

        $minutes = (int) $request->minutes;

        $order->status = 'u_pripremi';
        $order->preparation_time = $minutes;
        $order->ready_at = now()->addMinutes($minutes);
        $order->save();

        return response()->json([
            'success'  => true,
            'order_id' => $order->id,
            'ready_at' => $order->ready_at->toIso8601String(),
        ]);
    }

    /**
     * ============================
     * SPREMNO DUGME
     * ============================
     */
    public function ready(Order $order)
    {
        // 🔒 ZAŠTITA
        if (
            auth()->user()->role === 'admin' &&
            $order->restaurant_id !== auth()->user()->restaurant_id
        ) {
            abort(403);
        }

        if ($order->status !== 'u_pripremi') {
            return response()->json([
                'error' => 'Narudžbina nije u pripremi'
            ], 400);
        }

        // 🟢 TAKEAWAY
        if ($order->order_type === 'takeaway') {
            $order->status = 'zavrsena';
            $order->save();
        }

        // 🚚 DELIVERY
        if ($order->order_type === 'delivery') {
            $order->status = 'dostavlja_se';
            $order->save();
        }

        return response()->json([
            'success' => true,
            'status'  => $order->status,
        ]);
    }

    /**
     * ============================
     * CRON – ZAVRŠI DOSTAVE
     * ============================
     */
    public function finishOrders()
    {
        $query = Order::where('status', 'dostavlja_se')
            ->whereNotNull('ready_at')
            ->where('ready_at', '<=', now());

        // ADMIN → samo svoj restoran
        if (auth()->user()->role === 'admin') {
            $query->where('restaurant_id', auth()->user()->restaurant_id);
        }

        $query->update([
            'status' => 'zavrsena',
            'updated_at' => now(),
        ]);
    }
}
