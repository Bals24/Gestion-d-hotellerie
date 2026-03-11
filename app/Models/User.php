<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles; // ← ✅ AJOUTER CE TRAIT

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles; // ← ✅ AJOUTER HasRoles ICI

    protected $fillable = [
        'name',
        'email',
        'password',
        // ❌ SUPPRIMER 'role' et 'is_active' si présents
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // ✅ Méthodes helpers pour compatibilité avec ton code existant
    public function isAdmin(): bool {
        return $this->hasRole('admin');
    }

    public function isManager(): bool {
        return $this->hasRole('admin') || $this->hasRole('manager');
    }

    public function isReceptionist(): bool {
        return $this->hasRole('admin') || $this->hasRole('manager') || $this->hasRole('receptionist');
    }

    public function isAccountant(): bool {
        return $this->hasRole('admin') || $this->hasRole('manager') || $this->hasRole('accountant');
    }
}