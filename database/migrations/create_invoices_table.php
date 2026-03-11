<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->foreignId('reservation_id')->constrained()->onDelete('cascade');
            $table->date('issue_date');
            $table->date('due_date');
            $table->decimal('subtotal', 12, 2);
            $table->decimal('tax_rate', 5, 2)->default(20.00);
            $table->decimal('tax_amount', 12, 2);
            $table->decimal('total_amount', 12, 2);
            $table->enum('status', ['draft', 'sent', 'paid', 'overdue', 'cancelled'])->default('draft');
            $table->string('pdf_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('invoices');
    }
};