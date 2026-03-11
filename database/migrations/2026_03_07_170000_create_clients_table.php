<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('cin')->unique()->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->default('Madagascar');
            $table->date('birth_date')->nullable();
            $table->integer('loyalty_points')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('clients');
    }
};