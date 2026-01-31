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

    {{-- SEO NASLOV --}}
    <h1 class="section-title mb-2 text-center"
        style="color:black; font-weight:700;">
        {{ $category->name }} – Mister Wang
    </h1>

    {{-- SEO OPIS (DISKRETAN) --}}
    <p class="text-center mb-4"
       style="color:#4e342e; font-size:0.95rem;">
        Pogledajte ponudu iz kategorije <strong>{{ $category->name }}</strong>
        u kineskom restoranu Mister Wang. Brza dostava i autentični kineski ukusi.
    </p>

    <div class="row g-3 justify-content-center">
        @foreach($products as $product)
        <div class="col-6 col-sm-4 col-md-3 col-lg-2">
            <div class="card product-card h-100 text-center position-relative shadow-sm p-2"
                 style="background-color:#fff8f0; border-radius:10px;">

                <a href="{{ route('dish.showWithSuggestions', $product->id) }}"
                   class="text-decoration-none text-dark d-block h-100">

                    <img src="{{ asset($product->image_path) }}"
                         class="card-img-top mb-2"
                         alt="{{ $product->name }} - kinesko jelo Mister Wang"
                         style="height:100px; object-fit:cover; border-radius:6px;">

                    <div class="card-body p-1">
                        <h6 class="card-title mb-1"
                            style="font-size:0.85rem;">
                            {{ $product->name }}
                        </h6>

                        <p class="card-text mb-1 fw-bold"
                           style="font-size:0.75rem; color:#bf360c;">
                            {{ number_format($product->price, 0) }} RSD
                        </p>
                    </div>
                </a>

                <?php
                $hideSize = $hideSos = $hideAddons = $hideMeat = 0;

                switch($category->slug){
                    case 'predjela-i-salate':
                        $hideSize = 1;
                        $hideSos = 1;
                        $hideAddons = 1;
                        $hideMeat = 1;
                        break;

                    case 'supe':
                    case 'pirinac-i-nudle':
                        $hideSize = 1;
                        $hideSos = 1;
                        $hideAddons = 0;
                        $hideMeat = 1;
                        break;

                    case 'dezerti':
                    case 'pice':
                        $hideSize = 1;
                        $hideSos = 1;
                        $hideAddons = 1;
                        $hideMeat = 1;
                        break;

                    case 'morski-plodovi':
                    case 'jela-bez-mesa':
                        $hideSize = 1;
                        $hideMeat = 1;
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
                        break;

                }
                ?>

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
                        data-category="{{ $category->slug }}"
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
    const modalInstance = new bootstrap.Modal(addToCartModal);
    const form = document.getElementById('addToCartForm');

    // =============================
    // OTVARANJE MODALA
    // =============================
    document.querySelectorAll('.order-btn').forEach(button => {
        button.addEventListener('click', function () {

            const productId = this.dataset.id;
            const productName = this.dataset.name;
            const productPrice = parseFloat(this.dataset.price);

            // product id
            document.getElementById('modalProductId').value = productId;

            // base cena
            document.getElementById('productBasePrice').value = productPrice;

            // naslov
            addToCartModal.querySelector('.modal-title').textContent =
                "Dodaj u korpu: " + productName;

            // početna cena
            document.getElementById('totalPrice').innerText =
                productPrice.toFixed(0);

            // hide sekcije
            const hideSize = this.dataset.hideSize === "1";
            const hideSos = this.dataset.hideSos === "1";
            const hideAddons = this.dataset.hideAddons === "1";
            const hideMeat = this.dataset.hideMeat === "1";

            document.getElementById('sizeSection').style.display   = hideSize   ? 'none' : 'block';
            document.getElementById('sosSection').style.display    = hideSos    ? 'none' : 'block';
            document.getElementById('addonsSection').style.display = hideAddons ? 'none' : 'block';
            document.getElementById('meatSection').style.display   = hideMeat   ? 'none' : 'block';

            modalInstance.show();
        });
    });

    // =============================
    // SUBMIT FORME (AJAX)
    // =============================
    form.addEventListener('submit', function (e) {
        e.preventDefault(); // ⛔ sprečava reload

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

                // ✅ zatvori modal
                modalInstance.hide();

                // ✅ update badge korpe
                const cartCount = document.getElementById('cart-count');
                if (cartCount) {
                    cartCount.textContent = data.cart_count;
                    cartCount.style.display = 'inline-block';
                }

            }

        })
        .catch(err => {
            console.error('Greška pri dodavanju u korpu:', err);
        });
    });

    // =============================
    // RESET MODALA
    // =============================
    addToCartModal.addEventListener('hidden.bs.modal', function () {
        form.reset();
        document.getElementById('totalPrice').innerText = '0';

        document.querySelectorAll('.addon-checkbox').forEach(cb => cb.checked = false);

        document.body.classList.remove('modal-open');
        document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
    });

});
</script>


@include('partials.footer')
@endsection
