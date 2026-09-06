<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fee_payment_histories', function (Blueprint $table) {
            $table->id();

            $table->foreignId('voucher_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('fee_payment_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->string('action');

            $table->string('old_status')->nullable();
            $table->string('new_status')->nullable();

            $table->decimal('amount', 12, 2)->nullable();

            $table->foreignId('performed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->text('remarks')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fee_payment_histories');
    }
};