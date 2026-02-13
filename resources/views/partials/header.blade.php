<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <title>
        @yield('title', 'Mister Wang – Kineski restoran u Beogradu | Online poručivanje')
    </title>

    <meta name="description"
          content="@yield('meta_description', 'Mister Wang je kineski restoran u Beogradu. Poručite autentičnu kinesku hranu online – brza dostava, sveže namirnice i originalni recepti.')">

    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#27ae60">
    <link rel="icon" type="image/png" href="{{ asset('assets/logo.png') }}">
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
/* tvoj CSS ostaje isti */
.current-local { cursor:pointer; transition:all 0.2s ease; user-select:none; }
.current-local:hover { transform:scale(1.05); }
.local-arrow { transition:transform 0.25s ease; }
.show > .current-local .local-arrow { transform:rotate(180deg); }
.current-local.clicked { animation:clickBounce 0.4s ease; }
.nav-click { transition:all 0.2s ease; }
.nav-click.clicked { animation:clickBounce 0.4s ease; }

@keyframes clickBounce {
    0%{transform:scale(1);}
    50%{transform:scale(1.1);}
    100%{transform:scale(1);}
}

.dropdown-menu {
    transform-origin: top;
    animation: slideDown 0.25s ease forwards;
}

@keyframes slideDown {
    from{opacity:0; transform:scaleY(0.8);}
    to{opacity:1; transform:scaleY(1);}
}

.local-changed { animation: changedFlash 0.7s ease; }

@keyframes changedFlash {
    0%{box-shadow:0 0 0px rgba(255,255,255,0);}
    50%{box-shadow:0 0 12px rgba(0,255,100,0.9);}
    100%{box-shadow:0 0 0px rgba(255,255,255,0);}
}
</style>
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
<div class="container">

    <a class="navbar-brand nav-click" href="/pocetna">
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

{{-- LOKAL --}}
@if(isset($currentRestaurant))
<li class="nav-item dropdown me-3">
    <span class="badge {{ $currentRestaurant->is_open ? 'bg-success' : 'bg-danger' }}
        text-white p-2 current-local"
        style="font-size:0.85rem;"
        id="localDropdown"
        data-bs-toggle="dropdown">

        📍 {{ $currentRestaurant->name }}
        <i class="fas fa-chevron-down local-arrow ms-1"></i>
    </span>

    <ul class="dropdown-menu dropdown-menu-end shadow">
        <li>
            <a class="dropdown-item nav-click" href="{{ route('select.restaurant') }}">
                Promeni lokal
            </a>
        </li>
    </ul>
</li>
@endif


<li class="nav-item">
    <a class="nav-link nav-click" href="/jelovnik">Jelovnik</a>
</li>

<li class="nav-item">
    <a class="nav-link nav-click" href="/kontakt">Kontakt</a>
</li>

{{-- INSTALIRAJ DUGME – NIJE DIRANO --}}
<li class="nav-item">
    <button id="installBtn"
        class="btn btn-success fw-bold ms-2 nav-click"
        style="display:none;border-radius:50px;padding:6px 18px;">
        📲 Instaliraj aplikaciju
    </button>
</li>


{{-- GUEST --}}
@guest
<li class="nav-item ms-2">
    <a href="{{ route('login') }}"
       class="btn btn-outline-warning fw-bold nav-click"
       style="border-radius:50px;padding:8px 20px;">
        Prijava
    </a>
</li>
@endguest


{{-- AUTH --}}
@auth

{{-- ADMIN + EDITOR LINK --}}
@if(auth()->user()->role === 'admin' || auth()->user()->role === 'editor')
<li class="nav-item">
    <a class="nav-link nav-click" href="{{ route('admin.dashboard') }}">
        Urednički deo
    </a>
</li>
@endif


{{-- PROFIL DROPDOWN --}}
<li class="nav-item dropdown ms-3">

<a class="nav-link dropdown-toggle nav-click d-flex align-items-center"
   href="#"
   role="button"
   data-bs-toggle="dropdown">

    <div style="
        width:35px;
        height:35px;
        border-radius:50%;
        background:#ffc107;
        color:black;
        display:flex;
        align-items:center;
        justify-content:center;
        font-weight:bold;">
        {{ strtoupper(substr(auth()->user()->name,0,1)) }}
    </div>
</a>

<ul class="dropdown-menu dropdown-menu-end shadow">

<li>
    <a class="dropdown-item nav-click"
       href="{{ route('user.orders.index') }}">
        Moje narudžbine
    </a>
</li>

<li><hr class="dropdown-divider"></li>

<li>
<form method="POST" action="{{ route('logout') }}">
@csrf
<button type="submit" class="dropdown-item nav-click">
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


{{-- CART --}}
<a href="{{ route('order.cart') }}" class="cart-icon-wrapper nav-click" title="Korpa">
<i class="fa fa-shopping-cart"></i>
</a>


<script>
document.addEventListener("DOMContentLoaded", function() {

const local = document.getElementById("localDropdown");

if (local) {
    local.addEventListener("click", function() {
        local.classList.add("clicked");
        setTimeout(()=>local.classList.remove("clicked"),400);
    });

    @if(session('restaurant_changed'))
        local.classList.add("local-changed");
    @endif
}

document.querySelectorAll('.nav-click').forEach(el=>{
    el.addEventListener('click',function(){
        el.classList.add('clicked');
        setTimeout(()=>el.classList.remove('clicked'),400);
    });
});

});
</script>

<script>
let deferredPrompt;
const installBtn = document.getElementById('installBtn');

window.addEventListener('beforeinstallprompt', (e) => {

    // Sprečavamo automatski popup
    e.preventDefault();

    deferredPrompt = e;

    // Prikaži dugme
    installBtn.style.display = "inline-block";
});

installBtn.addEventListener('click', async () => {

    if (!deferredPrompt) return;

    deferredPrompt.prompt();

    const { outcome } = await deferredPrompt.userChoice;

    if (outcome === 'accepted') {
        console.log('Korisnik je instalirao aplikaciju');
    }

    deferredPrompt = null;
    installBtn.style.display = "none";
});

window.addEventListener('appinstalled', () => {
    installBtn.style.display = "none";
});
</script>


</body>
</html>
