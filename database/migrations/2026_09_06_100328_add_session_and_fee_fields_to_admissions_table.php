<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('admissions', function (Blueprint $table) {

            $table->foreignId('admission_session_id')
                ->nullable()
                ->after('id')
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('voucher_id')
                ->nullable()
                ->after('course_batch_id')
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('fee_payment_id')
                ->nullable()
                ->after('voucher_id')
                ->constrained()
                ->nullOnDelete();

            $table->timestamp('fee_verified_at')
                ->nullable();

            $table->foreignId('fee_verified_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('admitted_at')
                ->nullable();

            $table->foreignId('admitted_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->enum('admission_status', [
                'application',
                'fee_pending',
                'fee_verified',
                'admitted',
                'rejected',
                'cancelled'
            ])->default('application');

        });
    }

    public function down(): void
    {
        Schema::table('admissions', function (Blueprint $table) {

            $table->dropForeign([
                'admission_session_id',
                'voucher_id',
                'fee_payment_id',
                'fee_verified_by',
                'admitted_by'
            ]);

            $table->dropColumn([
                'admission_session_id',
                'voucher_id',
                'fee_payment_id',
                'fee_verified_at',
                'fee_verified_by',
                'admitted_at',
                'admitted_by',
                'admission_status'
            ]);
        });
    }
};