<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    protected $fillable = [
        'first_name', 'last_name', 'email', 'phone', 'cin',
        'address', 'city', 'country', 'birth_date', 'loyalty_points'
    ];

    protected $casts = ['birth_date' => 'date', 'loyalty_points' => 'integer'];

    public function reservations(): HasMany {
        return $this->hasMany(Reservation::class);
    }

    public function getFullNameAttribute(): string {
        return "{$this->first_name} {$this->last_name}";
    }

    public function scopeSearch($query, $term) {
        return $query->where(function($q) use ($term) {
            $q->where('first_name', 'LIKE', "%{$term}%")
              ->orWhere('last_name', 'LIKE', "%{$term}%")
              ->orWhere('email', 'LIKE', "%{$term}%")
              ->orWhere('phone', 'LIKE', "%{$term}%");
        });
    }
}