<?php
namespace App\Http\Controllers;

use App\Models\{Payment, Reservation};
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    // ❌ PAS DE __construct() avec middleware()

    public function index() {
        $payments = Payment::with(['reservation.client'])->latest()->paginate(10);
        return view('payments.index', compact('payments'));
    }

    public function show(Payment $payment) {
        return view('payments.show', compact('payment'));
    }
public function create(Reservation $reservation)
{
    // Vérifier que la réservation a un reste à payer
    if ($reservation->remaining_balance <= 0) {
        return back()->with('error', 'Cette réservation est déjà entièrement payée ');
    }

    // Charger les relations nécessaires
    $reservation->load(['client', 'room']);
    
    // Afficher la vue du formulaire de paiement
    return view('payments.create', compact('reservation'));
}
    public function store(Request $request, Reservation $reservation) {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1|max:'.$reservation->remaining_balance,
            'payment_method' => 'required|in:cash,card,mvola,orange_money,bank_transfer',
            'transaction_ref' => 'nullable|string',
        ]);
        Payment::create([
            'reservation_id' => $reservation->id,
            'amount' => $validated['amount'],
            'payment_method' => $validated['payment_method'],
            'transaction_ref' => $validated['transaction_ref'] ?? null,
            'status' => 'completed',
            'payment_date' => now(),
        ]);
        $reservation->increment('paid_amount', $validated['amount']);
        return redirect()->route('reservations.show', $reservation)->with('success', 'Paiement enregistré ✅');
    }
}