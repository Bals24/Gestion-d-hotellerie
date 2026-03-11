<?php
namespace App\Http\Controllers;

use App\Models\{Reservation, Room, Client};
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    // ❌ PAS DE __construct() avec middleware()

    public function index() {
        $reservations = Reservation::with(['client', 'room'])->latest()->paginate(10);
        return view('reservations.index', compact('reservations'));
    }

    public function create() {
        $clients = Client::orderBy('last_name')->get();
        $rooms = Room::where('status', 'available')->get();
        return view('reservations.create', compact('clients', 'rooms'));
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'room_id' => 'required|exists:rooms,id',
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'nb_guests' => 'required|integer|min:1',
        ]);
        $room = Room::find($validated['room_id']);
        if (!$room->isAvailableFor($validated['check_in'], $validated['check_out'])) {
            return back()->with('error', 'Chambre non disponible ❌')->withInput();
        }
        $validated['total_price'] = $room->calculateStayPrice($validated['check_in'], $validated['check_out']);
        $validated['paid_amount'] = 0;
        $validated['status'] = 'pending';
        $reservation = Reservation::create($validated);
        $room->update(['status' => 'reserved']);
        return redirect()->route('reservations.show', $reservation)->with('success', 'Réservation créée ✅');
    }

    public function show(Reservation $reservation) {
        $reservation->load(['client', 'room', 'payments']);
        return view('reservations.show', compact('reservation'));
    }

    public function edit(Reservation $reservation) {
        $clients = Client::all();
        $rooms = Room::all();
        return view('reservations.edit', compact('reservation', 'clients', 'rooms'));
    }

    public function update(Request $request, Reservation $reservation) {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'room_id' => 'required|exists:rooms,id',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
            'nb_guests' => 'required|integer|min:1',
        ]);
        $reservation->update($validated);
        return redirect()->route('reservations.show', $reservation)->with('success', 'Réservation mise à jour ✅');
    }

    public function destroy(Reservation $reservation) {
        $room = $reservation->room;
        if ($room->status === 'reserved') $room->update(['status' => 'available']);
        $reservation->delete();
        return redirect()->route('reservations.index')->with('success', 'Réservation supprimée ✅');
    }

    public function confirm(Reservation $reservation) {
        if ($reservation->status !== 'pending') return back()->with('error', 'Déjà traitée');
        $reservation->confirm();
        return back()->with('success', 'Réservation confirmée ✅');
    }

    public function checkIn(Reservation $reservation) {
        if ($reservation->status !== 'confirmed') return back()->with('error', 'Doit être confirmée');
        $reservation->checkIn();
        return back()->with('success', 'Check-in effectué ✅');
    }

    public function checkOut(Reservation $reservation) {
        if ($reservation->status !== 'checked_in') return back()->with('error', 'Client pas check-in');
        $reservation->checkOut();
        return back()->with('success', 'Check-out effectué ✅');
    }

    public function cancel(Reservation $reservation) {
        if (in_array($reservation->status, ['checked_out', 'cancelled'])) return back()->with('error', 'Ne peut pas annuler');
        $reservation->cancel();
        return back()->with('success', 'Réservation annulée ✅');
    }
}