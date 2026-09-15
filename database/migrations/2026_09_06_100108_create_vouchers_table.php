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

            $table->foreignId('admission_session_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('course_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('admission_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('bank_account_id')
                ->constrained()
                ->restrictOnDelete();

            $table->enum('voucher_type', [
                'admission',
                'hostel',
                'readmission',
            ]);

            $table->string('applicant_name');
            $table->string('father_name')->nullable();

            $table->string('cnic', 30)->nullable();
            $table->date('date_of_birth')->nullable();

            $table->enum('gender', [
                'Male',
                'Female',
                'Other',
            ])->nullable();

            $table->string('phone', 30)->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();

            $table->decimal('amount', 10, 2);

            $table->date('issue_date');
            $table->date('due_date');

            $table->enum('status', [
                'generated',
                'paid',
                'cancelled',
            ])->default('generated');

            $table->text('remarks')->nullable();

            /*
             * Bank snapshot
             * Keeps historical vouchers correct even if
             * bank account details change later.
             */
            $table->string('bank_name')->nullable();
            $table->string('account_title')->nullable();
            $table->string('account_number')->nullable();
            $table->string('iban')->nullable();
            $table->string('branch_name')->nullable();
            $table->string('branch_code')->nullable();

            $table->timestamps();

            $table->index('voucher_type');
            $table->index('cnic');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};