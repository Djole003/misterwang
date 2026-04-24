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

    <!-- LEVO: GLAVNO JELO -->
    <div class="col-md-6">
        <div class="main-dish-box">

            <img src="{{ asset($jelo->image_path) }}"
                 class="img-fluid mb-3 rounded">

            <h1>{{ $jelo->name }}</h1>

            <p>{{ $jelo->description }}</p>

            <div class="mb-3">
                @if(!$isPice)
                    <span style="text-decoration: line-through;">
                        {{ number_format($originalPrice, 0) }} RSD
                    </span><br>

                    <span style="color:red; font-weight:bold;">
                        {{ number_format($discountedPrice, 0) }} RSD
                    </span>
                @else
                    <span>{{ number_format($originalPrice, 0) }} RSD</span>
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

    <!-- DESNO: PREPORUKE -->
    <div class="col-md-6">

        <h4>Preporučena pića</h4>

        <div class="row">
            @foreach($pice as $p)
                <div class="col-6 col-md-4 mb-3">
                    <div class="card shadow-sm h-100">

                        <a href="{{ route('dish.showWithSuggestions', $p->id) }}"
                           class="text-decoration-none text-dark">

                            <img src="{{ asset($p->image_path) }}"
                                 class="card-img-top"
                                 style="height:100px; object-fit:cover;">
                        </a>

                        <div class="card-body p-2 text-center">

                            <small>{{ $p->name }}</small><br>

                            <strong>
                                {{ number_format($p->price, 0) }} RSD
                            </strong>

                            <form action="{{ route('cart.add') }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $p->id }}">
                                <input type="hidden" name="quantity" value="1">

                                <button type="submit"
                                        class="btn btn-success btn-sm w-100 mt-2">
                                    Dodaj
                                </button>
                            </form>

                        </div>

                    </div>
                </div>
            @endforeach
        </div>

        <h4 class="mt-4">Preporučeni dezerti</h4>

        <div class="row">
            @foreach($dezerti as $d)
                <div class="col-6 col-md-4 mb-3">
                    <div class="card shadow-sm h-100">

                        <a href="{{ route('dish.showWithSuggestions', $d->id) }}"
                           class="text-decoration-none text-dark">

                            <img src="{{ asset($d->image_path) }}"
                                 class="card-img-top"
                                 style="height:100px; object-fit:cover;">
                        </a>

                        <div class="card-body p-2 text-center">

                            <small>{{ $d->name }}</small><br>

                            <strong>
                                {{ number_format($d->price, 0) }} RSD
                            </strong>

                            <button type="button"
                                    class="btn btn-success btn-sm w-100 mt-2 order-btn"
                                    data-bs-toggle="modal"
                                    data-bs-target="#addToCartModal"
                                    data-id="{{ $d->id }}"
                                    data-name="{{ $d->name }}"
                                    data-price="{{ $d->price }}"
                                    data-has-size="{{ $d->has_size }}"
                                    data-has-sos="{{ $d->has_sos }}"
                                    data-has-meat="{{ $d->has_meat }}"
                                    data-has-rice="{{ $d->has_rice_option }}"
                                    data-addons='@json($d->category->addOns ?? [])'>

                                Dodaj
                            </button>

                        </div>

                    </div>
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
