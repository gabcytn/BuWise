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
        Schema::create('ledger_accounts', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->smallInteger('account_group_id');
            $table->foreignUuid('client_id')->nullable()->references('id')->on('users')->cascadeOnDelete();
            $table->string('name');
            $table->timestamps();

            // foreign keys
            $table->foreign('account_group_id')->references('id')->on('account_groups')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ledger_accounts');
    }
};
