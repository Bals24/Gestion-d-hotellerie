<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'reservation_id', 'payment_method', 'amount', 'payment_date',
        'transaction_ref', 'status', 'notes'
    ];

    protected $casts = ['amount' => 'decimal:2', 'payment_date' => 'datetime'];

    public function reservation(): BelongsTo {
        return $this->belongsTo(Reservation::class);
    }

    public function getFormattedAmountAttribute(): string {
        return number_format($this->amount, 0, ',', ' ') . ' Ar';
    }
}