@extends('layouts.app')
@include('partials.header')

@section('content')

@php
    $orderType = session('order_type', 'delivery');
@endphp

<style>
.checkout-wrapper {
    width: 100%;
    display: flex;
    justify-content: center;
    padding: 30px 15px;
    background: #f5f5f5;
}
.checkout-container {
    width: 100%;
    max-width: 900px;
    background: white;
    padding: 35px 45px;
    border-radius: 20px;
    box-shadow: 0 10px 35px rgba(0,0,0,0.08);
}
.checkout-title {
    text-align: center;
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 30px;
}
.checkout-form label {
    font-weight: 600;
    margin-bottom: 6px;
    display: block;
}
.checkout-form input,
.checkout-form textarea {
    width: 100%;
    padding: 14px;
    border-radius: 10px;
    border: 1px solid #ccc;
    margin-bottom: 20px;
}
.summary-box {
    padding: 20px;
    border-radius: 15px;
    background: #f9fafb;
    margin-top: 25px;
    border: 1px solid #eee;
}
.summary-item {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
    border-bottom: 1px solid #eee;
}
.grand-total {
    margin-top: 10px;
    font-size: 1.5rem;
    font-weight: 800;
    color: #27ae60;
    text-align: right;
}
.btn-finish {
    width: 100%;
    padding: 16px;
    font-size: 1.3rem;
    font-weight: 700;
    border-radius: 40px;
    background: #27ae60;
    border: none;
    color: white;
    margin-top: 25px;
}
</style>

<div class="checkout-wrapper">
<div class="checkout-container">

<h2 class="checkout-title">
    {{ $orderType === 'delivery' ? 'Detalji za dostavu' : 'Lično preuzimanje' }}
</h2>

<form action="{{ route('order.submit') }}" method="POST" class="checkout-form">
@csrf

<label>Ime i prezime</label>
<input type="text" name="ime" required
       value="{{ old('ime', auth()->user()->name ?? '') }}">

@if($orderType === 'delivery')

<label>Broj telefona</label>
<input type="text" name="telefon" required
       value="{{ old('telefon', auth()->user()->telefon ?? '') }}">

<label>Adresa dostave</label>
<input type="text" name="adresa" id="addressInput" required
       value="{{ old('adresa', auth()->user()->adresa ?? '') }}">

<button type="button" id="confirmAddressBtn"
        class="btn btn-outline-primary w-100 mb-2">
📍 Potvrdi adresu
</button>

<div id="delivery-info" class="alert alert-info d-none">
🚚 Zona: <strong id="zone-name"></strong><br>
Cena dostave: <strong id="delivery-price"></strong> RSD<br>

<div class="mt-2">
    Minimalni iznos za ovu zonu:
    <strong id="zone-minimum"></strong> RSD
</div>

<div id="minimum-warning" class="alert alert-warning mt-2 d-none"></div>

</div>

<div id="delivery-error" class="alert alert-danger d-none"></div>

<input type="hidden" name="delivery_price" id="delivery_price" value="0">

@endif

<label>Napomena (opciono)</label>
<textarea name="napomena" rows="3"></textarea>

<div class="summary-box">
    <div class="summary-item">
        <span>Međuzbir</span>
        <span id="productsTotal">{{ $productsTotal }} RSD</span>
    </div>

    @if($orderType === 'delivery')
    <div class="summary-item d-none" id="deliveryRow">
        <span>Dostava</span>
        <span>+ <span id="deliveryRowPrice">0</span> RSD</span>
    </div>
    @endif

    <div class="grand-total">
        Ukupno za plaćanje:
        <span id="grandTotal">{{ $productsTotal }}</span> RSD
    </div>
</div>

<button type="submit"
        id="submitOrderBtn"
        class="btn-finish"
        {{ $orderType === 'delivery' ? 'disabled' : '' }}>
Potvrdi porudžbinu
</button>

</form>
</div>
</div>

@endsection

@if($orderType === 'delivery')
@section('scripts')
<script>
const confirmBtn = document.getElementById('confirmAddressBtn');
const submitBtn = document.getElementById('submitOrderBtn');
const addressInput = document.getElementById('addressInput');

const zoneBox = document.getElementById('delivery-info');
const zoneName = document.getElementById('zone-name');
const zoneMinimum = document.getElementById('zone-minimum');
const minimumWarning = document.getElementById('minimum-warning');

const deliveryPriceBox = document.getElementById('delivery-price');
const deliveryPriceInput = document.getElementById('delivery_price');
const errorBox = document.getElementById('delivery-error');

const productsTotal = {{ $productsTotal }};
const grandTotalBox = document.getElementById('grandTotal');
const deliveryRow = document.getElementById('deliveryRow');
const deliveryRowPrice = document.getElementById('deliveryRowPrice');

confirmBtn.addEventListener('click', () => {
    fetch("{{ route('delivery.zone.check') }}", {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
            "Content-Type": "application/json",
            "Accept": "application/json"
        },
        body: JSON.stringify({ address: addressInput.value })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {

            zoneBox.classList.remove('d-none');
            errorBox.classList.add('d-none');

            zoneName.innerText = data.zone;
            deliveryPriceBox.innerText = data.price;
            deliveryPriceInput.value = data.price;

            zoneMinimum.innerText = data.minimum;

            deliveryRow.classList.remove('d-none');
            deliveryRowPrice.innerText = data.price;

            const newTotal = productsTotal + parseInt(data.price);
            grandTotalBox.innerText = newTotal;

            if (productsTotal < data.minimum) {
                const diff = data.minimum - productsTotal;

                minimumWarning.classList.remove('d-none');
                minimumWarning.innerText =
                    "Za ovu zonu potrebno je dodati još " + diff +
                    " RSD proizvoda do minimalnog iznosa.";

                submitBtn.disabled = true;
            } else {
                minimumWarning.classList.add('d-none');
                submitBtn.disabled = false;
            }

        } else {
            zoneBox.classList.add('d-none');
            errorBox.classList.remove('d-none');
            errorBox.innerText = data.message;

            deliveryRow.classList.add('d-none');
            grandTotalBox.innerText = productsTotal;
            submitBtn.disabled = true;
        }
    });
});
</script>
@endsection
@endif
