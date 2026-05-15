<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon-2.png') }}">
    <title>@yield('title', 'Arena de Combate - Alterdata')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @yield('styles')
</head>
<body class="bg-[#0a0e27] text-white @yield('body-class', '')">
    <!-- Floating Navigation -->
    <nav class="fixed top-4 sm:top-6 right-4 sm:right-6 z-50 flex items-center space-x-2 sm:space-x-3">
        <a href="{{ route('home') }}" class="nav-btn" title="Início">
            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="currentColor" viewBox="0 0 24 24">
                <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
            </svg>
        </a>
        <a href="{{ route('historico') }}" class="nav-btn" title="Histórico">
            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="currentColor" viewBox="0 0 24 24">
                <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
            </svg>
        </a>
    </nav>

    <!-- Alterdata Logo (Fixed) -->
    <div class="fixed top-4 sm:top-6 left-4 sm:left-6 z-50">
        <img src="{{ asset('images/Alterdata_Horizontal_branca.svg') }}" alt="Alterdata" class="h-6 sm:h-8 opacity-80 hover:opacity-100 transition">
    </div>

    <main class="@yield('main-class', 'h-screen w-screen')">
        @yield('content')
    </main>

    @yield('scripts')
</body>
</html>
