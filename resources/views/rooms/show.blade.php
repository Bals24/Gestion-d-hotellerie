@extends('layouts.app')
@section('title', '🛏️ Chambre ' . $room->room_number)

@section('content')
<div class="py-4">
    
    <div class="page-header animate-neo">
        <h1 class="page-header__title"><i class="fas fa-bed"></i> Chambre {{ $room->room_number }}</h1>
        <span class="status-badge {{ $room->status }}">{{ $room->status }}</span>
    </div>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem;">
        
        <!-- Infos principales -->
        <div class="neo-card animate-neo">
            <h3 style="color: var(--accent-gold); margin-bottom: 1.5rem;"><i class="fas fa-info-circle"></i> Informations</h3>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div>
                    <p style="color: var(--text-secondary); font-size: 0.85rem;">Type</p>
                    <p style="font-weight: 600; text-transform: capitalize;">{{ ucfirst($room->type) }}</p>
                </div>
                <div>
                    <p style="color: var(--text-secondary); font-size: 0.85rem;">Étage</p>
                    <p style="font-weight: 600;">{{ $room->floor }}<sup>ème</sup></p>
                </div>
                <div>
                    <p style="color: var(--text-secondary); font-size: 0.85rem;">Capacité</p>
                    <p style="font-weight: 600;"><i class="fas fa-users"></i> {{ $room->capacity }} personnes</p>
                </div>
                <div>
                    <p style="color: var(--text-secondary); font-size: 0.85rem;">Prix / nuit</p>
                    <p style="font-weight: 700; color: var(--accent-gold); font-size: 1.25rem;">
                        {{ number_format($room->price_per_night, 0, ',', ' ') }} Ar
                    </p>
                </div>
            </div>

            @if($room->description)
            <div style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid rgba(255,255,255,0.1);">
                <p style="color: var(--text-secondary); font-size: 0.85rem;">Description</p>
                <p>{{ $room->description }}</p>
            </div>
            @endif
        </div>

        <!-- Équipements -->
        <div class="neo-card animate-neo">
            <h3 style="color: var(--accent-gold); margin-bottom: 1.5rem;"><i class="fas fa-sliders-h"></i> Équipements</h3>
            <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                @foreach(['has_wifi'=>'WiFi','has_ac'=>'Climatisation','has_sea_view'=>'Vue mer','has_balcony'=>'Balcon','has_breakfast'=>'Petit-déjeuner'] as $key=>$label)
                <div style="display:flex;align-items:center;gap:0.5rem;">
                    <i class="fas fa-{{ $room->$key ? 'check-circle' : 'times-circle' }}" style="color: {{ $room->$key ? 'var(--accent-green)' : 'var(--text-secondary)' }};"></i>
                    <span>{{ $label }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
        @if(auth()->user()->hasRole(['admin', 'manager']))
        <a href="{{ route('rooms.edit', $room) }}" class="neo-btn neo-btn--primary">
            <i class="fas fa-edit"></i> Modifier
        </a>
        <form action="{{ route('rooms.destroy', $room) }}" method="POST" onsubmit="return confirm('Supprimer cette chambre ?')">
            @csrf @method('DELETE')
            <button type="submit" class="neo-btn neo-btn--danger">
                <i class="fas fa-trash"></i> Supprimer
            </button>
        </form>
        @endif
        <a href="{{ route('rooms.index') }}" class="neo-btn">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>
</div>
@endsection