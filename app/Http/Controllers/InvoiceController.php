<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:admin,manager,accountant']);
    }

    /**
     * Liste des factures
     */
    public function index(Request $request)
    {
        $query = Invoice::with(['reservation.client', 'reservation.room']);
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('date_from')) {
            $query->where('issue_date', '>=', $request->date_from);
        }
        
        $invoices = $query->latest()->paginate(15);
        
        return view('invoices.index', compact('invoices'));
    }

    /**
     * Afficher une facture
     */
    public function show(Invoice $invoice)
    {
        $invoice->load(['reservation.client', 'reservation.room', 'reservation.payments']);
        
        return view('invoices.show', compact('invoice'));
    }

    /**
     * Générer PDF d'une facture
     */
    public function pdf(Invoice $invoice)
    {
        $invoice->load(['reservation.client', 'reservation.room', 'reservation.payments']);
        
        $pdf = Pdf::loadView('invoices.pdf', compact('invoice'));
        
        return $pdf->download('facture_' . $invoice->invoice_number . '.pdf');
    }

    /**
     * Créer une facture pour une réservation
     */
    public function create(Reservation $reservation)
    {
        if ($reservation->invoice) {
            return redirect()->route('invoices.show', $reservation->invoice)
                ->with('error', 'Une facture existe déjà pour cette réservation');
        }

        $subtotal = $reservation->total_price;
        $taxRate = 20.00; // TVA 20%
        $taxAmount = $subtotal * ($taxRate / 100);
        $total = $subtotal + $taxAmount;

        $invoice = Invoice::create([
            'reservation_id' => $reservation->id,
            'issue_date' => today(),
            'due_date' => today()->addDays(30),
            'subtotal' => $subtotal,
            'tax_rate' => $taxRate,
            'tax_amount' => $taxAmount,
            'total_amount' => $total,
            'status' => 'sent',
        ]);

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Facture ' . $invoice->invoice_number . ' générée ✅');
    }
}