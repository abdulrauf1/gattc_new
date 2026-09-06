<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('admissions', function (Blueprint $table) {

            if (Schema::hasColumn('admissions', 'voucher_id')) {
                $table->dropForeign(['voucher_id']);
                $table->dropColumn('voucher_id');
            }

            if (Schema::hasColumn('admissions', 'fee_payment_id')) {
                $table->dropForeign(['fee_payment_id']);
                $table->dropColumn('fee_payment_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('admissions', function (Blueprint $table) {

            $table->foreignId('voucher_id')
                ->nullable()
                ->constrained('vouchers')
                ->nullOnDelete();

            $table->foreignId('fee_payment_id')
                ->nullable()
                ->constrained('fee_payments')
                ->nullOnDelete();
        });
    }
};