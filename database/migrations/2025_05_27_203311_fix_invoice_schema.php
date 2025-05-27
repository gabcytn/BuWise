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
        Schema::table('invoices', function (Blueprint $table) {
            $table->unsignedBigInteger('tax_id')->nullable()->after('client_id');
            $table->smallInteger('transaction_type_id')->after('tax_id');
            $table->date('issue_date')->after('transaction_type_id');
            $table->date('due_date')->nullable()->after('issue_date');
            $table->bigInteger('invoice_number')->after('due_date');
            $table->string('supplier')->nullable()->after('invoice_number');
            $table->string('vendor')->nullable()->after('supplier');
            $table->string('payment_method')->after('vendor');
            $table->string('discount_type')->nullable()->after('payment_method');
            $table->boolean('is_paid')->after('discount_type');

            $table->foreign('transaction_type_id')->references('id')->on('transaction_types')->cascadeOnUpdate();
            $table->foreign('tax_id')->references('id')->on('taxes')->cascadeOnUpdate();
        });

        Schema::table('ledger_entries', function (Blueprint $table) {
            $table->bigInteger('invoice_id')->nullable()->after('journal_entry_id');
            $table->foreign('invoice_id')->references('id')->on('invoices')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign(['transaction_type_id']);
            $table->dropForeign(['tax_id']);

            $table->dropColumn('issue_date');
            $table->dropColumn('due_date');
            $table->dropColumn('transaction_type_id');
            $table->dropColumn('invoice_number');
            $table->dropColumn('supplier');
            $table->dropColumn('vendor');
            $table->dropColumn('payment_method');
            $table->dropColumn('tax_id');
            $table->dropColumn('discount_type');
            $table->dropColumn('is_paid');
        });

        Schema::table('ledger_entries', function (Blueprint $table) {
            $table->dropForeign(['invoice_id']);
            $table->dropColumn('invoice_id');
        });
    }
};
