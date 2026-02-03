@extends('layouts.app')
@include('partials.header')

@section('content')

<div class="container my-5 d-flex justify-content-center">

    <div class="thankyou-card text-center">

        <div class="checkmark-wrapper">
            ✅
        </div>

        <h2 class="mt-3">Hvala na porudžbini!</h2>

        <p class="text-muted">
            Vaša porudžbina je uspešno primljena i prosleđena restoranu.
        </p>

        <p class="small text-muted">
            Status porudžbine možete pratiti u svom nalogu.
        </p>

        <div class="d-flex flex-column flex-md-row justify-content-center gap-3 mt-4">

            <a href="{{ route('user.orders.index') }}"
               class="btn btn-success px-4">
                📦 Pregled mojih narudžbina
            </a>

            <a href="{{ url('/pocetna') }}"
               class="btn btn-outline-primary px-4">
                🏠 Vrati se na početnu
            </a>

        </div>

    </div>

</div>


<style>
.thankyou-card {
    background: #ffffff;
    border-radius: 18px;
    padding: 40px 30px;
    max-width: 600px;
    width: 100%;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.checkmark-wrapper {
    font-size: 3rem;
    animation: pop 0.6s ease;
}

@keyframes pop {
    0% { transform: scale(0.6); opacity: 0; }
    70% { transform: scale(1.2); opacity: 1; }
    100% { transform: scale(1); }
}

.thankyou-card h2 {
    font-weight: 800;
    color: #2d3436;
}

.thankyou-card p {
    font-size: 1rem;
}

@media (max-width: 768px) {
    .thankyou-card {
        padding: 30px 20px;
    }
}
</style>

@endsection
