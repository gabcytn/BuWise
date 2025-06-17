<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->enum('category', ['invoice', 'journal', 'client', 'staff'])->after('description');
            $table->enum('category_description', [
                'manual_invoices',
                'digital_invoices',
                'manual_entry',
                'csv_migration',
                'create_client',
                'update_client',
                'suspend_client',
                'delete_client',
                'create_staff',
                'update_staff',
                'suspend_staff',
                'delete_staff'
            ])->after('category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn('category');
            $table->dropColumn('category_description');
        });
    }
};
