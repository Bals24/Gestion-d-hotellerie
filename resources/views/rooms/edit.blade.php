@extends('layouts.app')
@section('title', '✏️ Modifier Chambre')

@section('content')
<div class="py-4">
    
    <div class="page-header animate-neo">
        <h1 class="page-header__title"><i class="fas fa-edit"></i> Modifier {{ $room->room_number }}</h1>
    </div>

    <div class="neo-card animate-neo" style="max-width: 800px; margin: 0 auto;">
        <form action="{{ route('rooms.update', $room) }}" method="POST" class="neo-form">
            @csrf @method('PUT')
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                
                <div class="form-group">
                    <label><i class="fas fa-door-open"></i> Numéro *</label>
                    <input type="text" name="room_number" value="{{ old('room_number', $room->room_number) }}" required>
                    @error('room_number') <span style="color: var(--accent-red);">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label><i class="fas fa-bed"></i> Type *</label>
                    <select name="type" required>
                        @foreach(['single','double','suite','presidential','bungalow'] as $t)
                        <option value="{{ $t }}" {{ old('type', $room->type)==$t?'selected':'' }}>{{ ucfirst($t) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label><i class="fas fa-layer-group"></i> Étage *</label>
                    <input type="number" name="floor" value="{{ old('floor', $room->floor) }}" min="0" max="20" required>
                </div>

                <div class="form-group">
                    <label><i class="fas fa-users"></i> Capacité *</label>
                    <input type="number" name="capacity" value="{{ old('capacity', $room->capacity) }}" min="1" max="10" required>
                </div>

                <div class="form-group">
                    <label><i class="fas fa-tag"></i> Prix (Ar) *</label>
                    <input type="number" name="price_per_night" value="{{ old('price_per_night', $room->price_per_night) }}" min="0" required>
                </div>

                <div class="form-group">
                    <label><i class="fas fa-info-circle"></i> Statut *</label>
                    <select name="status" required>
                        @foreach(['available','occupied','maintenance','reserved'] as $s)
                        <option value="{{ $s }}" {{ old('status', $room->status)==$s?'selected':'' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label><i class="fas fa-sliders-h"></i> Équipements</label>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 1rem;">
                    @foreach(['has_wifi'=>'WiFi','has_ac'=>'Climatisation','has_sea_view'=>'Vue mer','has_balcony'=>'Balcon','has_breakfast'=>'Petit-déjeuner'] as $key=>$label)
                    <label style="display:flex;align-items:center;gap:0.5rem;">
                        <input type="checkbox" name="{{ $key }}" value="1" {{ old($key, $room->$key) ? 'checked' : '' }}> {{ $label }}
                    </label>
                    @endforeach
                </div>
            </div>

            <div class="form-group">
                <label><i class="fas fa-align-left"></i> Description</label>
                <textarea name="description" rows="3">{{ old('description', $room->description) }}</textarea>
            </div>

            <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                <button type="submit" class="neo-btn neo-btn--success" style="flex:1;justify-content:center;">
                    <i class="fas fa-save"></i> Enregistrer
                </button>
                <a href="{{ route('rooms.index') }}" class="neo-btn neo-btn--danger" style="flex:1;justify-content:center;">
                    <i class="fas fa-times"></i> Annuler
                </a>
            </div>
        </form>
    </div>
</div>
@endsection