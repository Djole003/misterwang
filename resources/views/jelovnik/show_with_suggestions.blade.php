@extends('layouts.app')

@include('partials.header')

@section('content')
@php
$orderType = session('order_type', 'delivery');


$originalPrice = $orderType === 'takeaway'
    ? $jelo->price_takeaway
    : $jelo->price_delivery;

$discountedPrice = $jelo->price;

$isPice = ($jelo->category->slug == 'pice');


@endphp

<div class="custom-detail-wrapper container my-5">
    <div class="row">


    <!-- Glavno jelo -->
    <div class="col-md-6">
        <div class="main-dish-box">

            <img src="{{ asset($jelo->image_path) }}"
                 alt="{{ $jelo->name }}"
                 class="main-dish-img">

            <h1 class="main-dish-title">
                {{ $jelo->name }}
            </h1>

            <p class="main-dish-desc">
                {{ $jelo->description }}
            </p>

            <div class="mb-3">
                @if(!$isPice)
                    <span style="text-decoration: line-through;">
                        {{ number_format($originalPrice, 0) }} RSD
                    </span><br>

                    <span style="color:red; font-weight:bold;">
                        {{ number_format($discountedPrice, 0) }} RSD
                    </span>
                @else
                    <span>
                        {{ number_format($originalPrice, 0) }} RSD
                    </span>
                @endif
            </div>

            <button type="button"
                    class="btn btn-success btn-lg order-btn"
                    data-bs-toggle="modal"
                    data-bs-target="#addToCartModal"
                    data-id="{{ $jelo->id }}"
                    data-name="{{ $jelo->name }}"
                    data-price="{{ $jelo->price }}"
                    data-has-size="{{ $jelo->has_size }}"
                    data-has-sos="{{ $jelo->has_sos }}"
                    data-has-meat="{{ $jelo->has_meat }}"
                    data-has-rice="{{ $jelo->has_rice_option }}"
                    data-addons='@json($jelo->category->addOns ?? [])'>

                🛒 Poruči odmah
            </button>

        </div>
    </div>

    <!-- Preporuke -->
    <div class="col-md-6">
        <h4>Preporučena pića</h4>

        <div class="row">
            @foreach($pice as $p)
                <div class="col-6 mb-2">
                    <a href="{{ route('dish.showWithSuggestions', $p->id) }}">
                        {{ $p->name }}
                    </a>
                </div>
            @endforeach
        </div>

        <h4 class="mt-3">Preporučeni dezerti</h4>

        <div class="row">
            @foreach($dezerti as $d)
                <div class="col-6 mb-2">
                    <a href="{{ route('dish.showWithSuggestions', $d->id) }}">
                        {{ $d->name }}
                    </a>
                </div>
            @endforeach
        </div>
    </div>

</div>


</div>

@include('partials.addToCartModal')

<script>
document.addEventListener('DOMContentLoaded', function () {

    const productIdInput = document.getElementById('modalProductId');
    const basePriceInput = document.getElementById('productBasePrice');

    const totalPriceEl = document.getElementById('totalPrice');
    const quantityInput = document.getElementById('productQuantity');

    const sizeSelect = document.getElementById('productSize');

    const sizeSection = document.getElementById('sizeSection');
    const sosSection = document.getElementById('sosSection');
    const meatSection = document.getElementById('meatSection');
    const riceSection = document.getElementById('riceSection');
    const addonsSection = document.getElementById('addonsSection');
    const addonsContainer = document.getElementById('addonsContainer');

    document.querySelectorAll('.order-btn').forEach(button => {

        button.addEventListener('click', function () {

            productIdInput.value = this.dataset.id;
            basePriceInput.value = parseFloat(this.dataset.price);

            const config = {
                hasSize: this.dataset.hasSize === "1",
                hasSos: this.dataset.hasSos === "1",
                hasMeat: this.dataset.hasMeat === "1",
                hasRice: this.dataset.hasRice === "1"
            };

            sizeSection.style.display = config.hasSize ? 'block' : 'none';
            sosSection.style.display = config.hasSos ? 'block' : 'none';
            meatSection.style.display = config.hasMeat ? 'block' : 'none';
            riceSection.style.display = config.hasRice ? 'block' : 'none';

            addonsContainer.innerHTML = '';

            const addons = JSON.parse(this.dataset.addons || '[]');

            if (addons.length > 0) {
                addonsSection.style.display = 'block';

                addons.forEach(addon => {
                    addonsContainer.innerHTML += `
                        <div class="form-check">
                            <input type="checkbox"
                                   class="form-check-input addon-checkbox"
                                   data-price="${addon.price}">
                            ${addon.name} (+${addon.price})
                        </div>
                    `;
                });

            } else {
                addonsSection.style.display = 'none';
            }

            recalcPrice();
        });
    });

    function recalcPrice() {
        let base = parseFloat(basePriceInput.value) || 0;

        if (sizeSelect && sizeSelect.value === 'velika') {
            base += 200;
        }

        document.querySelectorAll('.addon-checkbox:checked').forEach(cb => {
            base += parseFloat(cb.dataset.price);
        });

        const qty = parseInt(quantityInput.value) || 1;

        totalPriceEl.innerText = (base * qty).toFixed(0);
    }

    document.addEventListener('change', function(e){
        if(e.target.classList.contains('addon-checkbox') || e.target.id === 'productSize') {
            recalcPrice();
        }
    });

    quantityInput.addEventListener('input', recalcPrice);

});
</script>

@include('partials.footer')
@endsection
