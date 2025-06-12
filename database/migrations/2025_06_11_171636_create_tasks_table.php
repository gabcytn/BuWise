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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('assigned_to')->references('id')->on('users')->cascadeOnDelete();
            $table->foreignUuid('client')->nullable()->references('id')->on('users')->cascadeOnDelete();
            $table->string('name');
            $table->string('description');
            $table->enum('status', ['not_started', 'in_progress', 'completed']);
            $table->enum('frequency', ['once', 'daily', 'weekly', 'monthly', 'quarterly', 'annually']);
            $table->date('start_date');
            $table->date('end_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
