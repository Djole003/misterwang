<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Mister Wang') }}</title>

    @vite(['resources/js/app.js'])

    <style>
        /* CELA STRANICA */
        body {
            margin: 0;
            padding: 0;
            min-height: 100vh;

            display: flex;
            justify-content: center;
            align-items: center;

            background: linear-gradient(135deg, #181818, #2b0f0f);
            font-family: Arial, sans-serif;
        }

        /* GLAVNI KONTEJNER */
        .auth-container {
            width: 100%;
            max-width: 420px;

            padding: 30px;

            background: white;
            border-radius: 22px;

            border-top: 6px solid #a45625;

            box-shadow: 0 20px 40px rgba(0,0,0,0.4);

            box-sizing: border-box;
        }

        /* LOGO */
        .logo-wrapper {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo-wrapper img {
            width: 120px;
            border-radius: 12px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        }

        /* FORM GROUP */
        .form-group {
            margin-bottom: 15px;
            width: 100%;
        }

        /* LABELA */
        label {
            display: block;
            margin-bottom: 6px;

            font-weight: bold;
            color: #222;
        }

        /* INPUT POLJA */
        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px;

            border: 2px solid #ddd;
            border-radius: 12px;

            box-sizing: border-box;

            transition: all 0.3s ease;
        }

        input:focus {
            border-color: #a45625;
            outline: none;
            box-shadow: 0 0 8px rgba(164,86,37,0.5);
        }

        /* DUGME */
        .btn-primary {
            width: 100%;
            padding: 14px;

            background: linear-gradient(90deg, #a45625, #c02333);
            color: white;

            font-weight: bold;
            border: none;
            border-radius: 12px;

            cursor: pointer;

            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: scale(1.03);
        }

        /* FOOTER */
        .auth-footer {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
        }

        .auth-footer a {
            color: #a45625;
            font-weight: bold;
            text-decoration: none;
        }

        .auth-footer a:hover {
            color: #c02333;
        }

        /* ERROR PORUKE */
        .error {
            color: red;
            font-size: 13px;
            margin-top: 4px;
        }

        /* CHECKBOX RED */
        .remember-row {
            display: flex;
            align-items: center;
            margin-top: 10px;
        }

        .remember-row span {
            margin-left: 6px;
        }

        .forgot {
            display: block;
            margin-top: 10px;
            font-size: 14px;
        }
    </style>
</head>

<body>

<div class="auth-container">

    <div class="logo-wrapper">
        <a href="/">
            <img src="{{ asset('assets/logo.png') }}" alt="Mister Wang">
        </a>
    </div>

    {{ $slot }}

    @if (Route::has('register'))
        <div class="auth-footer">
            Nemate nalog?
            <a href="{{ route('register') }}">Registrujte se</a>
        </div>
    @endif

</div>

</body>
</html>
