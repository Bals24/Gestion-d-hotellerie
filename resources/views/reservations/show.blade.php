@extends('layouts.app')
@section('title', '📋 Réservation ' . $reservation->reservation_code)

@section('content')
<div class="py-4">
    
    <!-- En-tête -->
    <div class="page-header animate-neo">
        <h1 class="page-header__title">
            <i class="fas fa-calendar-alt"></i> Réservation {{ $reservation->reservation_code }}
        </h1>
        <span class="status-badge {{ $reservation->status }}">{{ $reservation->status }}</span>
    </div>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem;">
        
        <!-- Détails réservation -->
        <div class="neo-card animate-neo">
            <h3 style="color: var(--accent-gold); margin-bottom: 1.5rem;">
                <i class="fas fa-info-circle"></i> Détails
            </h3>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div>
                    <p style="color: var(--text-secondary); font-size: 0.85rem;">Client</p>
                    <p style="font-weight: 600;">{{ $reservation->client->full_name }}</p>
                    <p style="font-size: 0.85rem; color: var(--text-secondary);">{{ $reservation->client->email }}</p>
                    <p style="font-size: 0.85rem; color: var(--text-secondary);">{{ $reservation->client->phone }}</p>
                </div>
                <div>
                    <p style="color: var(--text-secondary); font-size: 0.85rem;">Chambre</p>
                    <p style="font-weight: 600;">{{ $reservation->room->room_number }} ({{ ucfirst($reservation->room->type) }})</p>
                    <p style="font-size: 0.85rem; color: var(--text-secondary);">Étage {{ $reservation->room->floor }}</p>
                </div>
                <div>
                    <p style="color: var(--text-secondary); font-size: 0.85rem;">Arrivée</p>
                    <p style="font-weight: 600;"><i class="fas fa-calendar-check"></i> {{ $reservation->check_in->format('d/m/Y') }}</p>
                </div>
                <div>
                    <p style="color: var(--text-secondary); font-size: 0.85rem;">Départ</p>
                    <p style="font-weight: 600;"><i class="fas fa-calendar-times"></i> {{ $reservation->check_out->format('d/m/Y') }}</p>
                </div>
                <div>
                    <p style="color: var(--text-secondary); font-size: 0.85rem;">Nuits</p>
                    <p style="font-weight: 600;">{{ $reservation->nights }} nuit(s)</p>
                </div>
                <div>
                    <p style="color: var(--text-secondary); font-size: 0.85rem;">Voyageurs</p>
                    <p style="font-weight: 600;"><i class="fas fa-users"></i> {{ $reservation->nb_guests }}</p>
                </div>
            </div>

            @if($reservation->special_requests)
            <div style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid rgba(255,255,255,0.1);">
                <p style="color: var(--text-secondary); font-size: 0.85rem;">Demandes spéciales</p>
                <p>{{ $reservation->special_requests }}</p>
            </div>
            @endif
        </div>

        <!-- Paiement -->
        <div class="neo-card animate-neo">
            <h3 style="color: var(--accent-gold); margin-bottom: 1.5rem;">
                <i class="fas fa-money-bill-wave"></i> Paiement
            </h3>
            
            <div style="margin-bottom: 1rem;">
                <p style="color: var(--text-secondary); font-size: 0.85rem;">Prix total</p>
                <p style="font-size: 1.5rem; font-weight: 700; color: var(--accent-gold);">
                    {{ number_format($reservation->total_price, 0, ',', ' ') }} Ar
                </p>
            </div>
            
            <div style="margin-bottom: 1rem;">
                <p style="color: var(--text-secondary); font-size: 0.85rem;">Déjà payé</p>
                <p style="font-size: 1.25rem; font-weight: 600; color: var(--accent-green);">
                    {{ number_format($reservation->paid_amount, 0, ',', ' ') }} Ar
                </p>
            </div>
            
            <div style="margin-bottom: 1.5rem;">
                <p style="color: var(--text-secondary); font-size: 0.85rem;">Reste à payer</p>
                <p style="font-size: 1.25rem; font-weight: 600; color: {{ $reservation->remaining_balance > 0 ? 'var(--accent-red)' : 'var(--accent-green)' }};">
                    {{ number_format($reservation->remaining_balance, 0, ',', ' ') }} Ar
                </p>
            </div>

            @if($reservation->remaining_balance > 0 && auth()->user()->hasRole(['admin','manager','receptionist']))
            <a href="{{ route('reservations.payment.create', $reservation) }}" class="neo-btn neo-btn--success" style="width: 100%; justify-content: center;">
                <i class="fas fa-credit-card"></i> Effectuer un paiement
            </a>
            @endif
        </div>
    </div>

    <!-- Actions selon statut -->
    <div style="display: flex; gap: 1rem; margin-top: 1.5rem; flex-wrap: wrap;">
        
        @if($reservation->status === 'pending' && auth()->user()->hasRole(['admin','manager','receptionist']))
        <form action="{{ route('reservations.confirm', $reservation) }}" method="POST">
            @csrf
            <button type="submit" class="neo-btn neo-btn--success">
                <i class="fas fa-check"></i> Confirmer
            </button>
        </form>
        @endif

        @if($reservation->status === 'confirmed' && auth()->user()->hasRole(['admin','manager','receptionist']))
        <form action="{{ route('reservations.checkin', $reservation) }}" method="POST">
            @csrf
            <button type="submit" class="neo-btn neo-btn--info">
                <i class="fas fa-door-open"></i> Check-in
            </button>
        </form>
        @endif

        @if($reservation->status === 'checked_in' && auth()->user()->hasRole(['admin','manager','receptionist']))
        <form action="{{ route('reservations.checkout', $reservation) }}" method="POST">
            @csrf
            <button type="submit" class="neo-btn neo-btn--warning">
                <i class="fas fa-door-closed"></i> Check-out
            </button>
        </form>
        @endif

        @if(!in_array($reservation->status, ['checked_out', 'cancelled']) && auth()->user()->hasRole(['admin','manager']))
        <form action="{{ route('reservations.cancel', $reservation) }}" method="POST" onsubmit="return confirm('Annuler cette réservation ?')">
            @csrf
            <button type="submit" class="neo-btn neo-btn--danger">
                <i class="fas fa-times"></i> Annuler
            </button>
        </form>
        @endif

        <a href="{{ route('reservations.index') }}" class="neo-btn">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>

    <!-- Historique des paiements -->
    @if($reservation->payments->count() > 0)
    <div class="neo-card animate-neo" style="margin-top: 1.5rem;">
        <h3 style="color: var(--accent-gold); margin-bottom: 1.5rem;">
            <i class="fas fa-history"></i> Historique des paiements
        </h3>
        <table class="neo-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Méthode</th>
                    <th>Référence</th>
                    <th>Montant</th>
                    <th>Statut</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reservation->payments as $payment)
                <tr>
                    <td>{{ $payment->payment_date->format('d/m/Y H:i') }}</td>
                    <td><span class="status-badge">{{ $payment->payment_method }}</span></td>
                    <td>{{ $payment->transaction_ref ?? '-' }}</td>
                    <td style="color: var(--accent-green); font-weight: 600;">
                        {{ number_format($payment->amount, 0, ',', ' ') }} Ar
                    </td>
                    <td><span class="status-badge {{ $payment->status }}">{{ $payment->status }}</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

</div>
@endsection