<?php

namespace App\Exports;

use App\Models\Reservation;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BookingsExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Reservation::with(['client', 'room']);
        
        if (!empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }
        if (!empty($this->filters['date_from'])) {
            $query->where('check_in', '>=', $this->filters['date_from']);
        }
        if (!empty($this->filters['date_to'])) {
            $query->where('check_out', '<=', $this->filters['date_to']);
        }
        
        return $query->orderBy('check_in', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'Code', 'Client', 'Email', 'Téléphone',
            'Chambre', 'Type', 'Arrivée', 'Départ', 'Nuits',
            'Prix Total (Ar)', 'Payé (Ar)', 'Reste (Ar)', 'Statut'
        ];
    }

    public function map($reservation): array
    {
        return [
            $reservation->reservation_code,
            $reservation->client->full_name,
            $reservation->client->email,
            $reservation->client->phone,
            $reservation->room->room_number,
            $reservation->room->type,
            $reservation->check_in->format('d/m/Y'),
            $reservation->check_out->format('d/m/Y'),
            $reservation->nights,
            number_format($reservation->total_price, 0, ',', ' '),
            number_format($reservation->paid_amount, 0, ',', ' '),
            number_format($reservation->remaining_balance, 0, ',', ' '),
            $reservation->status,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
            'A:M' => ['alignment' => ['horizontal' => 'center']],
        ];
    }
}