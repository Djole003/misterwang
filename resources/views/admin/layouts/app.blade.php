<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <!-- Dodaj ovde ostale CSS ili JS ako treba -->
</head>
<body>
    <!-- Sidebar možeš ovde staviti ako želiš, ili u partial -->
    <div class="sidebar">
        <nav class="nav flex-column">
            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">Dashboard</a>
            <a class="nav-link {{ request()->routeIs('admin.users.index') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">Korisnici</a>
            <a class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}" href="{{ route('admin.products.index') }}">Proizvodi</a>
        </nav>
    </div>

    <div class="main-content">
        @yield('content')
    </div>
</body>
</html>
