<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fee_configurations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('fee_type_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('bank_account_id')
                ->constrained()
                ->restrictOnDelete();

            $table->foreignId('admission_session_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('course_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->string('title');

            $table->decimal('amount', 12, 2);

            $table->date('effective_from')->nullable();
            $table->date('effective_until')->nullable();

            $table->boolean('mandatory')->default(true);
            $table->boolean('status')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fee_configurations');
    }
};