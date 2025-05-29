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
        Schema::create('ledger_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->references('id')->on('transactions');
            $table->foreignId('account_id')->references('id')->on('ledger_accounts');
            $table->unsignedBigInteger('tax_id')->nullable();
            $table->unsignedBigInteger('tax_ledger_entry_id')->nullable();
            $table->enum('entry_type', ['debit', 'credit']);
            $table->string('description')->nullable();
            $table->float('amount');
            $table->timestamps();

            $table->foreign('tax_id')->references('id')->on('taxes')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('tax_ledger_entry_id')->references('id')->on('ledger_entries')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ledger_entries');
    }
};
