<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Reservation extends Model
{
    protected $fillable = [
        'reservation_code', 'client_id', 'room_id', 'check_in', 'check_out',
        'nb_guests', 'total_price', 'paid_amount', 'status', 'special_requests'
    ];

    protected $casts = [
        'check_in' => 'date',
        'check_out' => 'date',
        'total_price' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'nb_guests' => 'integer'
    ];

    protected static function boot() {
        parent::boot();
        static::creating(function($model) {
            $model->reservation_code = 'RES-' . strtoupper(substr(uniqid(), -6));
        });
    }

    public function client(): BelongsTo { return $this->belongsTo(Client::class); }
    public function room(): BelongsTo { return $this->belongsTo(Room::class); }
    public function payments(): HasMany { return $this->hasMany(Payment::class); }

    public function getNightsAttribute(): int {
        return $this->check_in->diffInDays($this->check_out);
    }

    public function getRemainingBalanceAttribute(): float {
        return max(0, $this->total_price - $this->paid_amount);
    }

    public function getFormattedTotalAttribute(): string {
        return number_format($this->total_price, 0, ',', ' ') . ' Ar';
    }

    public function confirm(): bool {
        if ($this->status !== 'pending') return false;
        $this->update(['status' => 'confirmed']);
        $this->room->update(['status' => 'reserved']);
        return true;
    }

    public function checkIn(): bool {
        if ($this->status !== 'confirmed') return false;
        $this->update(['status' => 'checked_in']);
        $this->room->update(['status' => 'occupied']);
        return true;
    }

    public function checkOut(): bool {
        if ($this->status !== 'checked_in') return false;
        $this->update(['status' => 'checked_out']);
        $this->room->update(['status' => 'available']);
        return true;
    }

    public function cancel(): bool {
        $this->update(['status' => 'cancelled']);
        if ($this->room->status !== 'occupied') {
            $this->room->update(['status' => 'available']);
        }
        return true;
    }
}