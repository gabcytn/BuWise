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
        Schema::create('invoices', function (Blueprint $table) {
            $table->bigInteger('id')->autoIncrement()->primary();
            $table->foreignUuid('client_id')->references('id')->on('users');
            $table->integer('vendor_id');
            $table->integer('customer_id');
            $table->smallInteger('status');
            $table->string('invoice_number');
            $table->float('amount');
            $table->string('image');
            $table->string('description');
            $table->timestamps();

            // foreign keys
            $table->foreign('vendor_id')->references('id')->on('vendors');
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->foreign('status')->references('id')->on('status');
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
