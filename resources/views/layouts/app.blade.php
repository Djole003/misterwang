<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ config('app.name', 'Laravel') }}</title>

    <link href="{{ asset('css/styles.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#27ae60">

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
<div class="min-h-screen bg-gray-100">

    {{-- Poruka o radnom vremenu --}}
    @if(!empty($openingMessage))
        <div class="alert alert-danger text-center m-0">
            {{ $openingMessage }}
        </div>
    @endif

    {{-- GLOBALNE FLASH PORUKE --}}
    @if(session('error'))
        <div class="alert alert-danger text-center m-0">
            {{ session('error') }}
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success text-center m-0">
            {{ session('success') }}
        </div>
    @endif

    <main>
        @yield('content')
    </main>
</div>

<!-- Modal za izbor tipa porudžbine -->
<div class="modal fade" id="orderTypeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content p-4">

            <h5 class="modal-title text-center mb-3">
                Izaberite tip porudžbine
            </h5>

            <div class="d-flex justify-content-around">

                <button type="button"
                        class="btn btn-danger order-type-btn"
                        data-type="delivery">
                    Dostava
                </button>

                <button type="button"
                        class="btn btn-outline-primary order-type-btn"
                        data-type="takeaway">
                    Lično preuzimanje
                </button>

            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const orderLinks = document.querySelectorAll('.open-order-type-modal');

    orderLinks.forEach(link => {
        link.addEventListener('click', function(e){
            e.preventDefault();

            const targetUrl = link.getAttribute('href');
            const modalEl = document.getElementById('orderTypeModal');
            const modal = new bootstrap.Modal(modalEl);

            modal.show();

            document.querySelectorAll('.order-type-btn').forEach(btn => {
                btn.onclick = function(ev) {
                    ev.preventDefault();
                    const type = btn.dataset.type;

                    fetch(`/select-order-type/${type}`)
                        .then(() => {
                            modal.hide();
                            window.location.href = targetUrl;
                        });
                };
            });
        });
    });
});
</script>

<script>
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js')
            .then(reg => console.log('✅ Service Worker registrovan', reg))
            .catch(err => console.error('❌ SW greška', err));
    });
}
</script>

@stack('scripts')

</body>
</html>