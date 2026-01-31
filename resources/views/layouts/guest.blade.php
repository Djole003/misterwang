<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Mister Wang') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans bg-[#f7f3ee] flex items-center justify-center min-h-screen">

    <div class="w-full sm:max-w-md px-6 py-8 bg-white shadow-xl rounded-3xl border-t-4 border-[#a45625]">
        <!-- Logo -->
        <div class="flex justify-center mb-6">
            <a href="/">
                <img src="{{ asset('assets/logo.png') }}" alt="Mister Wang" class="w-28 h-28 object-contain rounded-lg shadow-md">
            </a>
        </div>

        <!-- Slot za formu -->
        {{ $slot }}

        <!-- Link za registraciju -->
        @if (Route::has('register'))
            <p class="mt-6 text-center text-sm text-gray-600">
                {{ __("Nemate nalog?") }}
                <a href="{{ route('register') }}" class="text-[#a45625] font-semibold hover:text-[#c02333] hover:underline">
                    {{ __('Registrujte se') }}
                </a>
            </p>
        @endif
    </div>

    <style>
        /* Dugme za login/registraciju */
        .btn-primary {
            background: linear-gradient(90deg, #a45625, #c02333);
            color: white;
            padding: 12px 0;
            font-weight: 600;
            border-radius: 12px;
            width: 100%;
            text-align: center;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background: linear-gradient(90deg, #c02333, #a45625);
            transform: scale(1.03);
        }

        /* Input polja */
        input[type="text"],
        input[type="email"],
        input[type="password"] {
            border: 2px solid #ddd;
            border-radius: 10px;
            padding: 10px;
            width: 100%;
            transition: all 0.3s ease;
        }
        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: #a45625;
            box-shadow: 0 0 5px rgba(164,86,37,0.5);
            outline: none;
        }

        /* Labela */
        label {
            font-weight: 600;
            color: #333;
        }

        /* Hover i fokus linkova */
        a:hover {
            color: #c02333;
        }
    </style>
</body>
</html>
