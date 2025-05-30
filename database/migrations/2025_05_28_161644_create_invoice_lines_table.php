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
        Schema::create('invoice_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->references('id')->on('transactions')->cascadeOnDelete()->cascadeOnUpdate();
            $table->unsignedBigInteger('tax_id')->nullable();
            $table->string('item_name');
            $table->integer('quantity');
            $table->float('unit_price');
            $table->string('discount')->nullable();
            $table->timestamps();

            $table->foreign('tax_id')->references('id')->on('taxes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_items');
    }
};
