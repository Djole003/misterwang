<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    {{-- SEO TITLE --}}
    <title>
        @yield('title', 'Mister Wang – Kineski restoran u Beogradu | Online poručivanje')
    </title>

    {{-- SEO DESCRIPTION --}}
    <meta name="description"
          content="@yield('meta_description', 'Mister Wang je kineski restoran u Beogradu. Poručite autentičnu kinesku hranu online – brza dostava, sveže namirnice i originalni recepti.')">

    {{-- SEO KEYWORDS (nije presudno, ali ne smeta) --}}
    <meta name="keywords"
          content="kineski restoran Beograd, kineska hrana dostava, poručivanje kineske hrane, Mister Wang">

    {{-- PWA --}}
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#27ae60">

    {{-- Open Graph (Facebook / WhatsApp / Viber) --}}
    <meta property="og:title"
          content="@yield('og_title', 'Mister Wang – Kineska hrana Beograd')" />
    <meta property="og:description"
          content="@yield('og_description', 'Autentična kineska hrana u Beogradu. Poruči online brzo i jednostavno.')" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:image" content="{{ asset('assets/hero.jpg') }}" />

    {{-- Icons --}}
    <link rel="icon" type="image/png" href="{{ asset('assets/logo.png') }}">

    {{-- Styles --}}
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>


<body>

<!-- ================= NAVBAR ================= -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="/pocetna">
            <img src="{{ asset('assets/logo.png') }}" alt="Logo"
                 style="height:40px;border-radius:7px;">
        </a>

        <button class="navbar-toggler" type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">

                <li class="nav-item">
                    <a class="nav-link open-order-type-modal" href="/jelovnik">Jelovnik</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/kontakt">Kontakt</a>
                </li>

                <!-- PWA INSTALL -->
                <li class="nav-item">
                    <button id="installBtn"
                            class="btn btn-success fw-bold ms-2"
                            style="display:none;border-radius:50px;padding:6px 18px;">
                        📲 Instaliraj aplikaciju
                    </button>
                </li>

                @guest
                    <li class="nav-item ms-2">
                        <a href="{{ route('login') }}"
                           class="btn btn-outline-warning fw-bold"
                           style="border-radius:50px;padding:8px 20px;">
                            Prijava
                        </a>
                    </li>
                @endguest

                @auth
                    @if(auth()->user()->role === 'admin' || auth()->user()->role === 'editor')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.dashboard') }}">
                                Urednički deo
                            </a>
                        </li>
                    @endif

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center justify-content-center"
                           href="#"
                           data-bs-toggle="dropdown"
                           style="width:40px;height:40px;border-radius:50%;background:#fff;color:#000;">
                            {{ strtoupper(auth()->user()->name[0]) }}
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end p-3 shadow"
                            style="min-width:220px;">
                            <li><strong>{{ auth()->user()->name }}</strong></li>

                            <li class="mt-1">
                                Kredit:
                                <strong>{{ number_format(auth()->user()->credit ?? 0, 0, ',', '.') }} RSD</strong>
                            </li>

                            <li><hr class="dropdown-divider"></li>

                            <li class="mt-1">
                                <a href="{{ route('user.orders.index') }}"
                                   class="btn btn-primary btn-sm w-100">
                                    Pregled narudžbina
                                </a>
                            </li>

                            <li class="mt-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button class="btn btn-danger btn-sm w-100">
                                        Odjava
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @endauth

            </ul>
        </div>
    </div>
</nav>

<!-- ================= FLASH ================= -->
<div class="container mt-3">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
</div>

<!-- ================= KORPA ================= -->
<a href="{{ route('order.cart') }}" class="cart-icon-wrapper" title="Korpa">
    <i class="fa fa-shopping-cart"></i>
</a>

<!-- ================= PWA SCRIPT ================= -->
<script>
let deferredPrompt = null;

window.addEventListener('beforeinstallprompt', (e) => {
    e.preventDefault();
    deferredPrompt = e;

    const btn = document.getElementById('installBtn');
    if (btn) btn.style.display = 'inline-block';
});

document.getElementById('installBtn')?.addEventListener('click', async () => {
    if (!deferredPrompt) return;

    deferredPrompt.prompt();
    await deferredPrompt.userChoice;

    deferredPrompt = null;
    document.getElementById('installBtn').style.display = 'none';
});

/* SERVICE WORKER */
if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('/sw.js');
}
</script>

</body>
</html>
