<?php

namespace App\Exports;

use App\Models\Client;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ClientsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Client::withCount('reservations')->orderBy('last_name')->get();
    }

    public function headings(): array
    {
        return ['Nom', 'Prénom', 'Email', 'Téléphone', 'CIN', 'Ville', 'Pays', 'Réservations'];
    }

    public function map($client): array
    {
        return [
            $client->last_name,
            $client->first_name,
            $client->email,
            $client->phone,
            $client->cin ?? '-',
            $client->city ?? '-',
            $client->country,
            $client->reservations_count,
        ];
    }
}