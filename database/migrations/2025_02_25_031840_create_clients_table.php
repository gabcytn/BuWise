<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->foreignUuid("bookkeeper_id")->references("id")->on("users")->cascadeOnDelete();
            $table->string("tin");
            $table->string("phone_number");
            $table->string("email")->unique();
            $table->string("client_type");
            $table->string("name");
            $table->string("password");
            $table->string("profile_img");
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
