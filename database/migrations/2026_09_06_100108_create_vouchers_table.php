<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();

            $table->string('voucher_no')->unique();

            $table->foreignId('fee_configuration_id')
                ->constrained()
                ->restrictOnDelete();

            $table->foreignId('admission_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('course_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('course_batch_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->string('applicant_name');

            $table->string('father_name')->nullable();

            $table->string('cnic')->nullable();

            $table->string('phone')->nullable();

            $table->decimal('amount', 12, 2);

            $table->date('issue_date');

            $table->date('due_date');

            $table->enum('status', [
                'generated',
                'submitted',
                'paid',
                'expired',
                'cancelled'
            ])->default('generated');

            $table->text('remarks')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};