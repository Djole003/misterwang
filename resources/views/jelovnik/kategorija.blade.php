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

    <h1 class="section-title mb-2 text-center"
        style="color:black; font-weight:700;">
        {{ $category->name }} – Mister Wang
    </h1>

    <p class="text-center mb-4"
       style="color:#4e342e; font-size:0.95rem;">
        Pogledajte ponudu iz kategorije <strong>{{ $category->name }}</strong>
        u kineskom restoranu Mister Wang. Brza dostava i autentični kineski ukusi.
    </p>

    <div class="row g-3 justify-content-center">

        @foreach($products as $product)

            @if(!$product->isAvailableForCurrentRestaurant())
                @continue
            @endif

            <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                <div class="card product-card h-100 text-center position-relative shadow-sm p-2"
                     style="background-color:#fff8f0; border-radius:10px;">

                    <a href="{{ route('dish.showWithSuggestions', $product->id) }}"
                       class="text-decoration-none text-dark d-block h-100">

                        <img src="{{ asset($product->image_path) }}"
                             class="card-img-top mb-2"
                             alt="{{ $product->name }}"
                             style="height:100px; object-fit:cover; border-radius:6px;">

                        <div class="card-body p-1">
                            <h6 class="card-title mb-1"
                                style="font-size:0.85rem;">
                                {{ $product->name }}
                            </h6>

                            @php
                                $isPice = ($category->slug == 'pice');

                                $originalPrice = session('order_type', 'delivery') === 'takeaway'
                                    ? $product->price_takeaway
                                    : $product->price_delivery;

                                $discountedPrice = $product->price;
                            @endphp

                            <p class="card-text mb-1 fw-bold" style="font-size:0.80rem;">

                                @if(!$isPice)
                                    <span style="text-decoration: line-through; color:#888; font-size:0.75rem;">
                                        {{ number_format($originalPrice, 0) }} RSD
                                    </span>

                                    <br>

                                    <span style="color:#d32f2f; font-size:0.85rem;">
                                        {{ number_format($discountedPrice, 0) }} RSD
                                    </span>
                                @else
                                    <span style="color:#bf360c;">
                                        {{ number_format($originalPrice, 0) }} RSD
                                    </span>
                                @endif

                            </p>

                        </div>
                    </a>

                    @php
                        $hideSize = $hideSos = $hideAddons = $hideMeat = $hideMixRice = $hideCutlery = 0;

                        switch($category->slug){

                            case 'predjela-i-salate':
                            case 'supe':
                            case 'pirinac-i-nudle':
                            case 'dezerti':
                                $hideSize = 1;
                                $hideSos = 1;
                                $hideAddons = 1;
                                $hideMeat = 1;
                                $hideMixRice = 1;
                                $hideCutlery = 1;
                                break;

                            case 'pice':
                                $hideSize = 1;
                                $hideSos = 1;
                                $hideAddons = 1;
                                $hideMeat = 1;
                                $hideMixRice = 1;
                                $hideCutlery = 1;
                                break;

                            case 'morski-plodovi':
                            case 'jela-bez-mesa':
                                $hideSize = 1;
                                $hideMeat = 1;
                                $hideMixRice = 1;
                                break;

                            case 'jela-sa-mesom':
                                if(in_array($product->name, ['Kung pao piletina', 'Kraljevska Piletina'])){
                                    $hideSos = 1;
                                    $hideMeat = 1;
                                }
                                break;

                            case 'akcije':
                                $hideSize = 1;
                                $hideMeat = 1;
                                $hideSos = 0;
                                $hideAddons = 0;
                                $hideMixRice = 1;
                                break;
                        }
                    @endphp

                    <button type="button"
                            class="btn btn-sm btn-success order-btn position-absolute bottom-0 start-50 translate-middle-x mb-2"
                            data-bs-toggle="modal"
                            data-bs-target="#addToCartModal"
                            data-id="{{ $product->id }}"
                            data-name="{{ $product->name }}"
                            data-price="{{ $product->price }}"
                            data-hide-size="{{ $hideSize }}"
                            data-hide-sos="{{ $hideSos }}"
                            data-hide-addons="{{ $hideAddons }}"
                            data-hide-meat="{{ $hideMeat }}"
                            data-hide-mixrice="{{ $hideMixRice }}"
                            data-hide-cutlery="{{ $hideCutlery }}"
                            style="font-size:0.75rem; padding:4px 8px;">
                        Poruči
                    </button>

                </div>
            </div>

        @endforeach
    </div>
</div>

@include('partials.addToCartModal')

<script>
document.addEventListener('DOMContentLoaded', function () {

    const addToCartModal = document.getElementById('addToCartModal');

    document.querySelectorAll('.order-btn').forEach(button => {
        button.addEventListener('click', function () {

            const productId = this.dataset.id;
            const productName = this.dataset.name;
            const productPrice = parseFloat(this.dataset.price);

            document.getElementById('modalProductId').value = productId;
            document.getElementById('productBasePrice').value = productPrice;

            addToCartModal.querySelector('.modal-title').textContent =
                "Dodaj u korpu: " + productName;

            document.getElementById('totalPrice').innerText =
                productPrice.toFixed(0);

            const hideSize = this.dataset.hideSize === "1";
            const hideSos = this.dataset.hideSos === "1";
            const hideAddons = this.dataset.hideAddons === "1";
            const hideMeat = this.dataset.hideMeat === "1";
            const hideMixRice = this.dataset.hideMixrice === "1";
            const hideCutlery = this.dataset.hideCutlery === "1";

            document.getElementById('sizeSection').style.display   = hideSize   ? 'none' : 'block';
            document.getElementById('sosSection').style.display    = hideSos    ? 'none' : 'block';
            document.getElementById('addonsSection').style.display = hideAddons ? 'none' : 'block';
            document.getElementById('meatSection').style.display   = hideMeat   ? 'none' : 'block';

            const mixRiceSection = document.getElementById('mixRiceSection');
            if (mixRiceSection) {
                mixRiceSection.style.display = hideMixRice ? 'none' : 'block';
            }

            const cutlerySection = document.getElementById('cutlerySection');
            if (cutlerySection) {
                cutlerySection.style.display = hideCutlery ? 'none' : 'block';
            }
        });
    });

    const form = document.getElementById('addToCartForm');

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        fetch(form.action, {
            method: 'POST',
            body: new FormData(form),
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(data => {

            if (data.success) {

                const modalEl = document.getElementById('addToCartModal');
                const existingModal = bootstrap.Modal.getInstance(modalEl);

                if (existingModal) {
                    existingModal.hide();
                }

                const cartCount = document.getElementById('cart-count');
                if (cartCount) {
                    cartCount.textContent = data.cart_count;
                    cartCount.style.display = 'inline-block';
                }

                form.reset();
                document.getElementById('totalPrice').innerText = '0';
            }

        })
        .catch(err => {
            console.error('Greška pri dodavanju u korpu:', err);
        });
    });

});
</script>

@include('partials.footer')
@endsection
