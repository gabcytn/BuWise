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
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign(['client_id']);
            $table->dropForeign(['vendor_id']);
            $table->dropForeign(['customer_id']);
            $table->dropForeign(['status']);

            $table->dropColumn([
                'client_id',
                'vendor_id',
                'customer_id',
                'status',
                'invoice_number',
                'amount',
                'description',
                'created_at',
                'updated_at',
            ]);
        });

        Schema::table('journal_entries', function (Blueprint $table) {
            $table->smallInteger('status_id')->after('invoice_id');
            $table->foreign('status_id')->references('id')->on('status')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
