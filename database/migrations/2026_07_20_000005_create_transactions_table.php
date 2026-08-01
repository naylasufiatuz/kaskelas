<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // income | expense
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->unsignedBigInteger('amount'); // stored in Rupiah, > 0
            $table->date('transaction_date');
            $table->string('description');
            $table->text('notes')->nullable();
            $table->string('receipt_path')->nullable();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            // Link back to the cash payment that generated this transaction (if any),
            // used to guarantee a payment never creates a duplicate income row.
            $table->foreignId('cash_payment_id')->nullable()->constrained('cash_payments')->nullOnDelete();
            $table->timestamps();

            $table->index(['type', 'transaction_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
