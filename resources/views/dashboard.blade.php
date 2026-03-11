@extends('layouts.app')
@section('title', '🎛️ Dashboard')

@section('content')
<div class="py-4">
    
    <!-- 🎯 Header -->
    <div class="page-header animate-neo">
        <h1 class="page-header__title">
            <i class="fas fa-hotel"></i>Gestion de l'Hotel
        </h1>
        <p style="color: var(--text-secondary);">
            {{ now()->format('d F Y') }} • 
            Bonjour {{ auth()->user()->name }}
            <span class="role-badge">{{ auth()->user()->getRoleNames()->first() ?? 'user' }}</span>
        </p>
    </div>

    <!-- 📊 Statistiques -->
    <div class="stats-grid">
        @php
        $statConfig = [
            'total_rooms' => ['icon' => 'bed', 'color' => 'var(--accent-gold)', 'label' => 'Total Chambres'],
            'available' => ['icon' => 'check-circle', 'color' => 'var(--accent-green)', 'label' => 'Disponibles'],
            'occupied' => ['icon' => 'door-closed', 'color' => 'var(--accent-red)', 'label' => 'Occupées'],
            'today_arrivals' => ['icon' => 'calendar-check', 'color' => 'var(--accent-blue)', 'label' => 'Arrivées aujourd\'hui'],
            'revenue_month' => ['icon' => 'euro-sign', 'color' => 'var(--accent-green)', 'label' => 'Revenus du mois'],
        ];
        @endphp
        
        @foreach($stats as $key => $value)
        <div class="neo-card stat-card animate-neo">
            <div class="stat-card__icon" style="color: {{ $statConfig[$key]['color'] }}">
                <i class="fas fa-{{ $statConfig[$key]['icon'] }}"></i>
            </div>
            <h3 class="stat-card__value">
                @if(str_contains($key, 'revenue')){{ number_format($value, 0, ',', ' ') }} Ar
                @else{{ $value }}@endif
            </h3>
            <p class="stat-card__label">{{ $statConfig[$key]['label'] }}</p>
        </div>
        @endforeach
    </div>

    <!--  Actions Rapides -->
    <div class="neo-card mb-6 animate-neo">
        <h3 class="text-lg font-bold mb-4" style="color: var(--accent-gold);"> Accès Rapide</h3>
        <div class="quick-actions">
            
            @if(auth()->user()->hasRole(['admin', 'manager']))
            <a href="{{ route('rooms.index') }}" class="neo-card action-card">
                <div class="action-card__icon" style="color: var(--accent-gold);"><i class="fas fa-bed"></i></div>
                <span class="action-card__label">Voir Chambres</span>
            </a>
            <a href="{{ route('rooms.create') }}" class="neo-card action-card">
                <div class="action-card__icon" style="color: var(--accent-green);"><i class="fas fa-plus"></i></div>
                <span class="action-card__label">Ajouter Chambre</span>
            </a>
            @endif

            @if(auth()->user()->hasRole(['admin', 'manager', 'receptionist']))
            <a href="{{ route('reservations.index') }}" class="neo-card action-card">
                <div class="action-card__icon" style="color: var(--accent-blue);"><i class="fas fa-calendar-alt"></i></div>
                <span class="action-card__label">Voir Réservations</span>
            </a>
            <a href="{{ route('reservations.create') }}" class="neo-card action-card">
                <div class="action-card__icon" style="color: var(--accent-green);"><i class="fas fa-calendar-plus"></i></div>
                <span class="action-card__label">Nouvelle Réservation</span>
            </a>
            <a href="{{ route('clients.index') }}" class="neo-card action-card">
                <div class="action-card__icon" style="color: var(--accent-purple);"><i class="fas fa-users"></i></div>
                <span class="action-card__label">Voir Clients</span>
            </a>
            @endif

            @if(auth()->user()->hasRole(['admin', 'manager', 'accountant']))
            <a href="{{ route('payments.index') }}" class="neo-card action-card">
                <div class="action-card__icon" style="color: var(--accent-green);"><i class="fas fa-money-bill-wave"></i></div>
                <span class="action-card__label">Voir Paiements</span>
            </a>
            <a href="{{ route('exports.bookings') }}" class="neo-card action-card">
                <div class="action-card__icon" style="color: var(--accent-gold);"><i class="fas fa-file-excel"></i></div>
                <span class="action-card__label">Export Excel</span>
            </a>
            @endif
        </div>
    </div>

    <!-- 📋 Réservations récentes -->
    <div class="neo-card animate-neo">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem;">
            <h3 class="text-lg font-bold" style="color: var(--accent-gold);">📋 Dernières Réservations</h3>
            @if(auth()->user()->hasRole(['admin', 'manager', 'receptionist']))
            <a href="{{ route('reservations.index') }}" style="color: var(--accent-gold); text-decoration:none;">Voir tout →</a>
            @endif
        </div>
        
        <div style="overflow-x:auto;">
            <table class="neo-table">
                <thead>
                    <tr>
                        <th>Code</th><th>Client</th><th>Chambre</th><th>Arrivée</th><th>Départ</th><th>Statut</th><th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentReservations ?? [] as $res)
                    <tr>
                        <td style="font-family:monospace;">{{ $res->reservation_code }}</td>
                        <td>{{ $res->client->full_name }}</td>
                        <td>{{ $res->room->room_number }}</td>
                        <td>{{ $res->check_in->format('d/m') }}</td>
                        <td>{{ $res->check_out->format('d/m') }}</td>
                        <td><span class="status-badge {{ $res->status }}">{{ $res->status }}</span></td>
                        <td>
                            <a href="{{ route('reservations.show', $res) }}" style="color: var(--accent-gold);">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" style="text-align:center; padding:2rem; color:var(--text-secondary);">Aucune réservation récente</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection