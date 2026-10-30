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
                ->cascadeOnDelete();

            $table->string('deposit_slip_no')->unique();
            $table->string('bank_transaction_no')->unique()->nullable();


            $table->string('payment_slip');

            $table->decimal('amount', 10, 2);

            $table->date('payment_date')->nullable();

            $table->enum('status', [
                'pending',
                'approved',
                'rejected',
            ])->default('pending');

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