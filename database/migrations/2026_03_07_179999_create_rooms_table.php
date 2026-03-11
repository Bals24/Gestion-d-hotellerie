<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('room_number')->unique();
            $table->enum('type', ['single', 'double', 'suite', 'presidential', 'bungalow']);
            $table->tinyInteger('floor');
            $table->decimal('price_per_night', 10, 2);
            $table->tinyInteger('capacity')->default(2);
            $table->enum('status', ['available', 'occupied', 'maintenance', 'reserved'])->default('available');
            $table->boolean('has_sea_view')->default(false);
            $table->boolean('has_balcony')->default(false);
            $table->boolean('has_wifi')->default(true);
            $table->boolean('has_ac')->default(true);
            $table->boolean('has_breakfast')->default(true);
            $table->text('description')->nullable();
            $table->string('image_url')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('rooms');
    }
};