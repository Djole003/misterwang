@extends('admin.layouts.admin')
@php
    $restaurantOpen = DB::table('restaurant_status')->value('is_open');
@endphp

@section('content')
    <h1>Dashboard</h1>
    <div class="card mb-4">
        <div class="card-body text-center">

            <h5 class="mb-3">
                Status restorana:
                @if($restaurantOpen)
                    <span class="badge bg-success">OTVORENO</span>
                @else
                    <span class="badge bg-danger">ZATVORENO</span>
                @endif
            </h5>

            <form method="POST" action="{{ route('admin.restaurant.toggle') }}">
                @csrf

                <button type="submit"
                    class="btn {{ $restaurantOpen ? 'btn-danger' : 'btn-success' }}">
                    {{ $restaurantOpen ? 'Zatvori restoran' : 'Otvori restoran' }}
                </button>
            </form>

        </div>
    </div>




    <p>Dobrodošao u admin panel.</p>
@endsection
