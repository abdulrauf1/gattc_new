<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admission_session_course', function (Blueprint $table) {
            $table->id();

            $table->foreignId('admission_session_id')
                ->constrained('admission_sessions')
                ->cascadeOnDelete();

            $table->foreignId('course_id')
                ->constrained('courses')
                ->cascadeOnDelete();

            $table->timestamps();

            $table->unique([
                'admission_session_id',
                'course_id',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admission_session_course');
    }
};