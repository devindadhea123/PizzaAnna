<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>PizzaAnna - @yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@heroicons/web@latest/dist/index.umd.js"></script>    @stack('styles')
     <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-gray-100 font-sans">

<div class="flex h-screen">
    @auth
        @if(Auth::user()->role == 'admin')
            @include('layouts.sidebar-admin')
        @else
            @include('layouts.sidebar-kasir')
        @endif
    @else
        <div class="w-72 bg-gray-800"></div>
    @endauth
    
    <main class="flex-1 overflow-y-auto">
        @yield('content')
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@stack('scripts')
</body>
</html>