<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    @yield('meta')

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body id="app">
    <header>
        <div class="row header-top">
            <nav class="navbar navbar-expand-md header-sidebar col">
                <div class="container-logo">
                    <a class="col"href="{{ route('product.index') }}">
                        <img class="header-logo" src="{{ Storage::url('Logo.svg') }}" alt="">
                    </a>
                </div>

                <p class="logo-name">Enterprize <br />
                    Resorce <br />
                    Planning
                </p>
            </nav>
            <nav class="navbar navbar-expand-md header-main col">
                <a class="navbar-brand logo-brand text-danger ms-3" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <div class="section"></div>

                <p class="cabinet-user">{{ getFullName() }}</p>
            </nav>
        </div>
    </header>

    <main class="app-content row">


        <div class="main-sidebar col">
            <p class="products-name text-white py-3">Продукты</p>
        </div>

        <div class="container py-3 col">
            @include('layouts.partials.flash')
            @yield('content')
        </div>

        <div class="app-dynamic-page visually-hidden"></div>
    </main>

    <footer>
        <div class="container">
            <div class="border-top pt-3">
                <p>&copy; {{ date('Y') }}</p>
            </div>
        </div>
    </footer>

    <!-- JavaScripts -->
    @yield('scripts')
</body>
</html>
