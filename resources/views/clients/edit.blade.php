<x-app-layout>
<div class="py-4">
    
    <div class="page-header animate-neo">
        <h1 class="page-header__title"><i class="fas fa-user-edit"></i> Modifier {{ $client->full_name }}</h1>
    </div>

    <div class="neo-card animate-neo" style="max-width: 800px; margin: 0 auto;">
        <form action="{{ route('clients.update', $client) }}" method="POST" class="neo-form">
            @csrf
            @method('PUT')
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                
                <div class="form-group">
                    <label><i class="fas fa-user"></i> Prénom *</label>
                    <input type="text" name="first_name" value="{{ old('first_name', $client->first_name) }}" required>
                </div>

                <div class="form-group">
                    <label><i class="fas fa-user"></i> Nom *</label>
                    <input type="text" name="last_name" value="{{ old('last_name', $client->last_name) }}" required>
                </div>

                <div class="form-group">
                    <label><i class="fas fa-envelope"></i> Email *</label>
                    <input type="email" name="email" value="{{ old('email', $client->email) }}" required>
                </div>

                <div class="form-group">
                    <label><i class="fas fa-phone"></i> Téléphone *</label>
                    <input type="text" name="phone" value="{{ old('phone', $client->phone) }}" required>
                </div>

                <div class="form-group">
                    <label><i class="fas fa-id-card"></i> CIN</label>
                    <input type="text" name="cin" value="{{ old('cin', $client->cin) }}">
                </div>

                <div class="form-group">
                    <label><i class="fas fa-calendar"></i> Date de naissance</label>
                    <input type="date" name="birth_date" value="{{ old('birth_date', $client->birth_date?->format('Y-m-d')) }}">
                </div>

                <div class="form-group">
                    <label><i class="fas fa-map-marker-alt"></i> Ville</label>
                    <input type="text" name="city" value="{{ old('city', $client->city) }}">
                </div>

                <div class="form-group">
                    <label><i class="fas fa-flag"></i> Pays</label>
                    <input type="text" name="country" value="{{ old('country', $client->country) }}">
                </div>
            </div>

            <div class="form-group">
                <label><i class="fas fa-home"></i> Adresse</label>
                <textarea name="address" rows="2">{{ old('address', $client->address) }}</textarea>
            </div>

            <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                <button type="submit" class="neo-btn neo-btn--success" style="flex: 1; justify-content: center;">
                    <i class="fas fa-save"></i> Enregistrer
                </button>
                <a href="{{ route('clients.index') }}" class="neo-btn neo-btn--danger" style="flex: 1; justify-content: center;">
                    <i class="fas fa-times"></i> Annuler
                </a>
            </div>
        </form>
    </div>
</div>
</x-app-layout>