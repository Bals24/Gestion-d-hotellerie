<?php
// database/migrations/xxxx_remove_old_role_column_from_users.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'role')) {
                $table->dropColumn('role');
            }
            if (Schema::hasColumn('users', 'is_active')) {
                $table->dropColumn('is_active');
            }
        });
    }

    public function down(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'manager', 'receptionist', 'accountant'])->default('receptionist')->after('email');
            $table->boolean('is_active')->default(true)->after('role');
        });
    }
};