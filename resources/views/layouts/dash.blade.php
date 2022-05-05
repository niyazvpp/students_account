<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Admin Panel') }}</title>


        <!-- Styles -->
        @php
            $version = session('version') || 1;
            $version_ = $version + 1;
            session(['version' => $version_]);
        @endphp
        <link rel="stylesheet" href="{{ asset('css/app.css') }}?ver={{ $version }}">

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}" defer></script>
    </head>
    <body class="font-sans antialiased bg-gray-100 min-h-screen">

        <div class="sm:grid sm:grid-cols-12 h-full">
        @include('parts.navigation')

        <main class="sm:col-span-10">
            @include('parts.header')
            <div class="px-4 sm:px-8 py-4 my-6" style="margin-top: -250px;">
                @yield('main')
            </div>
        </main>
        </div>
        @include('alert')
        @include('parts.footer')
    </body>
</html>
