<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->string('reservation_code')->unique();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->foreignId('room_id')->constrained()->onDelete('restrict');
            $table->date('check_in');
            $table->date('check_out');
            $table->tinyInteger('nb_guests');
            $table->decimal('total_price', 12, 2);
            $table->decimal('paid_amount', 12, 2)->default(0);
            $table->enum('status', ['pending', 'confirmed', 'checked_in', 'checked_out', 'cancelled'])->default('pending');
            $table->text('special_requests')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('reservations');
    }
};