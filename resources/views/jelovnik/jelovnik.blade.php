@extends('layouts.app')
@include('partials.header')

@section('title', 'Jelovnik | Mister Wang – Kineska hrana Beograd')
@section('meta_description', 'Pogledajte jelovnik kineskog restorana Mister Wang u Beogradu. Izaberite kategoriju i poručite kinesku hranu online.')

@section('content')

<div class="container">

    {{-- NASLOV --}}
    <h1 class="section-title text-center mb-2" style="color:#ffffff;">
        Jelovnik Mister Wang
    </h1>

    <p class="jelovnik-desc text-center mb-4">
        Izaberite kategoriju i poručite autentičnu kinesku hranu online.
    </p>

    {{-- KATEGORIJE --}}
    <div class="categories-container">
        <div class="categories-grid">
            @foreach($categories as $category)
                <a href="{{ route('jelovnik.kategorija', ['slug' => $category->slug]) }}"
                   class="category-card">

                    <img src="{{ asset($category->image) }}"
                         alt="{{ $category->name }} – kineska hrana Mister Wang Beograd">

                    {{-- SEO H2, ali vizuelno mali --}}
                    <h2 class="category-name">
                        {{ $category->name }}
                    </h2>

                </a>
            @endforeach
        </div>
    </div>

</div>

@include('partials.footer')
@endsection
