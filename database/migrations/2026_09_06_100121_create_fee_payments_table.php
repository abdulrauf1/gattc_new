<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fee_payments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('voucher_id')
                ->constrained()
                ->restrictOnDelete();

            $table->string('bank_transaction_no')->nullable();

            $table->string('deposit_slip_no')->nullable();

            $table->date('payment_date')->nullable();

            $table->decimal('amount', 12, 2);

            $table->enum('payment_method', [
                'bank',
                'online',
                'cash',
                'other'
            ])->default('bank');

            $table->enum('status', [
                'pending',
                'verified',
                'rejected'
            ])->default('pending');

            $table->string('proof_document')->nullable();

            $table->foreignId('verified_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('verified_at')->nullable();

            $table->text('remarks')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fee_payments');
    }
};