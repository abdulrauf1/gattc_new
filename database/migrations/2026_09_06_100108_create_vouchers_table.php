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

            /*
            |--------------------------------------------------------------------------
            | Admission Session
            |--------------------------------------------------------------------------
            */
            $table->foreignId('admission_session_id')
                ->constrained('admission_sessions')
                ->restrictOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Fee Configuration
            |--------------------------------------------------------------------------
            */
            $table->foreignId('fee_configuration_id')
                ->constrained('fee_configurations')
                ->restrictOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Admission
            |--------------------------------------------------------------------------
            |
            | This remains null until the voucher payment is verified.
            |
            */
            $table->foreignId('admission_id')
                ->nullable()
                ->constrained('admissions')
                ->nullOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Course Information
            |--------------------------------------------------------------------------
            */
            $table->foreignId('course_id')
                ->nullable()
                ->constrained('courses')
                ->nullOnDelete();

            $table->foreignId('course_batch_id')
                ->nullable()
                ->constrained('course_batches')
                ->nullOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Applicant Information
            |--------------------------------------------------------------------------
            */
            $table->string('applicant_name');

            $table->string('father_name')->nullable();

            $table->string('cnic')->nullable();

            $table->date('date_of_birth')->nullable();

            $table->enum('gender', [
                'Male',
                'Female',
                'Other',
            ])->nullable();

            $table->string('phone')->nullable();

            $table->string('email')->nullable();

            $table->text('address')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Voucher Information
            |--------------------------------------------------------------------------
            */
            $table->decimal('amount', 12, 2);

            $table->date('issue_date');

            $table->date('due_date');

            $table->enum('status', [
                'generated',
                'submitted',
                'paid',
                'expired',
                'cancelled',
            ])->default('generated');

            $table->text('remarks')->nullable();

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Useful Indexes
            |--------------------------------------------------------------------------
            */
            $table->index([
                'admission_session_id',
                'status',
            ]);

            $table->index('cnic');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};