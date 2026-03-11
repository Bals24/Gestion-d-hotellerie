@extends('layouts.app')
@section('title', '👤 ' . $client->full_name)

@section('content')
<div class="py-4">
    
    <div class="page-header animate-neo">
        <h1 class="page-header__title"><i class="fas fa-user"></i> {{ $client->full_name }}</h1>
    </div>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem;">
        
        <div class="neo-card animate-neo">
            <h3 style="color: var(--accent-gold); margin-bottom: 1.5rem;"><i class="fas fa-info-circle"></i> Informations</h3>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div>
                    <p style="color: var(--text-secondary); font-size: 0.85rem;">Email</p>
                    <p style="font-weight: 600;">{{ $client->email }}</p>
                </div>
                <div>
                    <p style="color: var(--text-secondary); font-size: 0.85rem;">Téléphone</p>
                    <p style="font-weight: 600;">{{ $client->phone }}</p>
                </div>
                <div>
                    <p style="color: var(--text-secondary); font-size: 0.85rem;">CIN</p>
                    <p style="font-weight: 600;">{{ $client->cin ?? '-' }}</p>
                </div>
                <div>
                    <p style="color: var(--text-secondary); font-size: 0.85rem;">Ville</p>
                    <p style="font-weight: 600;">{{ $client->city ?? '-' }}</p>
                </div>
                <div>
                    <p style="color: var(--text-secondary); font-size: 0.85rem;">Pays</p>
                    <p style="font-weight: 600;">{{ $client->country }}</p>
                </div>
                <div>
                    <p style="color: var(--text-secondary); font-size: 0.85rem;">Réservations</p>
                    <p style="font-weight: 600;">{{ $client->reservations->count() }}</p>
                </div>
            </div>

            @if($client->address)
            <div style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid rgba(255,255,255,0.1);">
                <p style="color: var(--text-secondary); font-size: 0.85rem;">Adresse</p>
                <p>{{ $client->address }}</p>
            </div>
            @endif
        </div>

        <div class="neo-card animate-neo">
            <h3 style="color: var(--accent-gold); margin-bottom: 1.5rem;"><i class="fas fa-chart-line"></i> Statistiques</h3>
            <div style="text-align: center;">
                <p style="color: var(--text-secondary); font-size: 0.85rem;">Total dépensé</p>
                <p style="font-size: 1.5rem; font-weight: 700; color: var(--accent-gold);">
                    {{ number_format($client->total_spent ?? 0, 0, ',', ' ') }} Ar
                </p>
            </div>
        </div>
    </div>

    <!-- Historique réservations -->
    @if($client->reservations->count() > 0)
    <div class="neo-card animate-neo" style="margin-top: 1.5rem;">
        <h3 style="color: var(--accent-gold); margin-bottom: 1.5rem;"><i class="fas fa-history"></i> Historique</h3>
        <table class="neo-table">
            <thead><tr><th>Code</th><th>Chambre</th><th>Arrivée</th><th>Départ</th><th>Total</th><th>Statut</th></tr></thead>
            <tbody>
                @foreach($client->reservations->sortByDesc('created_at') as $res)
                <tr>
                    <td style="font-family:monospace;">{{ $res->reservation_code }}</td>
                    <td>{{ $res->room->room_number }}</td>
                    <td>{{ $res->check_in->format('d/m/Y') }}</td>
                    <td>{{ $res->check_out->format('d/m/Y') }}</td>
                    <td style="color: var(--accent-gold);">{{ number_format($res->total_price, 0, ',', ' ') }} Ar</td>
                    <td><span class="status-badge {{ $res->status }}">{{ $res->status }}</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
        <a href="{{ route('clients.edit', $client) }}" class="neo-btn neo-btn--primary"><i class="fas fa-edit"></i> Modifier</a>
        <a href="{{ route('clients.index') }}" class="neo-btn"><i class="fas fa-arrow-left"></i> Retour</a>
    </div>
</div>
@endsection