<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <title>Mister Wang – Izaberite lokal</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Favicon / logo u tabu --}}
    <link rel="icon" type="image/png" href="{{ asset('assets/logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('assets/logo.png') }}">

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #111;
            color: #fff;
        }
        .restaurant-card {
            cursor: pointer;
            transition: transform .2s, box-shadow .2s;
        }
        .restaurant-card:hover {
            transform: scale(1.03);
            box-shadow: 0 10px 25px rgba(0,0,0,.6);
        }
        .restaurant-card img {
            height: 240px;
            object-fit: cover;
        }

        .status-badge {
            font-weight: 600;
            font-size: 0.95rem;
        }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="position-absolute top-0 end-0 p-3">
        @guest
            <a href="{{ route('login') }}" class="btn btn-outline-light">
                Login
            </a>
        @endguest
    </div>

    <h2 class="text-center mb-5">Izaberite lokal</h2>

    <div class="row g-4 justify-content-center">
        @foreach($restaurants as $restaurant)
            {{-- 2x2 na mobilnom, 4 u redu na desktopu --}}
            <div class="col-6 col-md-3">
                <form method="POST" action="{{ route('select.restaurant.store') }}">
                    @csrf
                    <input type="hidden" name="restaurant_id" value="{{ $restaurant->id }}">

                    <div class="card restaurant-card bg-dark text-white"
                         onclick="this.closest('form').submit()">

                        <img src="{{ asset($restaurant->image_path) }}"
                             class="card-img-top"
                             alt="{{ $restaurant->name }}">

                        <div class="card-body text-center">
                            <h5 class="card-title mb-1">
                                {{ $restaurant->name }}
                            </h5>

                            {{-- STATUS LOKALA --}}
                            <div class="status-badge mt-2">
                                @if(isset($restaurant->status))
                                    @if($restaurant->status['open'])
                                        <span class="text-success">
                                            {{ $restaurant->status['message'] }}
                                        </span>
                                    @else
                                        <span class="text-danger">
                                            {{ $restaurant->status['message'] }}
                                        </span>
                                    @endif
                                @else
                                    <span class="text-warning">
                                        Status nepoznat
                                    </span>
                                @endif
                            </div>

                        </div>
                    </div>
                </form>
            </div>
        @endforeach
    </div>
</div>

</body>
</html>
