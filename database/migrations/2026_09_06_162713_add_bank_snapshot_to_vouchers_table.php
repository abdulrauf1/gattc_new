<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vouchers', function (Blueprint $table) {
            $table->string('bank_name')->nullable()->after('amount');
            $table->string('account_title')->nullable()->after('bank_name');
            $table->string('account_number')->nullable()->after('account_title');
            $table->string('iban')->nullable()->after('account_number');
            $table->string('branch_name')->nullable()->after('iban');
        });
    }

    public function down(): void
    {
        Schema::table('vouchers', function (Blueprint $table) {
            $table->dropColumn([
                'bank_name',
                'account_title',
                'account_number',
                'iban',
                'branch_name',
            ]);
        });
    }
};