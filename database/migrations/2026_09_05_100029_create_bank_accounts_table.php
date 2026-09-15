<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bank_accounts', function (Blueprint $table) {
            $table->id();

            $table->string('account_title');
            $table->string('account_number')->nullable();
            $table->string('bank_name')->default('Bank of Khyber');
            $table->string('branch_name')->nullable();
            $table->string('branch_code')->nullable();
            $table->string('iban')->nullable();

            $table->string('purpose');
            $table->boolean('status')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bank_accounts');
    }
};