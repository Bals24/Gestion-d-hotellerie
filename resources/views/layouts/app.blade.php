<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', '🏨 Hôtel Madagascar Manager')</title>
    
    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- CSS -->
    @vite(['resources/css/app.css', 'resources/css/neumorphic.css', 'resources/js/app.js'])
</head>
<body>

    <!-- 🌀 Particules d'arrière-plan -->
    <div class="particles">
        @for($i = 0; $i < 25; $i++)
        <div class="particle" style="left: {{ rand(0,100) }}%; animation-delay: {{ rand(0,20) }}s;"></div>
        @endfor
    </div>

    @auth
    <!-- 🧭 Navigation Principale -->
    <nav class="neo-nav">
        <div class="neo-nav__brand">
            <i class="fas fa-hotel"></i>
            <span>Hôtel Madagascar</span>
        </div>
        
        <div class="neo-nav__links">
            <!-- Info utilisateur -->
            <div class="neo-nav__user">
                <i class="fas fa-user-circle"></i>
                {{ auth()->user()->name }}
                <span class="role-badge">{{ auth()->user()->getRoleNames()->first() ?? 'user' }}</span>
            </div>
            
            <!-- Dashboard - TOUS -->
            <a href="{{ route('dashboard') }}" class="neo-btn neo-btn--sm">
                <i class="fas fa-chart-line"></i> Dashboard
            </a>
            
            <!-- Chambres - Admin + Manager -->
            @if(auth()->user()->hasRole(['admin', 'manager']))
            <a href="{{ route('rooms.index') }}" class="neo-btn neo-btn--sm neo-btn--primary">
                <i class="fas fa-bed"></i> Chambres
            </a>
            @endif
            
            <!-- Réservations - Admin + Manager + Réception -->
            @if(auth()->user()->hasRole(['admin', 'manager', 'receptionist']))
            <a href="{{ route('reservations.index') }}" class="neo-btn neo-btn--sm neo-btn--info">
                <i class="fas fa-calendar-alt"></i> Réservations
            </a>
            <a href="{{ route('clients.index') }}" class="neo-btn neo-btn--sm neo-btn--warning">
                <i class="fas fa-users"></i> Clients
            </a>
            @endif
            
            <!-- Paiements - Admin + Manager + Compta + Réception -->
            @if(auth()->user()->hasRole(['admin', 'manager', 'accountant', 'receptionist']))
            <a href="{{ route('payments.index') }}" class="neo-btn neo-btn--sm neo-btn--success">
                <i class="fas fa-money-bill-wave"></i> Paiements
            </a>
            @endif
            
            <!-- Déconnexion -->
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" class="neo-btn neo-btn--sm neo-btn--danger" title="Déconnexion">
                    <i class="fas fa-sign-out-alt"></i>
                </button>
            </form>
        </div>
    </nav>
    @endauth

    <!-- 🎬 Contenu Principal -->
    <main class="neo-container py-6">
        @if(session('success'))
        <div class="neo-notification success mb-6">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="neo-notification error mb-6">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
        @endif
        
        @yield('content')
    </main>

    <!-- 🎲 Scripts 3D -->
    <script src="{{ asset('js/neumorphic.js') }}"></script>
    @stack('scripts')
</body>
</html>