<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\AddOn;

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
                'dostavlja_se',
                'rejected'
            ]);

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

        if ($request->filled('from') && $request->filled('to')) {
            $query->whereBetween('created_at', [
                Carbon::parse($request->from)->startOfDay(),
                Carbon::parse($request->to)->endOfDay(),
            ]);
        }

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
     * DETALJI NARUDŽBINE (MODAL)
     * ============================
     */
    public function show($id)
    {
        $query = Order::with('orderProducts.product');

        // 🔒 ADMIN vidi samo svoj restoran
        if (auth()->user()->role === 'admin') {
            $query->where('restaurant_id', auth()->user()->restaurant_id);
        }

        $order = $query->findOrFail($id);

        $html = "
            <h5 class='mb-3'>🧾 Narudžbina #{$order->id}</h5>

            <div class='mb-2'><b>Kupac:</b> " . ($order->name ?? '-') . "</div>
            <div class='mb-2'><b>Status:</b> {$order->status}</div>
            <div class='mb-2'><b>Tip:</b> {$order->order_type}</div>
            <div class='mb-2'><b>Ukupno:</b> " . number_format($order->total_price) . " RSD</div>

            <hr>

            <h6 class='mb-3'>🍔 Proizvodi:</h6>
            <ul class='list-group'>
        ";

        foreach ($order->orderProducts as $item) {

            $naziv = $item->product->naziv 
                ?? $item->product->name 
                ?? 'Proizvod';

            
            $details = $item->details ?? [];

            if (!is_array($details)) {
                $details = json_decode($details, true) ?? [];
            }

            // 🧠 priprema teksta
            $size   = !empty($details['size']) ? "📏 {$details['size']}" : '';
            $sos    = !empty($details['sos']) ? "🥫 {$details['sos']}" : '';
            $meat   = !empty($details['meat']) ? "🍗 {$details['meat']}" : '';
            $addonNames = [];

            if (!empty($details['addons']) && is_array($details['addons'])) {

                $addonNames = AddOn::whereIn('id', $details['addons'])
                    ->pluck('name') // ili 'naziv' ako ti je tako u bazi
                    ->toArray();
            }

            $addons = !empty($addonNames)
                ? "➕ " . implode(', ', $addonNames)
                : '';

            $html .= "
                <li class='list-group-item'>
                    <div class='d-flex justify-content-between'>
                        <strong>{$naziv}</strong>
                        <span>x {$item->quantity}</span>
                    </div>

                    <div class='text-muted small mt-1'>
                        " . ($size ? $size . "<br>" : "") . "
                        " . ($sos ? $sos . "<br>" : "") . "
                        " . ($meat ? $meat . "<br>" : "") . "
                        " . ($addons ? $addons : "") . "
                    </div>
                </li>
            ";
        }

        $html .= "</ul>";

        return $html;
    }

    /**
     * ============================
     * PRIHVATANJE NARUDŽBINE
     * ============================
     */
    public function accept(Request $request, Order $order)
    {
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
     * ODBIJANJE NARUDŽBINE
     * ============================
     */
    public function reject(Request $request, Order $order)
    {
        // 🔒 Zaštita: admin može odbiti samo svoj restoran
        if (
            auth()->user()->role === 'admin' &&
            $order->restaurant_id !== auth()->user()->restaurant_id
        ) {
            abort(403);
        }

        // Ne može se odbiti ako je već obrađena
        if (!in_array($order->status, ['primljena', 'u_pripremi'])) {
            return back()->with('error', 'Ovu porudžbinu više nije moguće odbiti.');
        }

        $request->validate([
            'reason' => 'required|string'
        ]);

        $reason = $request->reason;

        if ($request->filled('custom_reason')) {
            $reason .= ' - ' . $request->custom_reason;
        }

        $order->status = 'rejected';
        $order->rejection_reason = $reason;
        $order->save();

        return back()->with('success', 'Porudžbina je uspešno odbijena.');
    }

    /**
     * ============================
     * SPREMNO DUGME
     * ============================
     */
    public function ready(Order $order)
    {
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

        if ($order->order_type === 'takeaway') {
            $order->status = 'zavrsena';
            $order->save();
        }

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

        if (auth()->user()->role === 'admin') {
            $query->where('restaurant_id', auth()->user()->restaurant_id);
        }

        $query->update([
            'status' => 'zavrsena',
            'updated_at' => now(),
        ]);
    }
}
