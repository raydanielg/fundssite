<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', config('app.name'))</title>

        <link rel="icon" type="image/jpeg" href="{{ asset(str_replace(' ', '%20', 'WhatsApp Image 2026-03-12 at 14.40.39.jpeg')) }}">
        <link rel="apple-touch-icon" href="{{ asset(str_replace(' ', '%20', 'WhatsApp Image 2026-03-12 at 14.40.39.jpeg')) }}">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="hold-transition @yield('body_class', 'login-page')">
        @yield('content')
    </body>
</html>
