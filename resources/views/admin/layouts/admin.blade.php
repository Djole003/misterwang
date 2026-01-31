<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin panel')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#27ae60">

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- ADMIN CSS --}}
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
</head>
<body>

{{-- TOP NAV --}}
<nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('index') }}">Mister Wang</a>

        <div class="d-flex align-items-center gap-3">

            {{-- 👑 EDITOR: SWITCH RESTORANA --}}
            @if(auth()->check() && auth()->user()->role === 'editor')
                <form method="POST"
                      action="{{ route('admin.switchRestaurant') }}">
                    @csrf
                    <select name="restaurant_id"
                            class="form-select form-select-sm"
                            onchange="this.form.submit()">
                        @foreach(\App\Models\Restaurant::all() as $restaurant)
                            <option value="{{ $restaurant->id }}"
                                {{ session('restaurant_id') == $restaurant->id ? 'selected' : '' }}>
                                {{ $restaurant->name }}
                            </option>
                        @endforeach
                    </select>
                </form>
            @endif

            <span class="text-white">
                {{ auth()->user()->name }}
            </span>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn btn-sm btn-outline-light">Odjava</button>
            </form>
        </div>
    </div>
</nav>

<div class="admin-wrapper">

    {{-- SIDEBAR --}}
    <aside class="sidebar p-3">
        <nav class="nav flex-column gap-1">

            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
               href="{{ route('admin.dashboard') }}">
                📊 Dashboard
            </a>

            <a class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}"
               href="{{ route('admin.orders.index') }}">
                🧾 Narudžbine
            </a>

            <a class="nav-link {{ request()->routeIs('admin.orders.history') ? 'active' : '' }}"
               href="{{ route('admin.orders.history') }}">
                📜 Pregled narudžbina
            </a>

            <a class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}"
               href="{{ route('admin.products.index') }}">
                🍜 Proizvodi
            </a>

            {{-- 👑 SAMO EDITOR VIDI KORISNIKE --}}
            @if(auth()->user()->role === 'editor')
                <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"
                   href="{{ route('admin.users.index') }}">
                    👤 Korisnici
                </a>
            @endif

        </nav>
    </aside>

    {{-- CONTENT --}}
    <div class="content-wrapper">

        {{-- HEADER --}}
        <header class="admin-header d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-2">
                <button class="btn btn-sm btn-outline-secondary d-lg-none" id="sidebarToggle">
                    ☰
                </button>

                <h4 class="mb-0">@yield('header-title', 'Admin')</h4>
            </div>

            <span class="text-muted">{{ now()->format('H:i') }}</span>
        </header>

        {{-- PAGE CONTENT --}}
        <main class="p-4">

            @if(session('success'))
                <div class="alert alert-success text-center">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger text-center">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>

    </div>
</div>

<div class="sidebar-overlay"></div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

@yield('scripts')
</body>
</html>
