@extends('layouts.app')
@include('partials.header')

@section('content')

<div class="products-container mt-5"
     style="padding: 2rem; background: linear-gradient(135deg, #fff3e0, #ffe0b2); border-radius: 12px;">

    <div class="mb-3 text-start">
        <a href="{{ route('jelovnik') }}"
           class="btn btn-outline-danger btn-sm">
            ← Nazad na jelovnik
        </a>
    </div>

    <h1 class="section-title mb-4 text-center"
        style="color:black; font-weight:700;">
        {{ $category->name }} – Mister Wang
    </h1>

    <div class="row g-3 justify-content-center">

        @foreach($products as $product)

            @if(!$product->isAvailableForCurrentRestaurant())
                @continue
            @endif

            @php
                $orderType = session('order_type', 'delivery');

                if ($product->pivot && isset($product->pivot->price_delivery)) {
                    $oldPrice = $orderType === 'takeaway'
                        ? $product->pivot->price_takeaway
                        : $product->pivot->price_delivery;
                } else {
                    $oldPrice = $orderType === 'takeaway'
                        ? $product->price_takeaway
                        : $product->price_delivery;
                }

                $newPrice = $product->price;
                $isDiscounted = $oldPrice != $newPrice;
                $isDrink = $product->category->slug === 'pice';
            @endphp

            <div class="col-6 col-sm-4 col-md-3 col-lg-2">

                <div class="card product-card h-100 shadow-sm position-relative"
                     onclick="window.location='{{ route('dish.showWithSuggestions', $product->id) }}'"
                     style="
                        background-color:#fff8f0;
                        border-radius:10px;
                        cursor:pointer;
                        transition:all 0.2s ease;
                        overflow:hidden;
                     "
                     onmouseover="this.style.transform='translateY(-4px)'"
                     onmouseout="this.style.transform='translateY(0)'">

                    @if($isDiscounted)
                        <div style="
                            position:absolute;
                            top:6px;
                            left:6px;
                            background:#e53935;
                            color:white;
                            font-size:0.65rem;
                            padding:3px 7px;
                            border-radius:4px;
                            z-index:2;">
                            -15%
                        </div>
                    @endif

                    <img src="{{ asset($product->image_path) }}"
                         class="card-img-top"
                         alt="{{ $product->name }}"
                         style="height:120px; object-fit:cover;">

                    <div class="card-body text-center p-2 d-flex flex-column justify-content-between">

                        <div>
                            <h6 class="card-title mb-1" style="font-size:0.85rem;">
                                {{ $product->name }}
                            </h6>

                            @if($isDiscounted)
                                <p class="mb-0 text-muted"
                                   style="text-decoration: line-through; font-size:0.75rem;">
                                    {{ number_format($oldPrice, 0) }} RSD
                                </p>

                                <p class="fw-bold text-danger mb-2"
                                   style="font-size:0.9rem;">
                                    {{ number_format($newPrice, 0) }} RSD
                                </p>
                            @else
                                <p class="fw-bold mb-2"
                                   style="font-size:0.9rem;">
                                    {{ number_format($newPrice, 0) }} RSD
                                </p>
                            @endif
                        </div>

                        <button type="button"
                                class="btn btn-sm btn-success order-btn w-100"
                                data-bs-toggle="modal"
                                data-bs-target="#addToCartModal"
                                onclick="event.stopPropagation();"
                                data-id="{{ $product->id }}"
                                data-price="{{ $oldPrice }}"
                                data-is-drink="{{ $isDrink ? 1 : 0 }}"
                                data-has-size="{{ $product->has_size }}"
                                data-has-sos="{{ $product->has_sos }}"
                                data-has-meat="{{ $product->has_meat }}"
                                data-has-rice="{{ $product->has_rice_option }}"
                                data-addons='@json($category->addOns)'
                                style="font-size:0.75rem;">
                            Poruči
                        </button>

                    </div>

                </div>

            </div>

        @endforeach

    </div>
</div>

@include('partials.addToCartModal')
@include('partials.footer')

@endsection


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('addToCartForm');
    const productIdInput = document.getElementById('modalProductId');
    const basePriceInput = document.getElementById('productBasePrice');

    const totalPriceEl = document.getElementById('totalPrice');
    const quantityInput = document.getElementById('productQuantity');
    const sizeSelect = document.getElementById('productSize');
    const sosSelect = document.getElementById('sosSelect');
    const meatSelect = document.getElementById('meatSelect');

    const sizeSection = document.getElementById('sizeSection');
    const sosSection = document.getElementById('sosSection');
    const meatSection = document.getElementById('meatSection');
    const riceSection = document.getElementById('riceSection');
    const addonsSection = document.getElementById('addonsSection');
    const addonsContainer = document.getElementById('addonsContainer');

    let currentConfig = {};

    document.querySelectorAll('.order-btn').forEach(button => {

        button.addEventListener('click', function () {

            productIdInput.value = this.dataset.id;
            basePriceInput.value = parseFloat(this.dataset.price);

            currentConfig = {
                hasSize: this.dataset.hasSize === "1",
                hasSos: this.dataset.hasSos === "1",
                hasMeat: this.dataset.hasMeat === "1",
                hasRice: this.dataset.hasRice === "1"
            };

            sizeSection.style.display = currentConfig.hasSize ? 'block' : 'none';
            sosSection.style.display = currentConfig.hasSos ? 'block' : 'none';
            meatSection.style.display = currentConfig.hasMeat ? 'block' : 'none';
            riceSection.style.display = currentConfig.hasRice ? 'block' : 'none';

            addonsContainer.innerHTML = '';

            const categoryAddons = JSON.parse(this.dataset.addons || '[]');

            if (categoryAddons.length > 0) {
                addonsSection.style.display = 'block';

                categoryAddons.forEach(addon => {
                    const id = "addon_" + addon.id;

                    addonsContainer.innerHTML += `
                        <div class="form-check">
                            <input class="form-check-input addon-checkbox"
                                   type="checkbox"
                                   id="${id}"
                                   name="addons[]"
                                   value="${addon.id}"
                                   data-price="${addon.price}">
                            <label class="form-check-label"
                                   for="${id}"
                                   style="cursor:pointer;">
                                ${addon.name} +${addon.price} RSD
                            </label>
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

        if (currentConfig.hasSize && sizeSelect.value === 'velika') {
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

    form.addEventListener('submit', function(e){

        if (currentConfig.hasSize && !sizeSelect.value) {
            e.preventDefault();
            alert("Izaberite veličinu.");
            return;
        }

        if (currentConfig.hasSos && !sosSelect.value) {
            e.preventDefault();
            alert("Izaberite sos.");
            return;
        }

        if (currentConfig.hasMeat && !meatSelect.value) {
            e.preventDefault();
            alert("Izaberite meso.");
            return;
        }


    });

});
</script>
@endpush    