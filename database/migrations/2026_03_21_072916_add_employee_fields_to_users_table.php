<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('employee_id')->unique()->after('id');
            $table->string('department')->nullable();
            $table->date('join_date')->nullable()->after('department');
            $table->string('phone')->nullable()->after('join_date');
            $table->enum('role', ['admin', 'hr', 'employee'])->default('employee')->after('join_date');
            $table->foreignId('manager_Id')->nullable()->constrained('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['employee_id', 'department', 'join_date', 'phone', 'role', 'manager_Id']);
        });
    }
};
