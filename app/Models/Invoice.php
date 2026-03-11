<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    protected $fillable = [
        'invoice_number', 'reservation_id', 'issue_date', 'due_date',
        'subtotal', 'tax_rate', 'tax_amount', 'total_amount', 'status', 'pdf_path'
    ];

    protected $casts = [
        'issue_date' => 'date',
        'due_date' => 'date',
        'subtotal' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    protected static function boot() {
        parent::boot();
        static::creating(function($model) {
            $year = date('Y');
            $last = self::whereYear('created_at', $year)->orderBy('id', 'desc')->first();
            $next = $last ? intval(substr($last->invoice_number, -4)) + 1 : 1;
            $model->invoice_number = 'FAC-MG-' . $year . '-' . str_pad($next, 4, '0', STR_PAD_LEFT);
        });
    }

    public function reservation(): BelongsTo {
        return $this->belongsTo(Reservation::class);
    }

    public function getFormattedTotalAttribute(): string {
        return number_format($this->total_amount, 0, ',', ' ') . ' Ar';
    }
}