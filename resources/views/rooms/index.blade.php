@extends('layouts.app')

@section('title','Gestion des Chambres')

@section('content')

<div class="py-4">

```
<div class="page-header animate-neo">
    <h1 class="page-header__title">
        <i class="fas fa-bed"></i> Gestion des Chambres
    </h1>
    <p style="color: var(--text-secondary);">
        {{ $rooms->total() }} chambres au total
    </p>
</div>

<!-- Actions -->
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem;">
    
    <div style="display:flex; gap:0.5rem;">
        <a href="{{ route('rooms.create') }}" class="neo-btn neo-btn--success">
            <i class="fas fa-plus"></i> Ajouter
        </a>

        <a href="{{ route('dashboard') }}" class="neo-btn">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>

    <form method="GET" style="display:flex; gap:0.5rem;">
        <select name="status" class="neo-form" style="width:auto;">
            <option value="">Tous statuts</option>

            <option value="available"
                {{ request('status')=='available' ? 'selected' : '' }}>
                Disponible
            </option>

            <option value="occupied"
                {{ request('status')=='occupied' ? 'selected' : '' }}>
                Occupée
            </option>

            <option value="maintenance"
                {{ request('status')=='maintenance' ? 'selected' : '' }}>
                Maintenance
            </option>
        </select>

        <button type="submit" class="neo-btn neo-btn--primary">
            <i class="fas fa-filter"></i> Filtrer
        </button>
    </form>

</div>


<!-- Grille des chambres -->
<div class="stats-grid" style="grid-template-columns:repeat(auto-fill,minmax(300px,1fr));">

    @foreach($rooms as $room)

    <div class="neo-card animate-neo">

        <div style="display:flex; justify-content:space-between; align-items:start; margin-bottom:1rem;">
            
            <div>
                <h3 style="font-size:1.25rem; font-weight:700;">
                    {{ $room->room_number }}
                </h3>

                <p style="color:var(--text-secondary); font-size:0.9rem;">
                    {{ ucfirst($room->type) }} • 
                    {{ $room->floor }}<sup>ème</sup> étage
                </p>
            </div>

            <span class="status-badge {{ $room->status }}">
                {{ $room->status }}
            </span>

        </div>


        <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.5rem; margin-bottom:1rem; font-size:0.85rem;">

            <div>
                <i class="fas fa-user"></i>
                {{ $room->capacity }} pers.
            </div>

            <div>
                <i class="fas fa-wifi"></i>
                {{ $room->has_wifi ? 'Oui' : 'Non' }}
            </div>

            <div>
                <i class="fas fa-snowflake"></i>
                {{ $room->has_ac ? 'Oui' : 'Non' }}
            </div>

            <div>
                <i class="fas fa-water"></i>
                {{ $room->has_sea_view ? 'Vue mer' : 'Sans vue' }}
            </div>

        </div>


        <div style="font-size:1.5rem; font-weight:700; color:var(--accent-gold); margin-bottom:1rem;">
            {{ number_format($room->price_per_night,0,',',' ') }} Ar
            <span style="font-size:0.9rem; color:var(--text-secondary);">
                /nuit
            </span>
        </div>


        <div style="display:flex; gap:0.5rem;">

            <a href="{{ route('rooms.show',$room) }}"
               class="neo-btn neo-btn--sm neo-btn--primary"
               style="flex:1; justify-content:center;">
                <i class="fas fa-eye"></i> Voir
            </a>

            @if(auth()->user()->isManager() || auth()->user()->isAdmin())

            <a href="{{ route('rooms.edit',$room) }}"
               class="neo-btn neo-btn--sm"
               style="flex:1; justify-content:center;">
                <i class="fas fa-edit"></i>
            </a>

            <form action="{{ route('rooms.destroy',$room) }}"
                  method="POST"
                  onsubmit="return confirm('Supprimer ?')">

                @csrf
                @method('DELETE')

                <button type="submit" class="neo-btn neo-btn--sm neo-btn--danger">
                    <i class="fas fa-trash"></i>
                </button>

            </form>

            @endif

        </div>

    </div>

    @endforeach

</div>


<!-- Pagination -->
<div style="margin-top:2rem;">
    {{ $rooms->links() }}
</div>
```

</div>

@endsection
