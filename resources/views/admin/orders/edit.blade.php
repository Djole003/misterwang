@extends('admin.layouts.app')
@include('partials.header')
@section('content')
<div class="container mt-4 order-edit-container">
    <h1 class="order-edit-title">Izmeni status narudžbine #{{ $order->id }}</h1>

    <form action="{{ route('admin.orders.update', $order->id) }}" method="POST" class="order-edit-form">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="user" class="form-label">Korisnik</label>
            <input type="text" class="form-control order-edit-user" value="{{ $order->user->name ?? 'Nepoznato' }}" disabled>
        </div>

        <div class="mb-3">
            <label for="total_price" class="form-label">Ukupna cena</label>
            <input type="text" class="form-control order-edit-total" value="{{ number_format($order->total_price, 2) }} RSD" disabled>
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" class="form-select @error('status') is-invalid @enderror order-edit-status" required>
                <option value="pending" {{ $order->status == 'Na čekanju' ? 'selected' : '' }}>Na čekanju</option>
                <option value="processing" {{ $order->status == 'U obradi' ? 'selected' : '' }}>U obradi</option>
                <option value="completed" {{ $order->status == 'Završeno' ? 'selected' : '' }}>Završeno</option>
                <option value="cancelled" {{ $order->status == 'Otkazano' ? 'selected' : '' }}>Otkazano</option>
            </select>
            @error('status')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary order-edit-save">Sačuvaj izmene</button>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary order-edit-back">Nazad</a>
    </form>
</div>
@endsection
