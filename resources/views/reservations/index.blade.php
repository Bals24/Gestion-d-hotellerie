@extends('layouts.app')
@section('title', '📅 Gestion des Réservations')

@section('content')
<div class="py-4">
    
    <div class="page-header animate-neo">
        <h1 class="page-header__title"><i class="fas fa-calendar-alt"></i> Gestion des Réservations</h1>
        <p style="color: var(--text-secondary);">{{ $reservations->total() }} réservations</p>
    </div>

    <!-- Actions -->
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem; flex-wrap:wrap; gap:1rem;">
        <div style="display:flex; gap:0.5rem; flex-wrap:wrap;">
            <a href="{{ route('reservations.create') }}" class="neo-btn neo-btn--success">
                <i class="fas fa-plus"></i> Nouvelle
            </a>
            <a href="{{ route('dashboard') }}" class="neo-btn">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </div>
        <form method="GET" style="display:flex; gap:0.5rem; flex-wrap:wrap;">
            <input type="text" name="search" placeholder="Rechercher..." value="{{ request('search') }}" class="neo-form" style="width:200px;">
            <select name="status" class="neo-form" style="width:auto;">
                <option value="">Tous statuts</option>
                <option value="pending" {{ request('status')=='pending'?'selected':'' }}>En attente</option>
                <option value="confirmed" {{ request('status')=='confirmed'?'selected':'' }}>Confirmée</option>
                <option value="checked_in" {{ request('status')=='checked_in'?'selected':'' }}>Check-in</option>
                <option value="checked_out" {{ request('status')=='checked_out'?'selected':'' }}>Check-out</option>
                <option value="cancelled" {{ request('status')=='cancelled'?'selected':'' }}>Annulée</option>
            </select>
            <button type="submit" class="neo-btn neo-btn--primary"><i class="fas fa-search"></i></button>
        </form>
    </div>

    <!-- Tableau -->
    <div class="neo-card animate-neo">
        <div style="overflow-x:auto;">
            <table class="neo-table">
                <thead>
                    <tr>
                        <th>Code</th><th>Client</th><th>Chambre</th><th>Arrivée</th><th>Départ</th><th>Nuits</th><th>Total</th><th>Statut</th><th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reservations as $res)
                    <tr>
                        <td style="font-family:monospace;">{{ $res->reservation_code }}</td>
                        <td>{{ $res->client->full_name }}</td>
                        <td>{{ $res->room->room_number }}</td>
                        <td>{{ $res->check_in->format('d/m/Y') }}</td>
                        <td>{{ $res->check_out->format('d/m/Y') }}</td>
                        <td>{{ $res->nights }}</td>
                        <td style="color:var(--accent-gold); font-weight:600;">{{ number_format($res->total_price,0,',',' ') }} Ar</td>
                        <td><span class="status-badge {{ $res->status }}">{{ $res->status }}</span></td>
                        <td>
                            <div style="display:flex; gap:0.25rem;">
                                <a href="{{ route('reservations.show',$res) }}" class="neo-btn neo-btn--sm neo-btn--primary"><i class="fas fa-eye"></i></a>
                                @if($res->status==='pending')
                                <form action="{{ route('reservations.confirm',$res) }}" method="POST">@csrf<button class="neo-btn neo-btn--sm neo-btn--success"><i class="fas fa-check"></i></button></form>
                                @endif
                                @if($res->status==='confirmed')
                                <form action="{{ route('reservations.checkin',$res) }}" method="POST">@csrf<button class="neo-btn neo-btn--sm neo-btn--info"><i class="fas fa-door-open"></i></button></form>
                                @endif
                                @if($res->status==='checked_in')
                                <form action="{{ route('reservations.checkout',$res) }}" method="POST">@csrf<button class="neo-btn neo-btn--sm neo-btn--warning"><i class="fas fa-door-closed"></i></button></form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="9" style="text-align:center; padding:2rem; color:var(--text-secondary);">Aucune réservation</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div style="margin-top:2rem;">{{ $reservations->links() }}</div>
</div>
@endsection