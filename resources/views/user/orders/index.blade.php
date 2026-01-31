@extends('layouts.app')
@include('partials.header')

@section('content')
<div class="container my-5 user-orders">

    <h2 class="orders-title">Moje narudžbine</h2>

    @if($orders->isEmpty())
        <p class="orders-empty">Još uvek nemate porudžbina.</p>
    @else
        @foreach($orders as $order)
            <div class="order-card">

                <div class="order-header">
                    <span class="order-id">#{{ $order->id }}</span>

                    <div class="d-flex gap-2 align-items-center">
                        {{-- TIP PORUDŽBINE --}}
                        <span class="order-type {{ $order->order_type }}">
                            {{ $order->order_type === 'delivery' ? '🚚 Dostava' : '🏪 Preuzimanje' }}
                        </span>

                        {{-- STATUS --}}
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

                    @if($order->orderProducts && $order->orderProducts->count())
                        <ul class="products-list">
                            @foreach($order->orderProducts as $item)
                                <li class="product-item">
                                    <div class="product-name">
                                        {{ $item->product->name ?? 'Nepoznat proizvod' }}
                                    </div>

                                    <div class="product-details">
                                        @php $details = $item->details ?? []; @endphp

                                        @if(!empty($details['size']))
                                            <span>Vel: {{ $details['size'] }}</span>
                                        @endif

                                        @if(!empty($details['sos']))
                                            <span>Sos: {{ $details['sos'] }}</span>
                                        @endif

                                        @if(!empty($details['addons']))
                                            <span>
                                                Dodaci:
                                                {{ collect($details['addons'])
                                                    ->map(fn($id) => $addonsMap[(int)$id] ?? 'Nepoznat dodatak')
                                                    ->implode(', ') }}
                                            </span>
                                        @endif

                                        <span class="product-qty">
                                            Količina: {{ $item->quantity }}
                                        </span>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted">Nema stavki u ovoj porudžbini.</p>
                    @endif
                </div>

            </div>
        @endforeach
    @endif
</div>

{{-- ================= STILOVI ================= --}}
<style>
.user-orders {
    max-width: 900px;
}

.orders-title {
    font-weight: 700;
    margin-bottom: 25px;
    text-align: center;
}

.orders-empty {
    text-align: center;
    color: #777;
}

.order-card {
    background: #ffffff;
    border-radius: 14px;
    padding: 18px 20px;
    margin-bottom: 20px;
    box-shadow: 0 8px 22px rgba(0,0,0,0.08);
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

.status-zavrsena {
    background: #d4edda;
    color: #155724;
}

.status-otkazana {
    background: #f8d7da;
    color: #721c24;
}

/* 🔹 TIP PORUDŽBINE */
.order-type {
    padding: 4px 10px;
    border-radius: 16px;
    font-size: 0.75rem;
    font-weight: 600;
    white-space: nowrap;
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

.order-products {
    border-top: 1px solid #eee;
    padding-top: 12px;
}

.products-title {
    font-weight: 600;
    margin-bottom: 8px;
}

.products-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.product-item {
    padding: 10px 0;
    border-bottom: 1px dashed #e0e0e0;
}

.product-item:last-child {
    border-bottom: none;
}

.product-name {
    font-weight: 600;
    margin-bottom: 4px;
}

.product-details {
    font-size: 0.85rem;
    color: #555;
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}

.product-qty {
    font-weight: 600;
}

.order-actions {
    margin-top: 15px;
    text-align: right;
}
</style>
@endsection
