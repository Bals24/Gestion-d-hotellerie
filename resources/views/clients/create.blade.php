@extends('layouts.app')
@section('title', '➕ Nouveau Client')

@section('content')
<div class="py-4">
    
    <!-- En-tête -->
    <div class="page-header animate-neo">
        <h1 class="page-header__title">
            <i class="fas fa-user-plus"></i> Nouveau Client
        </h1>
        <p style="color: var(--text-secondary);">
            Enregistrer un nouveau client dans le système
        </p>
    </div>

    <!-- Formulaire -->
    <div class="neo-card animate-neo" style="max-width: 900px; margin: 0 auto;">
        <form action="{{ route('clients.store') }}" method="POST" class="neo-form">
            @csrf
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                
                <!-- Prénom -->
                <div class="form-group">
                    <label for="first_name"><i class="fas fa-user"></i> Prénom *</label>
                    <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}" required placeholder="Ex: Jean">
                    @error('first_name') 
                        <span style="color: var(--accent-red); font-size: 0.85rem;">{{ $message }}</span> 
                    @enderror
                </div>

                <!-- Nom -->
                <div class="form-group">
                    <label for="last_name"><i class="fas fa-user"></i> Nom *</label>
                    <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}" required placeholder="Ex: Rakoto">
                    @error('last_name') 
                        <span style="color: var(--accent-red); font-size: 0.85rem;">{{ $message }}</span> 
                    @enderror
                </div>

                <!-- Email -->
                <div class="form-group">
                    <label for="email"><i class="fas fa-envelope"></i> Email *</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="client@email.com">
                    @error('email') 
                        <span style="color: var(--accent-red); font-size: 0.85rem;">{{ $message }}</span> 
                    @enderror
                </div>

                <!-- Téléphone -->
                <div class="form-group">
                    <label for="phone"><i class="fas fa-phone"></i> Téléphone *</label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone') }}" required placeholder="+261 34 00 000 00">
                    @error('phone') 
                        <span style="color: var(--accent-red); font-size: 0.85rem;">{{ $message }}</span> 
                    @enderror
                </div>

                <!-- CIN -->
                <div class="form-group">
                    <label for="cin"><i class="fas fa-id-card"></i> CIN (Carte d'identité)</label>
                    <input type="text" id="cin" name="cin" value="{{ old('cin') }}" placeholder="Numéro CIN">
                    @error('cin') 
                        <span style="color: var(--accent-red); font-size: 0.85rem;">{{ $message }}</span> 
                    @enderror
                </div>

                <!-- Date de naissance -->
                <div class="form-group">
                    <label for="birth_date"><i class="fas fa-calendar"></i> Date de naissance</label>
                    <input type="date" id="birth_date" name="birth_date" value="{{ old('birth_date') }}">
                    @error('birth_date') 
                        <span style="color: var(--accent-red); font-size: 0.85rem;">{{ $message }}</span> 
                    @enderror
                </div>

                <!-- Ville -->
                <div class="form-group">
                    <label for="city"><i class="fas fa-map-marker-alt"></i> Ville</label>
                    <input type="text" id="city" name="city" value="{{ old('city') }}" placeholder="Ex: Antananarivo">
                </div>

                <!-- Pays -->
                <div class="form-group">
                    <label for="country"><i class="fas fa-flag"></i> Pays</label>
                    <input type="text" id="country" name="country" value="{{ old('country', 'Madagascar') }}" placeholder="Ex: Madagascar">
                </div>
            </div>

            <!-- Adresse -->
            <div class="form-group">
                <label for="address"><i class="fas fa-home"></i> Adresse</label>
                <textarea id="address" name="address" rows="2" placeholder="Adresse complète...">{{ old('address') }}</textarea>
            </div>

            <!-- Boutons d'action -->
            <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                <button type="submit" class="neo-btn neo-btn--success" style="flex: 1; justify-content: center;">
                    <i class="fas fa-check"></i> Enregistrer le client
                </button>
                <a href="{{ route('clients.index') }}" class="neo-btn" style="flex: 1; justify-content: center;">
                    <i class="fas fa-times"></i> Annuler
                </a>
            </div>
        </form>
    </div>
</div>
@endsection