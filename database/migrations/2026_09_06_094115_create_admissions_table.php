<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admissions', function (Blueprint $table) {
            $table->id();

            $table->string('application_no')->unique();

            $table->foreignId('course_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('course_batch_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            // Applicant information
            $table->string('full_name');
            $table->string('father_name')->nullable();

            $table->date('date_of_birth')->nullable();

            $table->enum('gender', [
                'male',
                'female',
                'other'
            ])->nullable();

            $table->string('cnic', 20)->nullable();
            $table->string('phone', 30);
            $table->string('email')->nullable();

            // Address
            $table->text('address')->nullable();
            $table->string('district')->nullable();
            $table->string('tehsil')->nullable();

            // Education
            $table->string('qualification')->nullable();
            $table->string('institute')->nullable();
            $table->string('passing_year')->nullable();

            // Documents
            $table->string('photo')->nullable();
            $table->string('cnic_front')->nullable();
            $table->string('cnic_back')->nullable();
            $table->string('qualification_document')->nullable();

            $table->enum('status', [
                'pending',
                'under_review',
                'approved',
                'rejected',
                'enrolled'
            ])->default('pending');

            $table->text('remarks')->nullable();

            $table->timestamp('submitted_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admissions');
    }
};