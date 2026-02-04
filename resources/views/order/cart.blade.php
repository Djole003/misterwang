@extends('layouts.app')
@include('partials.header')

@section('content')

<style>
/* ------- GLOBAL CART WRAPPER ------- */
.cart-wrapper {
    width: 100%;
    padding: 20px 10px;
    background: #f5f5f5;
}

.cart-container {
    max-width: 1100px;
    margin: 0 auto;
    background: white;
    padding: 25px;
    border-radius: 16px;
    box-shadow: 0 4px 25px rgba(0,0,0,0.08);
    animation: fadeIn 0.5s ease;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to   { opacity: 1; transform: translateY(0); }
}

.cart-title {
    text-align: center;
    font-size: 32px;
    font-weight: 700;
    margin-bottom: 25px;
}

/* ------- TABLE DESKTOP ------- */
.cart-table {
    width: 100%;
    border-collapse: collapse;
}

.cart-table th {
    background: #fafafa;
    border-bottom: 2px solid #eee;
    padding: 14px 8px;
    font-size: 16px;
    font-weight: 700;
}

.cart-table td {
    padding: 14px 8px;
    border-bottom: 1px solid #f1f1f1;
    vertical-align: middle;
    animation: fadeIn 0.4s ease;
}

/* ------- PRODUCT IMAGE ------- */
.cart-item-row img {
    width: 55px;
    height: 55px;
    border-radius: 12px;
    object-fit: cover;
    margin-right: 10px;
    transition: transform 0.2s ease;
}

.cart-item-row img:hover {
    transform: scale(1.05);
}

/* ------- REMOVE BUTTON ------- */
.btn-remove {
    background: #ff4d4d;
    color: white;
    border: none;
    padding: 6px 12px;
    border-radius: 8px;
    font-size: 14px;
    cursor: pointer;
    transition: 0.2s;
}

.btn-remove:hover {
    background: #e60000;
    transform: scale(1.05);
}

/* ------- CONFIRM BUTTON ------- */
.btn-confirm {
    width: 100%;
    background: #27ae60;
    color: white;
    border: none;
    font-size: 18px;
    padding: 14px;
    border-radius: 12px;
    font-weight: 600;
    margin-top: 25px;
    cursor: pointer;
    transition: 0.2s;
}

.btn-confirm:hover {
    background: #1e8c4d;
    transform: scale(1.01);
}

/* ------- MOBILE ------- */
@media (max-width: 768px) {

    .cart-table thead {
        display: none;
    }

    .cart-table, .cart-table tbody, .cart-table tr, .cart-table td {
        display: block;
        width: 100%;
    }

    .cart-table tr {
        background: #ffffff;
        border-radius: 16px;
        margin-bottom: 18px;
        padding: 14px;
        box-shadow: 0 3px 12px rgba(0,0,0,0.06);
        animation: fadeIn 0.5s ease;
    }

    .cart-table td {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: none;
        font-size: 15px;
    }

    .cart-table td::before {
        content: attr(data-label);
        font-weight: 700;
        color: #666;
    }

    .product-name {
        flex-direction: column;
        text-align: center;
        justify-content: center;
        gap: 8px;
    }

    .product-name img {
        margin: 0 auto;
    }

    .cart-table-foot {
        text-align: center;
        margin-top: 20px;
    }

    .total-price {
        font-size: 22px;
        font-weight: 700;
    }
}
</style>

<div class="cart-wrapper">
    <div class="cart-container">

        <h2 class="cart-title">Vaša korpa</h2>

        {{-- Prikaz tipa porudžbine --}}
        @php
            $orderType = session('order_type', 'delivery');
        @endphp
        <p style="text-align:center; font-size:16px; margin-bottom:20px;">
            Tip porudžbine: <strong>{{ $orderType === 'delivery' ? 'Dostava' : 'Preuzimanje' }}</strong>
        </p>

        @if(session('cart') && count(session('cart')) > 0)
            @php $totalPrice = 0; @endphp
            <table class="cart-table">
                <thead class="cart-table-head">
                    <tr>
                        <th>Proizvod</th>
                        <th>Veličina</th>
                        <th>Sos</th>
                        <th>Dodaci</th>
                        <th>Količina</th>
                        <th>Cena</th>
                        <th>Mešanje pirinča</th>
                        <th>Akcija</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach(session('cart') as $index => $item)
                        @php
                            $product = \App\Models\Product::find($item['product_id']);
                            $details = $item['details'] ?? [];

                            // Dodaci
                            $addonsIds = $details['addons'] ?? [];
                            $addons = \App\Models\AddOn::whereIn('id', $addonsIds)->pluck('name')->toArray();

                            $basePrice = $product->price;

                            // Velika porcija
                            if(($details['size'] ?? null) === 'velika') {
                                $basePrice += 200;
                            }

                            // Cena dodataka
                            $addonsPrice = 0;
                            if(!empty($addonsIds)) {
                                $addonsPrice = array_sum(\App\Models\AddOn::whereIn('id', $addonsIds)->pluck('price')->toArray());
                            }

                            $cenaPoKomadu = $basePrice + $addonsPrice;
                            $ukupnaCena = $cenaPoKomadu * $item['quantity'];
                            $totalPrice += $ukupnaCena;
                        @endphp

                        <tr class="cart-item-row">
                            <td data-label="Proizvod" class="product-name d-flex align-items-center">
                                @if($product && $product->image_path)
                                    <img src="{{ asset($product->image_path) }}" alt="{{ $product->name }}">
                                @endif
                                <span>{{ $product->name ?? 'Nepoznat proizvod' }}</span>
                            </td>

                            <td data-label="Veličina">{{ ucfirst($details['size'] ?? '-') }}</td>
                            <td data-label="Sos">{{ $details['sos'] ?? '-' }}</td>
                            <td data-label="Dodaci">{{ !empty($addons) ? implode(', ', $addons) : '-' }}</td>
                            <td data-label="Količina">{{ $item['quantity'] }}</td>
                            <td data-label="Cena">{{ $ukupnaCena }} RSD</td>
                            <td data-label="Mešanje pirinča">{{ $details['mix_rice'] ?? '-' }}</td>
                            <td data-label="Akcija">
                                <form action="{{ route('order.remove', $index) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn-remove">Ukloni</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach

                </tbody>

                <tfoot class="cart-table-foot">
                    <tr>
                        <th colspan="5" class="total-label">Ukupno:</th>
                        <th colspan="2" class="total-price">{{ $totalPrice }} RSD</th>
                    </tr>
                </tfoot>
            </table>

            <a href="{{ route('order.checkout') }}" class="btn-confirm">Nastavi na poručivanje</a>

        @else
            <p class="empty-cart-text" style="text-align:center; font-size:20px; padding:20px;">
                Vaša korpa je prazna.
            </p>
        @endif

    </div>
</div>

@endsection
