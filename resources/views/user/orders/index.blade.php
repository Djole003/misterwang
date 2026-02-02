@extends('layouts.app')
@include('partials.header')

@section('content')
<div class="container my-5 user-orders">

    <h2 class="orders-title">Moje narudžbine</h2>

    @php
        $activeOrders = $orders->whereIn('status', ['primljena', 'u_pripremi', 'dostavlja_se']);
        $historyOrders = $orders->whereNotIn('status', ['primljena', 'u_pripremi', 'dostavlja_se']);

        $totalOrders = $orders->count();
        $totalSpent = $orders->sum('total_price');
    @endphp


    {{-- ================= AKTIVNE NARUDŽBINE ================= --}}
    <div class="active-orders-section mb-4">

        <div class="section-header">
            Trenutne narudžbine
        </div>

        @if($activeOrders->isEmpty())
            <div class="alert alert-info text-center">
                Trenutno nemate aktivnih narudžbina.
            </div>
        @else
            @foreach($activeOrders as $order)
                <div class="order-card active">

                    <div class="order-header">
                        <span class="order-id">#{{ $order->id }}</span>

                        <div class="d-flex gap-2 align-items-center">
                            <span class="order-type {{ $order->order_type }}">
                                {{ $order->order_type === 'delivery' ? '🚚 Dostava' : '🏪 Preuzimanje' }}
                            </span>

                            <span class="order-status status-{{ $order->status }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                    </div>

                    <div class="order-meta">
                        <span><strong>Datum:</strong> {{ $order->created_at->format('d.m.Y H:i') }}</span>
                        <span><strong>Ukupno:</strong> {{ number_format($order->total_price, 2) }} RSD</span>
                    </div>

                </div>
            @endforeach
        @endif
    </div>


    {{-- ================= STATISTIKA ================= --}}
    <div class="stats-section mb-5">

        <div class="section-header dark">
            📊 Statistika naloga
        </div>

        <div class="row g-3">

            <div class="col-12 col-md-6">
                <div class="stats-card">
                    <h5>Ukupno porudžbina</h5>
                    <div class="stats-value">
                        {{ $totalOrders }}
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6">
                <div class="stats-card">
                    <h5>Ukupno potrošeno</h5>
                    <div class="stats-value">
                        {{ number_format($totalSpent, 2) }} RSD
                    </div>
                </div>
            </div>

        </div>
    </div>


    {{-- ================= ISTORIJA NARUDŽBINA ================= --}}
    <div class="history-orders-section">

        <div class="section-header dark">
            🕑 Istorija porudžbina
        </div>

        @if($historyOrders->isEmpty())
            <p class="orders-empty">Još uvek nemate prethodnih porudžbina.</p>
        @else
            @foreach($historyOrders as $order)

                <div class="order-card">

                    <div class="order-header">
                        <span class="order-id">#{{ $order->id }}</span>

                        <div class="d-flex gap-2 align-items-center">
                            <span class="order-type {{ $order->order_type }}">
                                {{ $order->order_type === 'delivery' ? '🚚 Dostava' : '🏪 Preuzimanje' }}
                            </span>

                            <span class="order-status status-{{ $order->status }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                    </div>

                    <div class="order-meta">
                        <span><strong>Datum:</strong> {{ $order->created_at->format('d.m.Y H:i') }}</span>
                        <span><strong>Ukupno:</strong> {{ number_format($order->total_price, 2) }} RSD</span>
                    </div>

                    <div class="order-products">

                        <div class="order-actions">
                            <form method="POST" action="{{ route('orders.repeat', $order->id) }}">
                                @csrf
                                <button type="submit" class="btn btn-outline-primary btn-sm">
                                    🔁 Ponovi porudžbinu
                                </button>
                            </form>
                        </div>

                        <p class="products-title">Proizvodi:</p>

                        <ul class="products-list">
                            @foreach($order->orderProducts as $item)
                                <li class="product-item">
                                    {{ $item->product->name ?? 'Nepoznat proizvod' }}
                                    × {{ $item->quantity }}
                                </li>
                            @endforeach
                        </ul>

                    </div>

                </div>

            @endforeach
        @endif

    </div>

</div>


{{-- ================= STILOVI ================= --}}
<style>

.user-orders {
    max-width: 900px;
}

.orders-title {
    font-weight: 800;
    margin-bottom: 25px;
    text-align: center;
    color: #333;
}

/* ==== NASLOVI SEKCIJA ==== */

.section-header {
    background: #e9ecef;
    color: #333;
    padding: 12px 16px;
    border-radius: 10px;
    font-weight: 700;
    margin-bottom: 15px;
    font-size: 1.1rem;
}

.section-header.dark {
    background: #343a40;
    color: white;
}

/* ==== KARTICE PORUDŽBINA ==== */

.order-card {
    background: #ffffff;
    border-radius: 14px;
    padding: 18px 20px;
    margin-bottom: 20px;
    box-shadow: 0 8px 22px rgba(0,0,0,0.08);
}

.order-card.active {
    border-left: 6px solid #17a2b8;
}

.order-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
}

.order-id {
    font-weight: 700;
    font-size: 1.1rem;
}

/* ==== STATUS ==== */

.order-status {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
}

.status-primljena {
    background: #fff3cd;
    color: #856404;
}

.status-u_pripremi {
    background: #d1ecf1;
    color: #0c5460;
}

.status-dostavlja_se {
    background: #cce5ff;
    color: #004085;
}

.status-zavrsena {
    background: #d4edda;
    color: #155724;
}

.status-odbijena {
    background: #f8d7da;
    color: #721c24;
}

/* ==== TIP ==== */

.order-type {
    padding: 4px 10px;
    border-radius: 16px;
    font-size: 0.75rem;
    font-weight: 600;
}

.order-type.delivery {
    background: #e3f2fd;
    color: #0d47a1;
}

.order-type.takeaway {
    background: #ede7f6;
    color: #4527a0;
}

.order-meta {
    display: flex;
    justify-content: space-between;
    font-size: 0.9rem;
    color: #444;
    margin-bottom: 15px;
}

/* ==== STATISTIKA ==== */

.stats-card {
    background: #ffffff;
    border-radius: 12px;
    padding: 20px;
    text-align: center;
    border: 1px solid #dee2e6;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
}

.stats-card h5 {
    color: #555;
    font-weight: 600;
}

.stats-value {
    font-size: 1.8rem;
    font-weight: 800;
    margin-top: 10px;
    color: #212529;
}

/* ==== PROIZVODI ==== */

.products-list {
    list-style: none;
    padding: 0;
}

.product-item {
    padding: 6px 0;
    border-bottom: 1px dashed #ddd;
}

.order-actions {
    text-align: right;
    margin-bottom: 10px;
}

.orders-empty {
    text-align: center;
    color: #777;
    margin-top: 20px;
}

</style>

@endsection
