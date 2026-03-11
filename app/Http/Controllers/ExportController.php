<?php

namespace App\Http\Controllers;

use App\Exports\BookingsExport;
use App\Exports\ClientsExport;
use App\Exports\RevenueExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class ExportController extends Controller
{
    // ❌ PAS DE __construct() avec middleware() !
    // Les permissions sont gérées dans routes/web.php uniquement

    /**
     * Export des réservations en Excel
     */
    public function bookings(Request $request)
    {
        // Vérification manuelle du rôle (sécurité supplémentaire)
        if (!auth()->user()->hasRole(['admin', 'manager', 'accountant'])) {
            abort(403, 'Accès non autorisé');
        }

        $filters = [
            'status' => $request->get('status'),
            'date_from' => $request->get('date_from'),
            'date_to' => $request->get('date_to'),
        ];

        return Excel::download(
            new BookingsExport($filters),
            'reservations_' . Carbon::now()->format('Y-m-d_H-i') . '.xlsx'
        );
    }

    /**
     * Export des clients en Excel
     */
    public function clients(Request $request)
    {
        if (!auth()->user()->hasRole(['admin', 'manager', 'accountant'])) {
            abort(403, 'Accès non autorisé');
        }

        return Excel::download(
            new ClientsExport(),
            'clients_' . Carbon::now()->format('Y-m-d_H-i') . '.xlsx'
        );
    }

    /**
     * Export des revenus en Excel
     */
    public function revenue(Request $request)
    {
        if (!auth()->user()->hasRole(['admin', 'manager', 'accountant'])) {
            abort(403, 'Accès non autorisé');
        }

        $filters = [
            'month' => $request->get('month'),
            'year' => $request->get('year'),
        ];

        return Excel::download(
            new RevenueExport($filters),
            'revenus_' . Carbon::now()->format('Y-m-d_H-i') . '.xlsx'
        );
    }
}