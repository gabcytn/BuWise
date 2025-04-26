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
            $table->bigInteger('journal_entry_id');
            $table->integer('account_id');
            $table->smallInteger('entry_type_id');
            $table->smallInteger('transaction_type_id');
            $table->float('amount');
            $table->timestamps();

            // foreign keys
            $table->foreign('journal_entry_id')->references('id')->on('journal_entries')->cascadeOnDelete();
            $table->foreign('account_id')->references('id')->on('ledger_accounts')->cascadeOnDelete();
            $table->foreign('entry_type_id')->references('id')->on('entry_types')->cascadeOnDelete();
            $table->foreign('transaction_type_id')->references('id')->on('transaction_types')->cascadeOnDelete();
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
