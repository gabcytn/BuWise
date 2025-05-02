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
        Schema::create('accounts_opening_balance', function (Blueprint $table) {
            $table->integer('ledger_account_id');
            $table->foreignUuid('client_id')->references('id')->on('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->float('initial_balance')->default(0.0);
            $table->timestamps();

            $table->foreign('ledger_account_id')->references('id')->on('ledger_accounts')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts_opening_balance');
    }
};
