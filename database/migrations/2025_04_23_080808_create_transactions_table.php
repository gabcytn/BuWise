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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('client_id')->references('id')->on('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignUuid('created_by')->references('id')->on('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->enum('status', ['approved', 'pending', 'rejected']);
            $table->enum('type', ['invoice', 'journal']);
            $table->enum('kind', ['sales', 'purchases']);
            $table->float('amount');
            $table->date('date');
            $table->string('payment_method');
            $table->string('description')->nullable();
            $table->string('reference_no')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
