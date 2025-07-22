<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Triconnect</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
       
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

        @include('adminlte::partials.sidebar')


        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-triconnect-light text-triconnect-dark">
        <div class="min-h-screen bg-triconnect-light">
            <nav class="bg-triconnect shadow-lg p-4 flex items-center sticky-nav">
                <div class="flex items-center">
                    <!-- Logo -->
                    <img src="{{ asset('images/triconnect.png') }}" alt="Triconnect Logo" class="w-10 h-10 rounded-full mr-3 bg-white p-1 shadow" />
                    <span class="text-white text-2xl font-bold tracking-wide">Triconnect</span>
                </div>
                <div class="ml-auto">
                    @include('layouts.navigation')
                </div>
            </nav>
            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset
            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>

    @include('adminlte::partials.footer')
</html>

<style>
    .sticky-nav {
        position: sticky;
        top: 0;
        z-index: 1030;
        background: inherit;
    }
</style>
