@extends('layouts.app')
@include('partials.header')

@section('content')
    <div class="container mt-5">
        <h1>Hvala na porudžbini!</h1>
        <p>Vaša porudžbina je uspešno primljena.</p>
        <a href="{{ url('/pocetna') }}" class="btn btn-primary">Vrati se na početnu</a>
    </div>
@endsection
