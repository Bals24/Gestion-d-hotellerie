@extends('layouts.app')
@section('title', '💰 Gestion des Paiements')

@section('content')
<div class="py-4">
    
    <div class="page-header animate-neo">
        <h1 class="page-header__title"><i class="fas fa-money-bill-wave"></i> Gestion des Paiements</h1>
        <p style="color: var(--text-secondary);">{{ $payments->total() }} paiements enregistrés</p>
    </div>

    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem;">
        <a href="{{ route('dashboard') }}" class="neo-btn"><i class="fas fa-arrow-left"></i> Retour Dashboard</a>
        <form method="GET" style="display:flex; gap:0.5rem;">
            <select name="method" class="neo-form" style="width:auto;">
                <option value="">Toutes méthodes</option>
                <option value="cash" {{ request('method')=='cash'?'selected':'' }}>Espèces</option>
                <option value="card" {{ request('method')=='card'?'selected':'' }}>Carte</option>
                <option value="mvola" {{ request('method')=='mvola'?'selected':'' }}>Mvola</option>
                <option value="orange_money" {{ request('method')=='orange_money'?'selected':'' }}>Orange Money</option>
                <option value="bank_transfer" {{ request('method')=='bank_transfer'?'selected':'' }}>Virement</option>
            </select>
            <button type="submit" class="neo-btn neo-btn--primary"><i class="fas fa-filter"></i></button>
        </form>
    </div>

    <div class="neo-card animate-neo">
        <table class="neo-table">
            <thead>
                <tr><th>Réservation</th><th>Client</th><th>Méthode</th><th>Montant</th><th>Date</th><th>Statut</th></tr>
            </thead>
            <tbody>
                @forelse($payments as $payment)
                <tr>
                    <td style="font-family:monospace;">{{ $payment->reservation->reservation_code }}</td>
                    <td>{{ $payment->reservation->client->full_name }}</td>
                    <td><span class="status-badge">{{ str_replace('_',' ',$payment->payment_method) }}</span></td>
                    <td style="color:var(--accent-green); font-weight:600;">{{ number_format($payment->amount,0,',',' ') }} Ar</td>
                    <td>{{ $payment->payment_date->format('d/m/Y H:i') }}</td>
                    <td><span class="status-badge {{ $payment->status }}">{{ $payment->status }}</span></td>
                </tr>
                @empty
                <tr><td colspan="6" style="text-align:center; padding:2rem;">Aucun paiement</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:2rem;">{{ $payments->links() }}</div>
</div>
@endsection