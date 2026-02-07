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
                     alt="{{ $jelo->name }} – kinesko jelo Mister Wang"
                     class="main-dish-img">

                <h1 class="main-dish-title">
                    {{ $jelo->name }}
                </h1>

                <p style="font-size:0.9rem; color:#6d4c41;">
                    {{ $jelo->name }} iz ponude kineskog restorana Mister Wang.
                    Autentični kineski ukusi, brza priprema i dostava u Beogradu.
                </p>

                <p class="main-dish-desc">
                    {{ $jelo->description }}
                </p>

                {{-- CENE SA POPUSTOM --}}

                <div class="mb-3">
                    @if(!$isPice)
                        <span style="text-decoration: line-through; color:#888; font-size:1rem;">
                            {{ number_format($originalPrice, 0) }} RSD
                        </span>

                        <br>

                        <span style="color:#d32f2f; font-size:1.4rem; font-weight:bold;">
                            {{ number_format($discountedPrice, 0) }} RSD
                        </span>
                    @else
                        <span style="color:#bf360c; font-size:1.3rem;">
                            {{ number_format($originalPrice, 0) }} RSD
                        </span>
                    @endif
                </div>

                <p class="main-dish-orders">
                    Poručeno puta: {{ $jelo->total_orders }}
                </p>

                {{-- DUGME ZA PORUČIVANJE --}}

                <button type="button"
                        class="btn btn-success btn-lg order-btn"
                        data-bs-toggle="modal"
                        data-bs-target="#addToCartModal"
                        data-id="{{ $jelo->id }}"
                        data-name="{{ $jelo->name }}"
                        data-price="{{ $jelo->price }}"
                        style="min-width:200px;">

                    🛒 Poruči odmah
                </button>

            </div>
        </div>

        <!-- Preporuke -->
        <div class="col-md-6">
            <div class="side-suggestions-box">

                <h4 class="side-section-title">
                    Preporučena pića
                </h4>

                <div class="row">
                    @foreach($pice as $p)
                        <div class="col-md-4 col-6 mb-3">
                            <a href="{{ route('dish.showWithSuggestions', ['id' => $p->id]) }}"
                               class="text-decoration-none text-dark open-order-type-modal">

                                <div class="suggestion-card">
                                    <img src="{{ asset($p->image_path) }}"
                                         alt="{{ $p->name }}"
                                         class="suggestion-img">

                                    <p class="suggestion-name">
                                        {{ $p->name }}
                                    </p>
                                </div>

                            </a>
                        </div>
                    @endforeach
                </div>

                <h4 class="side-section-title mt-4">
                    Preporučeni dezerti
                </h4>

                <div class="row">
                    @foreach($dezerti as $d)
                        <div class="col-md-4 col-6 mb-3">
                            <a href="{{ route('dish.showWithSuggestions', ['id' => $d->id]) }}"
                               class="text-decoration-none text-dark open-order-type-modal">

                                <div class="suggestion-card">
                                    <img src="{{ asset($d->image_path) }}"
                                         alt="{{ $d->name }}"
                                         class="suggestion-img">

                                    <p class="suggestion-name">
                                        {{ $d->name }}
                                    </p>
                                </div>

                            </a>
                        </div>
                    @endforeach
                </div>

            </div>
        </div>

    </div>
</div>

@include('partials.addToCartModal')

<script>
document.addEventListener('DOMContentLoaded', function () {

    // Klik na dugme "Poruči odmah"
    document.querySelectorAll('.order-btn').forEach(button => {
        button.addEventListener('click', function () {

            const productId = this.dataset.id;
            const productName = this.dataset.name;
            const productPrice = parseFloat(this.dataset.price);

            document.getElementById('modalProductId').value = productId;
            document.getElementById('productBasePrice').value = productPrice;

            document.querySelector('#addToCartModal .modal-title').textContent =
                "Dodaj u korpu: " + productName;

            document.getElementById('totalPrice').innerText =
                productPrice.toFixed(0);
        });
    });

    // GLAVNA LOGIKA – SUBMIT FORME I POVRATAK NA KATEGORIJU
    const form = document.getElementById('addToCartForm');

    if (form) {
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

                    // Zatvori modal
                    const modalEl = document.getElementById('addToCartModal');
                    const existingModal = bootstrap.Modal.getInstance(modalEl);

                    if (existingModal) {
                        existingModal.hide();
                    }

                    // Ažuriraj broj u korpi
                    const cartCount = document.getElementById('cart-count');
                    if (cartCount) {
                        cartCount.textContent = data.cart_count;
                        cartCount.style.display = 'inline-block';
                    }

                    // RESET FORME
                    form.reset();

                    // 🔥 POVRATAK NA KATEGORIJU
                    window.location.href = "{{ route('jelovnik.kategorija', ['slug' => $jelo->category->slug]) }}";
                }

            })
            .catch(err => {
                console.error('Greška pri dodavanju u korpu:', err);
            });
        });
    }

});
</script>

@include('partials.footer')
@endsection
