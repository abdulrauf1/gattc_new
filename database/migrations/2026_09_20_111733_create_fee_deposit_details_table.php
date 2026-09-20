<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fee_deposit_details', function (Blueprint $table) {

            $table->id();

            /*
             * For Regular / DIT / Private:
             * this points to the selected course.
             *
             * For Hostel:
             * this can remain NULL because Hostel is not a course.
             */
            $table->foreignId('course_id')
                ->nullable()
                ->constrained('courses')
                ->nullOnDelete();

            /*
             * Supported voucher/fee categories.
             */
            $table->string('fee_category', 30)->index();

            /*
             * Internal code.
             *
             * Examples:
             * admission_fee
             * tuition_fee
             * board_registration_fee
             */
            $table->string('fee_code', 80);

            /*
             * Text displayed on the voucher.
             */
            $table->string('fee_name', 255);

            /*
             * Amount of this individual deposit item.
             */
            $table->decimal('amount', 10, 2)->default(0);

            /*
             * Whether this item is normally mandatory.
             */
            $table->boolean('mandatory')->default(false);

            /*
             * Controls display order.
             */
            $table->unsignedInteger('sort_order')->default(0);

            /*
             * Active/inactive.
             */
            $table->boolean('status')->default(true);

            $table->timestamps();

            /*
             * Faster lookup for course/category.
             */
            $table->index([
                'course_id',
                'fee_category',
                'status',
            ]);

            $table->index([
                'fee_category',
                'fee_code',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fee_deposit_details');
    }
};