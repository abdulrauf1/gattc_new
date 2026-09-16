<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_cards', function (Blueprint $table) {

            $table->id();

            $table->foreignId('admission_id')
                ->constrained('admissions')
                ->cascadeOnDelete();

            $table->string('card_no')
                ->unique();

            $table->string('photo')
                ->nullable();

            $table->date('issued_at');

            $table->foreignId('issued_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->boolean('status')
                ->default(true);

            $table->text('remarks')
                ->nullable();

            $table->timestamps();

            $table->index('admission_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_cards');
    }
};