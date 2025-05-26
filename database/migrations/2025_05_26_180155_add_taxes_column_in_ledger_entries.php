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
        Schema::table('ledger_entries', function (Blueprint $table) {
            $table->unsignedBigInteger('tax_id')->nullable()->after('entry_type_id');
            $table->unsignedBigInteger('tax_ledger_entry_id')->nullable()->after('tax_id');

            $table->foreign('tax_id')->references('id')->on('taxes')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('tax_ledger_entry_id')->references('id')->on('ledger_entries')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ledger_entries', function (Blueprint $table) {
            $table->dropForeign(['tax_id']);
            $table->dropColumn('tax_id');

            $table->dropForeign(['tax_ledger_entry_id']);
            $table->dropColumn('tax_ledger_entry_id');
        });
    }
};
