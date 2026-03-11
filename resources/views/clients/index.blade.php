@extends('layouts.app')
@section('title', '👥 Gestion des Clients')

@section('content')
<div class="py-4">
    
    <!-- En-tête -->
    <div class="page-header animate-neo">
        <h1 class="page-header__title">
            <i class="fas fa-users"></i> Gestion des Clients
        </h1>
        <p style="color: var(--text-secondary);">
            {{ $clients->total() }} clients enregistrés
        </p>
    </div>

    <!-- Actions avec bouton AJOUTER -->
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem; flex-wrap:wrap; gap:1rem;">
        
        <!-- Boutons à gauche -->
        <div style="display:flex; gap:0.5rem;">
            
            {{-- ✅ BOUTON VERT "AJOUTER" --}}
            <a href="{{ route('clients.create') }}" class="neo-btn neo-btn--success">
                <i class="fas fa-user-plus"></i> Ajouter
            </a>
            
            {{-- Bouton Retour --}}
            <a href="{{ route('dashboard') }}" class="neo-btn">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </div>
        
        <!-- Formulaire de recherche à droite -->
        <form method="GET" style="display:flex; gap:0.5rem;">
            <input type="text" name="search" placeholder="Nom, email, téléphone..." value="{{ request('search') }}" class="neo-form" style="width:300px;">
            <button type="submit" class="neo-btn neo-btn--primary">
                <i class="fas fa-search"></i>
            </button>
        </form>
    </div>

    <!-- Tableau des clients -->
    <div class="neo-card animate-neo">
        <table class="neo-table">
            <thead>
                <tr>
                    <th>Nom complet</th>
                    <th>Email</th>
                    <th>Téléphone</th>
                    <th>Ville</th>
                    <th>Réservations</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($clients as $client)
                <tr>
                    <td><strong>{{ $client->first_name }} {{ $client->last_name }}</strong></td>
                    <td>{{ $client->email }}</td>
                    <td>{{ $client->phone }}</td>
                    <td>{{ $client->city ?? '-' }}</td>
                    <td>{{ $client->reservations->count() }}</td>
                    <td>
                        <a href="{{ route('clients.show', $client) }}" class="neo-btn neo-btn--sm neo-btn--primary">
                            <i class="fas fa-eye"></i> Voir
                        </a>
                        <a href="{{ route('clients.edit', $client) }}" class="neo-btn neo-btn--sm">
                            <i class="fas fa-edit"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center; padding:2rem; color:var(--text-secondary);">
                        Aucun client trouvé. <a href="{{ route('clients.create') }}" style="color:var(--accent-green);">Cliquez ici pour en ajouter un</a>.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div style="margin-top:2rem;">
        {{ $clients->links() }}
    </div>
</div>
@endsection