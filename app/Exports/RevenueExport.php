<?php

namespace App\Exports;

use App\Models\Payment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class RevenueExport implements FromCollection, WithHeadings, WithMapping
{
    protected $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Payment::with(['reservation.client', 'reservation.room'])
            ->where('status', 'completed');
        
        if (!empty($this->filters['month'])) {
            $query->whereMonth('payment_date', $this->filters['month']);
        }
        if (!empty($this->filters['year'])) {
            $query->whereYear('payment_date', $this->filters['year']);
        }
        
        return $query->orderBy('payment_date', 'desc')->get();
    }

    public function headings(): array
    {
        return ['Date', 'Réservation', 'Client', 'Chambre', 'Méthode', 'Montant (Ar)', 'Référence'];
    }

    public function map($payment): array
    {
        return [
            $payment->payment_date->format('d/m/Y H:i'),
            $payment->reservation->reservation_code,
            $payment->reservation->client->full_name,
            $payment->reservation->room->room_number,
            $payment->payment_method,
            number_format($payment->amount, 0, ',', ' '),
            $payment->transaction_ref ?? '-',
        ];
    }
}