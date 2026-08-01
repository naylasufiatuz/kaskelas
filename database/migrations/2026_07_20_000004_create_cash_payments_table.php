<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cash_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->date('week_start'); // always a Monday
            $table->unsignedInteger('amount');
            $table->timestamp('paid_at')->nullable();
            $table->string('status')->default('unpaid'); // paid | unpaid | partial
            $table->text('notes')->nullable();
            $table->timestamps();

            // One payment record per student per week - prevents duplicate income transactions
            $table->unique(['student_id', 'week_start']);
            $table->index('week_start');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cash_payments');
    }
};
