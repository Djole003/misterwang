@extends('admin.layouts.admin')

@section('title', 'Pregled narudžbina')
@section('header-title', 'Pregled narudžbina')

@section('content')


<style>
    tbody tr:hover {
        background-color: #f8f9fa;
        transition: 0.2s;
    }
</style>

<h4 class="mb-3">📜 Pregled narudžbina</h4>

{{-- 💰 PAZAR --}}
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h6 class="text-muted mb-1">Ukupan pazar</h6>
                <h3 class="mb-0 text-success">
                    {{ number_format($totalRevenue) }} RSD
                </h3>

                @if(request('period'))
                    <small class="text-muted">
                        Period: {{ request('period') }}
                    </small>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- FILTERI --}}
<div class="card mb-3">
    <div class="card-body d-flex flex-wrap gap-2">

        <a href="{{ route('admin.orders.history', ['period' => 'today']) }}"
           class="btn btn-outline-primary">Danas</a>

        <a href="{{ route('admin.orders.history', ['period' => 'yesterday']) }}"
           class="btn btn-outline-secondary">Juče</a>

        <a href="{{ route('admin.orders.history', ['period' => '7days']) }}"
           class="btn btn-outline-success">7 dana</a>

        <a href="{{ route('admin.orders.history', ['period' => '30days']) }}"
           class="btn btn-outline-warning">30 dana</a>

        <form method="GET" class="d-flex gap-2 ms-auto">
            <input type="date" name="from" class="form-control"
                   value="{{ request('from') }}">
            <input type="date" name="to" class="form-control"
                   value="{{ request('to') }}">
            <button class="btn btn-dark">Filtriraj</button>
        </form>
    </div>
</div>

{{-- TABELA --}}
<div class="table-responsive">
    <table class="table table-striped align-middle">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>Kupac</th>
                <th>Tip</th>
                <th>Ukupno</th>
                <th>Status</th>
                <th>Datum</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
            <tr onclick="showOrder({{ $order->id }})"
                style="cursor: pointer;">

                <td>#{{ $order->id }}</td>

                <td>{{ $order->name ?? '-' }}</td>

                <td>{{ strtoupper($order->order_type) }}</td>

                <td>{{ number_format($order->total_price) }} RSD</td>

                <td>
                    @php
                        $statusClass = match($order->status) {
                            'zavrsena' => 'bg-success',
                            'dostavlja_se' => 'bg-warning text-dark',
                            'u_pripremi' => 'bg-primary',
                            'primljena' => 'bg-secondary',
                            'rejected' => 'bg-danger',
                            default => 'bg-dark'
                        };
                    @endphp

                    <span class="badge {{ $statusClass }}">
                        {{ $order->status }}
                    </span>
                </td>

                <td>{{ $order->created_at->format('d.m.Y H:i') }}</td>

            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center text-muted">
                    Nema narudžbina
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-3">
    {{ $orders->links() }}
</div>

{{-- MODAL --}}
<div class="modal fade" id="orderModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">🧾 Detalji narudžbine</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body" id="orderDetails">
                <div class="text-center py-4">
                    <div class="spinner-border text-primary"></div>
                    <p class="mt-2">Učitavanje...</p>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- CSS --}}
<style>
    .modal-body ul {
        padding-left: 20px;
    }

    .modal-body li {
        margin-bottom: 6px;
    }
</style>

{{-- JS --}}
<script>
function showOrder(id) {

    let url = "{{ route('admin.orders.details', ':id') }}";
    url = url.replace(':id', id);

    fetch(url)
        .then(res => {
            if (!res.ok) throw new Error("404");
            return res.text();
        })
        .then(html => {

            document.getElementById('orderDetails').innerHTML = html;

            let modal = new bootstrap.Modal(document.getElementById('orderModal'));
            modal.show();
        })
        .catch(() => {
            document.getElementById('orderDetails').innerHTML =
                "<div class='text-danger text-center'>Greška (ruta ne postoji)</div>";
        });
}
</script>

@endsection