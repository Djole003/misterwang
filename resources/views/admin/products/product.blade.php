@extends('admin.layouts.admin')

@section('title', 'Proizvodi')
@section('header-title', '📦 Proizvodi')

@section('content')
<div class="ap-page">

    {{-- HEADER --}}
    <div class="ap-header">
        <h2>📦 Proizvodi</h2>

        <a href="{{ route('admin.products.create') }}"
           class="btn btn-primary">
            + Dodaj proizvod
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- TABLE --}}
    <div class="ap-table-wrapper">
        <table class="table table-hover ap-table">

            <thead>
                <tr>
                    <th>#</th>
                    <th>Naziv</th>
                    <th>Cena</th>
                    <th>Kategorija</th>
                    <th>Slika</th>
                    <th>Status</th>
                    <th>Akcije</th>
                </tr>
            </thead>

            <tbody>
            @forelse($products as $product)
                <tr>
                    <td>{{ $product->id }}</td>

                    <td class="fw-semibold">
                        {{ $product->name }}
                    </td>

                    <td>
                        {{ number_format($product->price, 0) }} RSD
                    </td>

                    <td>
                        {{ $product->category->name ?? '-' }}
                    </td>

                    <td>
                        @if($product->image_path)
                            <img src="{{ asset('storage/' . $product->image_path) }}"
                                 class="ap-img"
                                 alt="{{ $product->name }}">
                        @else
                            —
                        @endif
                    </td>

                    <td>
                        <button
                            class="btn btn-sm toggle-availability-btn
                            {{ $product->isAvailableForCurrentRestaurant() ? 'btn-success' : 'btn-danger' }}"
                            data-id="{{ $product->id }}">
                            {{ $product->isAvailableForCurrentRestaurant()
                                ? 'Dostupno'
                                : 'Nema na stanju' }}
                        </button>
                    </td>

                    <td class="ap-actions">
                        <a href="{{ route('admin.products.edit', $product->id) }}"
                           class="btn btn-warning btn-sm">
                            Izmeni
                        </a>

                        @if(auth()->user()->role === 'editor')
                            <form action="{{ route('admin.products.destroy', $product->id) }}"
                                  method="POST"
                                  class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('Obrisati proizvod?')">
                                    Obriši
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center text-muted">
                        Nema proizvoda
                    </td>
                </tr>
            @endforelse
            </tbody>

        </table>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('click', function (e) {

    const button = e.target.closest('.toggle-availability-btn');
    if (!button) return;

    // zaštita od duplog klika
    if (button.dataset.loading === '1') return;
    button.dataset.loading = '1';
    button.disabled = true;

    const productId = button.dataset.id;

    fetch(`/admin/products/${productId}/toggle-availability`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(r => r.json())
    .then(data => {

        if (typeof data.is_available === 'undefined') {
            alert('Neispravan odgovor sa servera');
            return;
        }

        button.classList.toggle('btn-success', data.is_available);
        button.classList.toggle('btn-danger', !data.is_available);

        button.textContent = data.is_available
            ? 'Dostupno'
            : 'Nema na stanju';
    })
    .catch(() => {
        alert('Greška pri promeni statusa');
    })
    .finally(() => {
        button.disabled = false;
        button.dataset.loading = '0';
    });
});
</script>
@endsection
