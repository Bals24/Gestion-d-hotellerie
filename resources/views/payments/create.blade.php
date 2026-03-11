@extends('layouts.app')
@section('title', '💳 Paiement - ' . $reservation->reservation_code)

@section('content')
<div class="py-4">
    
    <!-- En-tête -->
    <div class="page-header animate-neo">
        <h1 class="page-header__title">
            <i class="fas fa-credit-card"></i> Effectuer un Paiement
        </h1>
        <p style="color: var(--text-secondary);">
            Réservation : {{ $reservation->reservation_code }}
        </p>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
        
        <!-- Résumé de la réservation -->
        <div class="neo-card animate-neo">
            <h3 style="color: var(--accent-gold); margin-bottom: 1.5rem;">
                <i class="fas fa-file-invoice"></i> Résumé
            </h3>
            
            <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                
                <div style="display: flex; justify-content: space-between;">
                    <span style="color: var(--text-secondary);">Client</span>
                    <span style="font-weight: 600;">{{ $reservation->client->full_name }}</span>
                </div>
                
                <div style="display: flex; justify-content: space-between;">
                    <span style="color: var(--text-secondary);">Chambre</span>
                    <span style="font-weight: 600;">{{ $reservation->room->room_number }}</span>
                </div>
                
                <div style="display: flex; justify-content: space-between;">
                    <span style="color: var(--text-secondary);">Dates</span>
                    <span style="font-weight: 600;">
                        {{ $reservation->check_in->format('d/m/Y') }} → {{ $reservation->check_out->format('d/m/Y') }}
                    </span>
                </div>
                
                <div style="display: flex; justify-content: space-between; padding-top: 1rem; border-top: 1px solid rgba(255,255,255,0.1);">
                    <span style="color: var(--text-secondary);">Prix total</span>
                    <span style="font-weight: 700; color: var(--accent-gold);">
                        {{ number_format($reservation->total_price, 0, ',', ' ') }} Ar
                    </span>
                </div>
                
                <div style="display: flex; justify-content: space-between;">
                    <span style="color: var(--text-secondary);">Déjà payé</span>
                    <span style="font-weight: 600; color: var(--accent-green);">
                        {{ number_format($reservation->paid_amount, 0, ',', ' ') }} Ar
                    </span>
                </div>
                
                <div style="display: flex; justify-content: space-between; padding-top: 1rem; border-top: 1px solid rgba(255,255,255,0.1);">
                    <span style="color: var(--text-secondary); font-weight: 600;">💰 Reste à payer</span>
                    <span style="font-size: 1.25rem; font-weight: 700; color: var(--accent-red);">
                        {{ number_format($reservation->remaining_balance, 0, ',', ' ') }} Ar
                    </span>
                </div>
            </div>
        </div>

        <!-- Formulaire de paiement -->
        <div class="neo-card animate-neo">
            <h3 style="color: var(--accent-gold); margin-bottom: 1.5rem;">
                <i class="fas fa-money-bill-wave"></i> Détails du Paiement
            </h3>
            
            {{-- ✅ FORMULAIRE AVEC METHOD="POST" --}}
            <form action="{{ route('reservations.payment.store', $reservation) }}" method="POST" class="neo-form">
                @csrf
                
                <!-- Montant -->
                <div class="form-group">
                    <label for="amount"><i class="fas fa-tag"></i> Montant (Ariary) *</label>
                    <input type="number" 
                           id="amount" 
                           name="amount" 
                           value="{{ old('amount', $reservation->remaining_balance) }}" 
                           max="{{ $reservation->remaining_balance }}" 
                           min="1"
                           required 
                           style="font-weight: 700; color: var(--accent-gold); font-size: 1.1rem;">
                    @error('amount') 
                        <span style="color: var(--accent-red); font-size: 0.85rem;">{{ $message }}</span> 
                    @enderror
                    <small style="color: var(--text-secondary);">
                        Maximum : {{ number_format($reservation->remaining_balance, 0, ',', ' ') }} Ar
                    </small>
                </div>

                <!-- Méthode de paiement -->
                <div class="form-group">
                    <label for="payment_method"><i class="fas fa-wallet"></i> Méthode de paiement *</label>
                    <select id="payment_method" name="payment_method" required>
                        <option value="">-- Sélectionner --</option>
                        <option value="cash" {{ old('payment_method')=='cash'?'selected':'' }}>
                            💵 Espèces (Cash)
                        </option>
                        <option value="card" {{ old('payment_method')=='card'?'selected':'' }}>
                            💳 Carte bancaire
                        </option>
                        <option value="mvola" {{ old('payment_method')=='mvola'?'selected':'' }}>
                            📱 Mvola (Madagascar)
                        </option>
                        <option value="orange_money" {{ old('payment_method')=='orange_money'?'selected':'' }}>
                            📱 Orange Money
                        </option>
                        <option value="bank_transfer" {{ old('payment_method')=='bank_transfer'?'selected':'' }}>
                            🏦 Virement bancaire
                        </option>
                    </select>
                    @error('payment_method') 
                        <span style="color: var(--accent-red); font-size: 0.85rem;">{{ $message }}</span> 
                    @enderror
                </div>

                <!-- Référence transaction -->
                <div class="form-group">
                    <label for="transaction_ref"><i class="fas fa-receipt"></i> Référence de transaction</label>
                    <input type="text" 
                           id="transaction_ref" 
                           name="transaction_ref" 
                           value="{{ old('transaction_ref') }}" 
                           placeholder="Ex: MV123456789, TXN-2024-001">
                    <small style="color: var(--text-secondary);">
                        Pour Mvola/Orange Money : entrez le code de confirmation
                    </small>
                </div>

                <!-- Notes -->
                <div class="form-group">
                    <label for="notes"><i class="fas fa-comment"></i> Notes (optionnel)</label>
                    <textarea id="notes" name="notes" rows="2" placeholder="Notes supplémentaires...">{{ old('notes') }}</textarea>
                </div>

                <!-- Boutons -->
                <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                    <button type="submit" class="neo-btn neo-btn--success" style="flex: 1; justify-content: center;">
                        <i class="fas fa-check"></i> Valider le paiement
                    </button>
                    <a href="{{ route('reservations.show', $reservation) }}" class="neo-btn" style="flex: 1; justify-content: center;">
                        <i class="fas fa-times"></i> Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection