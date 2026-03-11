<?php
namespace App\Http\Controllers;

use App\Models\{Room, Reservation, Payment, Client};

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $stats = [];

        if (in_array($user->role, ['admin', 'manager'])) {
            $stats = [
                'total_rooms' => Room::count(),
                'available' => Room::where('status', 'available')->count(),
                'occupied' => Room::where('status', 'occupied')->count(),
                'today_arrivals' => Reservation::whereDate('check_in', today())->whereIn('status', ['confirmed', 'checked_in'])->count(),
                'today_departures' => Reservation::whereDate('check_out', today())->where('status', 'checked_in')->count(),
                'revenue_month' => Payment::whereMonth('payment_date', now()->month)->where('status', 'completed')->sum('amount'),
                'pending_reservations' => Reservation::where('status', 'pending')->count(),
                'total_clients' => Client::count(),
            ];
        } elseif ($user->role === 'receptionist') {
            $stats = [
                'today_arrivals' => Reservation::whereDate('check_in', today())->count(),
                'today_departures' => Reservation::whereDate('check_out', today())->count(),
                'available_rooms' => Room::where('status', 'available')->count(),
                'pending_reservations' => Reservation::where('status', 'pending')->count(),
            ];
        } elseif ($user->role === 'accountant') {
            $stats = [
                'revenue_today' => Payment::whereDate('payment_date', today())->where('status', 'completed')->sum('amount'),
                'revenue_month' => Payment::whereMonth('payment_date', now()->month)->where('status', 'completed')->sum('amount'),
                'pending_payments' => Payment::where('status', 'pending')->count(),
            ];
        }

        $recentReservations = Reservation::with(['client', 'room'])->latest()->limit(8)->get();

        return view('dashboard', compact('stats', 'recentReservations'));
    }
}