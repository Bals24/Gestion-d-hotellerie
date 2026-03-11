@extends('layouts.app')
@section('title', '➕ Nouvelle Chambre')

@section('content')
<div class="py-4">
    
    <div class="page-header animate-neo">
        <h1 class="page-header__title"><i class="fas fa-plus"></i> Nouvelle Chambre</h1>
        <p style="color: var(--text-secondary);">Ajouter une chambre à l'hôtel</p>
    </div>

    <div class="neo-card animate-neo" style="max-width: 800px; margin: 0 auto;">
        <form action="{{ route('rooms.store') }}" method="POST" class="neo-form">
            @csrf
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                
                <!-- Numéro -->
                <div class="form-group">
                    <label><i class="fas fa-door-open"></i> Numéro de chambre *</label>
                    <input type="text" name="room_number" value="{{ old('room_number') }}" required placeholder="Ex: A101, B205">
                    @error('room_number') <span style="color: var(--accent-red); font-size: 0.85rem;">{{ $message }}</span> @enderror
                </div>

                <!-- Type -->
                <div class="form-group">
                    <label><i class="fas fa-bed"></i> Type *</label>
                    <select name="type" required>
                        <option value="">Sélectionner...</option>
                        <option value="single" {{ old('type')=='single'?'selected':'' }}>Single (1 personne)</option>
                        <option value="double" {{ old('type')=='double'?'selected':'' }}>Double (2 personnes)</option>
                        <option value="suite" {{ old('type')=='suite'?'selected':'' }}>Suite (3-4 personnes)</option>
                        <option value="presidential" {{ old('type')=='presidential'?'selected':'' }}>Presidential (VIP)</option>
                        <option value="bungalow" {{ old('type')=='bungalow'?'selected':'' }}>Bungalow</option>
                    </select>
                    @error('type') <span style="color: var(--accent-red); font-size: 0.85rem;">{{ $message }}</span> @enderror
                </div>

                <!-- Étage -->
                <div class="form-group">
                    <label><i class="fas fa-layer-group"></i> Étage *</label>
                    <input type="number" name="floor" value="{{ old('floor', 0) }}" min="0" max="20" required>
                    @error('floor') <span style="color: var(--accent-red); font-size: 0.85rem;">{{ $message }}</span> @enderror
                </div>

                <!-- Capacité -->
                <div class="form-group">
                    <label><i class="fas fa-users"></i> Capacité *</label>
                    <input type="number" name="capacity" value="{{ old('capacity', 2) }}" min="1" max="10" required>
                    @error('capacity') <span style="color: var(--accent-red); font-size: 0.85rem;">{{ $message }}</span> @enderror
                </div>

                <!-- Prix -->
                <div class="form-group">
                    <label><i class="fas fa-tag"></i> Prix par nuit (Ariary) *</label>
                    <input type="number" name="price_per_night" value="{{ old('price_per_night') }}" min="0" required placeholder="Ex: 250000">
                    @error('price_per_night') <span style="color: var(--accent-red); font-size: 0.85rem;">{{ $message }}</span> @enderror
                </div>

                <!-- Statut -->
                <div class="form-group">
                    <label><i class="fas fa-info-circle"></i> Statut *</label>
                    <select name="status" required>
                        <option value="available" {{ old('status')=='available'?'selected':'' }}>Disponible</option>
                        <option value="occupied" {{ old('status')=='occupied'?'selected':'' }}>Occupée</option>
                        <option value="maintenance" {{ old('status')=='maintenance'?'selected':'' }}>Maintenance</option>
                        <option value="reserved" {{ old('status')=='reserved'?'selected':'' }}>Réservée</option>
                    </select>
                    @error('status') <span style="color: var(--accent-red); font-size: 0.85rem;">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Équipements -->
            <div class="form-group">
                <label><i class="fas fa-sliders-h"></i> Équipements</label>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 1rem; margin-top: 0.5rem;">
                    <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                        <input type="checkbox" name="has_wifi" value="1" {{ old('has_wifi', true) ? 'checked' : '' }}>
                        <i class="fas fa-wifi"></i> WiFi
                    </label>
                    <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                        <input type="checkbox" name="has_ac" value="1" {{ old('has_ac', true) ? 'checked' : '' }}>
                        <i class="fas fa-snowflake"></i> Climatisation
                    </label>
                    <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                        <input type="checkbox" name="has_sea_view" value="1" {{ old('has_sea_view') ? 'checked' : '' }}>
                        <i class="fas fa-water"></i> Vue mer
                    </label>
                    <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                        <input type="checkbox" name="has_balcony" value="1" {{ old('has_balcony') ? 'checked' : '' }}>
                        <i class="fas fa-door-open"></i> Balcon
                    </label>
                    <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                        <input type="checkbox" name="has_breakfast" value="1" {{ old('has_breakfast', true) ? 'checked' : '' }}>
                        <i class="fas fa-coffee"></i> Petit-déjeuner
                    </label>
                </div>
            </div>

            <!-- Description -->
            <div class="form-group">
                <label><i class="fas fa-align-left"></i> Description</label>
                <textarea name="description" rows="3" placeholder="Description de la chambre...">{{ old('description') }}</textarea>
                @error('description') <span style="color: var(--accent-red); font-size: 0.85rem;">{{ $message }}</span> @enderror
            </div>

            <!-- Boutons -->
            <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                <button type="submit" class="neo-btn neo-btn--success" style="flex: 1; justify-content: center;">
                    <i class="fas fa-check"></i> Créer la chambre
                </button>
                <a href="{{ route('rooms.index') }}" class="neo-btn" style="flex: 1; justify-content: center;">
                    <i class="fas fa-times"></i> Annuler
                </a>
            </div>
        </form>
    </div>
</div>
@endsection