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

            $table->string('admission_no')->unique();

            $table->foreignId('admission_session_id')
                ->constrained()
                ->restrictOnDelete();

            $table->foreignId('course_id')
                ->constrained()
                ->restrictOnDelete();

            $table->string('student_name');
            $table->string('father_name');

            $table->string('cnic', 30);
            $table->date('date_of_birth')->nullable();

            $table->enum('gender', [
                'Male',
                'Female',
                'Other',
            ])->nullable();

            $table->string('phone', 30);
            $table->string('email')->nullable();

            $table->text('address')->nullable();
            $table->string('student_photo')->nullable();
            
            $table->enum('status', [
                'pending',
                'approved',
                'rejected',
            ])->default('pending');

            $table->text('remarks')->nullable();

            $table->timestamps();

            $table->index('cnic');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admissions');
    }
};