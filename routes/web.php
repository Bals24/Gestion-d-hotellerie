<?php

use App\Http\Controllers\{
    DashboardController,
    RoomController,
    ClientController,
    ReservationController,
    PaymentController,
    ExportController,
    InvoiceController
};
use Illuminate\Support\Facades\Route;

// Redirection page d'accueil
Route::get('/', fn() => redirect()->route('login'));

// Routes protégées par authentification
Route::middleware(['auth'])->group(function() {
    
    // 🎛️ Dashboard - TOUS LES RÔLES
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // 🏢 CHAMBRES - Admin OU Manager
    Route::middleware(['role:admin|manager'])->prefix('rooms')->name('rooms.')->group(function() {
        Route::get('/', [RoomController::class, 'index'])->name('index');
        Route::get('/create', [RoomController::class, 'create'])->name('create');
        Route::post('/', [RoomController::class, 'store'])->name('store');
        Route::get('/{room}', [RoomController::class, 'show'])->name('show');
        Route::get('/{room}/edit', [RoomController::class, 'edit'])->name('edit');
        Route::put('/{room}', [RoomController::class, 'update'])->name('update');
        Route::delete('/{room}', [RoomController::class, 'destroy'])->name('destroy');
    });

    // 👥 CLIENTS - Admin OU Manager OU Réception
    Route::middleware(['role:admin|manager|receptionist'])->prefix('clients')->name('clients.')->group(function() {
        Route::get('/', [ClientController::class, 'index'])->name('index');
        Route::get('/create', [ClientController::class, 'create'])->name('create');
        Route::post('/', [ClientController::class, 'store'])->name('store');
        Route::get('/{client}', [ClientController::class, 'show'])->name('show');
        Route::get('/{client}/edit', [ClientController::class, 'edit'])->name('edit');
        Route::put('/{client}', [ClientController::class, 'update'])->name('update');
        Route::delete('/{client}', [ClientController::class, 'destroy'])->name('destroy');
    });

    // 📅 RÉSERVATIONS - Admin OU Manager OU Réception
    Route::middleware(['role:admin|manager|receptionist'])->prefix('reservations')->name('reservations.')->group(function() {
        Route::get('/', [ReservationController::class, 'index'])->name('index');
        Route::get('/create', [ReservationController::class, 'create'])->name('create');
        Route::post('/', [ReservationController::class, 'store'])->name('store');
        Route::get('/{reservation}', [ReservationController::class, 'show'])->name('show');
        Route::get('/{reservation}/edit', [ReservationController::class, 'edit'])->name('edit');
        Route::put('/{reservation}', [ReservationController::class, 'update'])->name('update');
        Route::delete('/{reservation}', [ReservationController::class, 'destroy'])->name('destroy');
        
        // Actions spécifiques
        Route::post('/{reservation}/confirm', [ReservationController::class, 'confirm'])->name('confirm');
        Route::post('/{reservation}/checkin', [ReservationController::class, 'checkIn'])->name('checkin');
        Route::post('/{reservation}/checkout', [ReservationController::class, 'checkOut'])->name('checkout');
        Route::post('/{reservation}/cancel', [ReservationController::class, 'cancel'])->name('cancel');
        
        // Paiement : GET pour afficher le formulaire
        Route::get('/{reservation}/payment', [PaymentController::class, 'create'])->name('payment.create');
        // Paiement : POST pour traiter
        Route::post('/{reservation}/pay', [PaymentController::class, 'store'])->name('payment.store');
    });

    // 💰 PAIEMENTS - Admin OU Manager OU Compta OU Réception
    Route::middleware(['role:admin|manager|accountant|receptionist'])->prefix('payments')->name('payments.')->group(function() {
        Route::get('/', [PaymentController::class, 'index'])->name('index');
        Route::get('/{payment}', [PaymentController::class, 'show'])->name('show');
    });

   // 📊 EXPORTS - Admin OU Manager OU Compta (UN SEUL middleware)
Route::middleware(['role:admin|manager|accountant'])->prefix('export')->name('exports.')->group(function() {
    Route::get('/bookings', [ExportController::class, 'bookings'])->name('bookings');
    Route::get('/clients', [ExportController::class, 'clients'])->name('clients');
    Route::get('/revenue', [ExportController::class, 'revenue'])->name('revenue');
});

    // 📄 FACTURES - Admin OU Manager OU Compta
    Route::middleware(['role:admin|manager|accountant'])->prefix('invoices')->name('invoices.')->group(function() {
        Route::get('/', [InvoiceController::class, 'index'])->name('index');
        Route::get('/{invoice}/pdf', [InvoiceController::class, 'pdf'])->name('pdf');
    });
});

// Routes d'authentification Breeze (DOIT ÊTRE À LA FIN)
require __DIR__.'/auth.php';