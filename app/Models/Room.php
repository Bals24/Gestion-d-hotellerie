<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    protected $fillable = [
        'room_number', 'type', 'floor', 'price_per_night', 'capacity',
        'status', 'has_sea_view', 'has_balcony', 'has_wifi', 'has_ac',
        'has_breakfast', 'description', 'image_url'
    ];

    protected $casts = [
        'price_per_night' => 'decimal:2',
        'has_sea_view' => 'boolean',
        'has_balcony' => 'boolean',
        'has_wifi' => 'boolean',
        'has_ac' => 'boolean',
        'has_breakfast' => 'boolean',
    ];

    public function reservations(): HasMany {
        return $this->hasMany(Reservation::class);
    }

    public function scopeAvailable($query) {
        return $query->where('status', 'available');
    }

    public function getFormattedPriceAttribute(): string {
        return number_format($this->price_per_night, 0, ',', ' ') . ' Ar';
    }

    public function calculateStayPrice(string $checkIn, string $checkOut): float {
        $nights = (strtotime($checkOut) - strtotime($checkIn)) / 86400;
        return max(0, $nights) * $this->price_per_night;
    }

    public function isAvailableFor(string $checkIn, string $checkOut, ?int $excludeId = null): bool {
        $query = $this->reservations()
            ->where('status', '!=', 'cancelled')
            ->where(function($q) use ($checkIn, $checkOut) {
                $q->whereBetween('check_in', [$checkIn, $checkOut])
                  ->orWhereBetween('check_out', [$checkIn, $checkOut]);
            });
        
        if ($excludeId) $query->where('id', '!=', $excludeId);
        
        return $query->doesntExist();
    }
}