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
        Schema::create('fee_receipts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('fee_payment_id')
                ->unique()
                ->constrained('fee_payments')
                ->cascadeOnDelete();

            $table->string('receipt_no')->unique();

            $table->dateTime('issued_at');

            $table->foreignId('issued_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->text('remarks')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fee_receipts');
    }
};