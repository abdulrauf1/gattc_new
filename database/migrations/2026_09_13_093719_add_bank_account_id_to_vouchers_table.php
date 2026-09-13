<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vouchers', function (Blueprint $table) {
            if (!Schema::hasColumn('vouchers', 'bank_account_id')) {
                $table->foreignId('bank_account_id')
                    ->nullable()
                    ->after('fee_configuration_id')
                    ->constrained('bank_accounts')
                    ->restrictOnDelete();
            }

            if (!Schema::hasColumn('vouchers', 'voucher_category')) {
                $table->string('voucher_category')
                    ->after('bank_account_id')
                    ->index();
            }

            if (!Schema::hasColumn('vouchers', 'fee_details')) {
                $table->json('fee_details')
                    ->nullable()
                    ->after('amount');
            }
        });
    }

    public function down(): void
    {
        Schema::table('vouchers', function (Blueprint $table) {
            if (Schema::hasColumn('vouchers', 'bank_account_id')) {
                $table->dropForeign(['bank_account_id']);
                $table->dropColumn('bank_account_id');
            }

            if (Schema::hasColumn('vouchers', 'voucher_category')) {
                $table->dropColumn('voucher_category');
            }

            if (Schema::hasColumn('vouchers', 'fee_details')) {
                $table->dropColumn('fee_details');
            }
        });
    }
};