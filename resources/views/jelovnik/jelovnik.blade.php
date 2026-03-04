@extends('layouts.app')
@include('partials.header')

@section('title', 'Jelovnik | Mister Wang – Kineska hrana Beograd')
@section('meta_description', 'Pogledajte jelovnik kineskog restorana Mister Wang u Beogradu. Poručite kinesku hranu online.')

@section('content')

<style>

html{
scroll-behavior:smooth;
}

.section-title{
font-weight:700;
color:#fff;
}

/* KATEGORIJE */

.category-nav{
display:flex;
gap:10px;
overflow-x:auto;
padding:14px 20px;

position:sticky;
top:70px;

background:linear-gradient(135deg,#a55722,#c96d32);
box-shadow:0 6px 16px rgba(0,0,0,0.15);

backdrop-filter:blur(10px);

z-index:1000;
border-radius:14px;
}

.category-nav::-webkit-scrollbar{
display:none;
}

.category-nav-item{
flex:0 0 auto;
background:white;
padding:8px 18px;
border-radius:20px;
text-decoration:none;
color:#333;
white-space:nowrap;
font-weight:600;
font-size:14px;

transition:all 0.2s ease;
}

.category-nav-item:hover{
background:#ff7043;
color:white;
transform:translateY(-1px);
}

.category-nav-item.active{
background:#ff7043;
color:white;
box-shadow:0 4px 12px rgba(0,0,0,0.2);
}

/* PRODUCT CARD */

.product-card{
border:none;
border-radius:14px;
box-shadow:0 6px 16px rgba(0,0,0,0.08);
transition:all 0.2s ease;
cursor:pointer;
}

.product-card:hover{
transform:translateY(-4px);
box-shadow:0 10px 24px rgba(0,0,0,0.12);
}

.product-img{
height:120px;
object-fit:cover;
}

.discount-badge{
position:absolute;
top:6px;
left:6px;
background:#e53935;
color:white;
font-size:0.65rem;
padding:3px 7px;
border-radius:4px;
z-index:2;
}

.category-title{
display:flex;
align-items:center;
gap:15px;
margin:40px 0 20px 0;
}

.category-title span{
font-size:1.6rem;
font-weight:700;
color:#fff;
position:relative;
padding-bottom:6px;
}

.category-title span::after{
content:"";
position:absolute;
left:0;
bottom:0;
width:60px;
height:3px;
background:#ff7043;
border-radius:3px;
}

</style>


<div class="container">

<h1 class="section-title text-center mb-4">
Jelovnik Mister Wang
</h1>




@php

$customOrder = [
'akcije',
'predjela-i-salate',
'jela-sa-mesom',
'jela-bez-mesa',
'morski-plodovi',
'supe',
'pirinac-i-nudle',
'dezerti',
'pice'
];

$sortedCategories = $categories->sortBy(function($category) use ($customOrder) {
return array_search($category->slug, $customOrder);
});

@endphp


<div class="category-nav">

@foreach($sortedCategories as $category)

<a href="#{{ $category->slug }}" class="category-nav-item">
{{ $category->name }}
</a>

@endforeach

</div>


@if(isset($najprodavanija) && $najprodavanija->count())

<section class="mb-5">

<div class="category-title">
<span>🔥 Najprodavanija jela</span>
</div>

<div class="row g-3 justify-content-center">

@foreach($najprodavanija as $product)

@php
$orderType = session('order_type','delivery');

$oldPrice = $orderType === 'takeaway'
? $product->price_takeaway
: $product->price_delivery;

$newPrice = $product->price;

$isDiscounted = $oldPrice != $newPrice;

$isDrink = $product->category->slug === 'pice';
@endphp

<div class="col-6 col-sm-4 col-md-3 col-lg-2">

<div class="card product-card h-100 shadow-sm position-relative"
onclick="window.location='{{ route('dish.showWithSuggestions', $product->id) }}'">

@if($isDiscounted)
<div class="discount-badge">-15%</div>
@endif

<img src="{{ asset($product->image_path) }}"
class="card-img-top product-img"
alt="{{ $product->name }}">

<div class="card-body text-center p-2 d-flex flex-column justify-content-between">

<div>

<h6 class="card-title mb-1" style="font-size:0.85rem;">
{{ $product->name }}
</h6>

@if($isDiscounted)

<p class="mb-0 text-muted"
style="text-decoration: line-through; font-size:0.75rem;">
{{ number_format($oldPrice,0) }} RSD
</p>

<p class="fw-bold text-danger mb-2"
style="font-size:0.9rem;">
{{ number_format($newPrice,0) }} RSD
</p>

@else

<p class="fw-bold mb-2"
style="font-size:0.9rem;">
{{ number_format($newPrice,0) }} RSD
</p>

@endif

</div>


@if($isDrink)

<form action="{{ route('cart.add') }}" method="POST">
@csrf

<input type="hidden" name="product_id" value="{{ $product->id }}">
<input type="hidden" name="quantity" value="1">

<button
type="submit"
class="btn btn-sm btn-success w-100"
style="font-size:0.75rem;"
onclick="event.stopPropagation();"
>
Dodaj
</button>

</form>

@else

<button
type="button"
class="btn btn-sm btn-success order-btn w-100"
data-bs-toggle="modal"
data-bs-target="#addToCartModal"
onclick="event.stopPropagation();"
data-id="{{ $product->id }}"
data-price="{{ $oldPrice }}"
data-is-drink="0"
data-has-size="{{ $product->has_size }}"
data-has-sos="{{ $product->has_sos }}"
data-has-meat="{{ $product->has_meat }}"
data-has-rice="{{ $product->has_rice_option }}"
data-addons='@json($product->category->addOns ?? [])'
style="font-size:0.75rem;"
>

Poruči

</button>

@endif

</div>

</div>

</div>

@endforeach

</div>

</section>

@endif


@foreach($sortedCategories as $category)

<section id="{{ $category->slug }}" class="mb-5">

<div class="category-title">
<span>{{ $category->name }}</span>
</div>

<div class="row g-3 justify-content-center">

@foreach($productsByCategory[$category->id] ?? [] as $product)

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
onclick="window.location='{{ route('dish.showWithSuggestions', $product->id) }}'">

@if($isDiscounted)

<div class="discount-badge">
-15%
</div>

@endif

<img src="{{ asset($product->image_path) }}"
class="card-img-top product-img"
alt="{{ $product->name }}">

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


@if($isDrink)

<form action="{{ route('cart.add') }}" method="POST">
@csrf

<input type="hidden" name="product_id" value="{{ $product->id }}">
<input type="hidden" name="quantity" value="1">

<button
type="submit"
class="btn btn-sm btn-success w-100"
style="font-size:0.75rem;"
onclick="event.stopPropagation();"
>
Dodaj
</button>

</form>

@else

<button
type="button"
class="btn btn-sm btn-success order-btn w-100"
data-bs-toggle="modal"
data-bs-target="#addToCartModal"
onclick="event.stopPropagation();"
data-id="{{ $product->id }}"
data-price="{{ $oldPrice }}"
data-is-drink="0"
data-has-size="{{ $product->has_size }}"
data-has-sos="{{ $product->has_sos }}"
data-has-meat="{{ $product->has_meat }}"
data-has-rice="{{ $product->has_rice_option }}"
data-addons='@json($category->addOns)'
style="font-size:0.75rem;"
>

Poruči

</button>

@endif

</div>

</div>

</div>

@endforeach

</div>

</section>

@endforeach


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

            /* RESETUJ modal */

            sizeSection.style.display = 'none';
            sosSection.style.display = 'none';
            meatSection.style.display = 'none';
            riceSection.style.display = 'none';

            /* prikaži samo šta jelo ima */

            if(currentConfig.hasSize) sizeSection.style.display = 'block';
            if(currentConfig.hasSos) sosSection.style.display = 'block';
            if(currentConfig.hasMeat) meatSection.style.display = 'block';
            if(currentConfig.hasRice) riceSection.style.display = 'block';


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
                                   for="${id}">
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


document.querySelectorAll('.category-nav-item').forEach(link => {

link.addEventListener('click', function(){

setTimeout(() => {

link.scrollIntoView({
behavior:"smooth",
inline:"center",
block:"nearest"
});

},100);

});

});
</script>
@endpush