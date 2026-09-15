<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();

            $table->foreignId('course_category_id')
                ->nullable()
                ->constrained('course_categories')
                ->nullOnDelete();

            $table->foreignId('bank_account_id')
                ->constrained('bank_accounts')
                ->restrictOnDelete();

            $table->string('title');
            $table->string('slug')->unique();

            $table->enum('course_type', [
                'regular',
                'dit',
                'private',
            ])->default('regular');

            $table->text('description')->nullable();
            $table->string('duration')->nullable();
            $table->text('eligibility')->nullable();

            $table->decimal('fee_amount', 10, 2);

            $table->string('image')->nullable();
            $table->integer('sort_order')->default(0);

            $table->boolean('status')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};