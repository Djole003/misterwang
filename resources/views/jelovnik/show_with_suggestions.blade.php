@extends('layouts.app')

@include('partials.header')

@section('content')
@php
    // Provera tipa porudžbine iz sessiona
    $orderType = session('order_type', 'delivery');
    $price = $orderType === 'delivery' ? $jelo->price_delivery : $jelo->price_takeaway;
@endphp

<div class="custom-detail-wrapper container my-5">
    <div class="row">

        <!-- Glavno jelo -->
        <div class="col-md-6">
            <div class="main-dish-box">

                <img src="{{ asset($jelo->image_path) }}"
                     alt="{{ $jelo->name }} – kinesko jelo Mister Wang"
                     class="main-dish-img">

                {{-- SEO H1 --}}
                <h1 class="main-dish-title">
                    {{ $jelo->name }}
                </h1>

                {{-- SEO opis (diskretan) --}}
                <p style="font-size:0.9rem; color:#6d4c41;">
                    {{ $jelo->name }} iz ponude kineskog restorana Mister Wang.
                    Autentični kineski ukusi, brza priprema i dostava u Beogradu.
                </p>

                <p class="main-dish-desc">
                    {{ $jelo->description }}
                </p>

                <p class="main-dish-price">
                    Cena: {{ number_format($price, 0) }} RSD
                </p>

                <p class="main-dish-orders">
                    Poručeno puta: {{ $jelo->total_orders }}
                </p>

                <a href="{{ route('jelovnik.kategorija', ['slug' => $jelo->category->slug]) }}"
                   class="btn btn-secondary mt-3">
                    Nazad na {{ $jelo->category->name }}
                </a>
            </div>
        </div>

        <!-- Preporuke -->
        <div class="col-md-6">
            <div class="side-suggestions-box">

                {{-- PIĆA --}}
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
                                         alt="{{ $p->name }} – piće Mister Wang"
                                         class="suggestion-img">

                                    <p class="suggestion-name">
                                        {{ $p->name }}
                                    </p>
                                </div>

                            </a>
                        </div>
                    @endforeach
                </div>

                {{-- DEZERTI --}}
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
                                         alt="{{ $d->name }} – dezert Mister Wang"
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

@include('partials.footer')
@endsection
