@extends('layouts.app')
@section('title', '📅 Nouvelle Réservation')

@section('content')
<div class="py-4">
    
    <div class="page-header animate-neo">
        <h1 class="page-header__title"><i class="fas fa-calendar-plus"></i> Nouvelle Réservation</h1>
        <p style="color: var(--text-secondary);">Réserver une chambre pour un client</p>
    </div>

    <div class="neo-card animate-neo" style="max-width: 900px; margin: 0 auto;">
        <form action="{{ route('reservations.store') }}" method="POST" class="neo-form">
            @csrf
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                
                <!-- Client -->
                <div class="form-group">
                    <label><i class="fas fa-user"></i> Client *</label>
                    <select name="client_id" required>
                        <option value="">Sélectionner un client...</option>
                        @foreach($clients as $client)
                        <option value="{{ $client->id }}" {{ old('client_id')==$client->id?'selected':'' }}>
                            {{ $client->full_name }} ({{ $client->email }})
                        </option>
                        @endforeach
                    </select>
                    @error('client_id') <span style="color: var(--accent-red);">{{ $message }}</span> @enderror
                    <a href="{{ route('clients.create') }}" target="_blank" style="color: var(--accent-gold); font-size: 0.85rem;">+ Nouveau client</a>
                </div>

                <!-- Chambre -->
                <div class="form-group">
                    <label><i class="fas fa-bed"></i> Chambre *</label>
                    <select name="room_id" id="room_select" required onchange="updatePrice()">
                        <option value="">Sélectionner une chambre...</option>
                        @foreach($rooms as $room)
                        <option value="{{ $room->id }}" data-price="{{ $room->price_per_night }}" {{ old('room_id')==$room->id?'selected':'' }}>
                            {{ $room->room_number }} - {{ ucfirst($room->type) }} ({{ number_format($room->price_per_night, 0, ',', ' ') }} Ar)
                        </option>
                        @endforeach
                    </select>
                    @error('room_id') <span style="color: var(--accent-red);">{{ $message }}</span> @enderror
                </div>

                <!-- Check-in -->
                <div class="form-group">
                    <label><i class="fas fa-calendar-check"></i> Date d'arrivée *</label>
                    <input type="date" name="check_in" id="check_in" value="{{ old('check_in', today()->format('Y-m-d')) }}" required min="{{ today()->format('Y-m-d') }}">
                    @error('check_in') <span style="color: var(--accent-red);">{{ $message }}</span> @enderror
                </div>

                <!-- Check-out -->
                <div class="form-group">
                    <label><i class="fas fa-calendar-times"></i> Date de départ *</label>
                    <input type="date" name="check_out" id="check_out" value="{{ old('check_out') }}" required min="{{ today()->format('Y-m-d') }}">
                    @error('check_out') <span style="color: var(--accent-red);">{{ $message }}</span> @enderror
                </div>

                <!-- Nombre de personnes -->
                <div class="form-group">
                    <label><i class="fas fa-users"></i> Nombre de personnes *</label>
                    <input type="number" name="nb_guests" value="{{ old('nb_guests', 2) }}" min="1" max="10" required>
                    @error('nb_guests') <span style="color: var(--accent-red);">{{ $message }}</span> @enderror
                </div>

                <!-- Prix total -->
                <div class="form-group">
                    <label><i class="fas fa-tag"></i> Prix total estimé (Ariary)</label>
                    <input type="text" id="total_price" value="0 Ar" readonly style="background: var(--bg-dark); font-weight: 700; color: var(--accent-gold);">
                </div>
            </div>

            <!-- Demandes spéciales -->
            <div class="form-group">
                <label><i class="fas fa-comment"></i> Demandes spéciales</label>
                <textarea name="special_requests" rows="3" placeholder="Ex: Chambre calme, lit bébé, anniversaire...">{{ old('special_requests') }}</textarea>
            </div>

            <!-- Boutons -->
            <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                <button type="submit" class="neo-btn neo-btn--success" style="flex: 1; justify-content: center;">
                    <i class="fas fa-check"></i> Créer la réservation
                </button>
                <a href="{{ route('reservations.index') }}" class="neo-btn" style="flex: 1; justify-content: center;">
                    <i class="fas fa-times"></i> Annuler
                </a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function updatePrice() {
    const roomSelect = document.getElementById('room_select');
    const checkIn = document.getElementById('check_in');
    const checkOut = document.getElementById('check_out');
    const totalPriceEl = document.getElementById('total_price');
    
    const selectedOption = roomSelect.options[roomSelect.selectedIndex];
    const pricePerNight = parseFloat(selectedOption.dataset.price) || 0;
    
    if (checkIn.value && checkOut.value && pricePerNight > 0) {
        const nights = (new Date(checkOut.value) - new Date(checkIn.value)) / (1000 * 60 * 60 * 24);
        if (nights > 0) {
            const total = Math.floor(nights * pricePerNight);
            totalPriceEl.value = total.toLocaleString('fr-MG') + ' Ar';
        }
    }
}
document.getElementById('check_in').addEventListener('change', updatePrice);
document.getElementById('check_out').addEventListener('change', updatePrice);
updatePrice();
</script>
@endpush
@endsection