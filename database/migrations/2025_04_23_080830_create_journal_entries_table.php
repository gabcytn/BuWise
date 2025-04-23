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
        Schema::create('journal_entries', function (Blueprint $table) {
            $table->bigInteger('id')->autoIncrement()->primary();
            $table->foreignUuid('client_id')->references('id')->on('users');
            $table->bigInteger('invoice_id');
            $table->smallInteger('transaction_type_id');
            $table->string('description');
            $table->dateTime('date');
            $table->timestamps();

            // foreign keys
            $table->foreign('invoice_id')->references('id')->on('invoices');
            $table->foreign('transaction_type_id')->references('id')->on('transaction_types');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('journal_entries');
    }
};
