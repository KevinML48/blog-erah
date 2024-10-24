<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="{{ asset('js/time-display.js') }}"></script>

    <style>
        body {
            background: linear-gradient(to bottom left,
            #ff0000 0%,
            #660000 15%,
            #330000 20%,
            #000000 50%,
            #000000 100%);
            color: white;
        }
    </style>
</head>
<body class="font-sans antialiased">
<div class="min-h-screen">
    @include('layouts.navigation')

    <!-- Page Heading -->
    @isset($header)
        <header class="">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <h2 class="font-semibold text-xl leading-tight">
                    {{ $header }}
                </h2>
            </div>
        </header>
    @endisset

    <!-- Page Content -->
    <main>
        {{ $slot }}
    </main>
</div>
</body>
</html>
