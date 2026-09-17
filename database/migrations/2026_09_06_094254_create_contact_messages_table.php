<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Create table if it does not exist
        |--------------------------------------------------------------------------
        */
        if (!Schema::hasTable('contact_messages')) {

            Schema::create('contact_messages', function (Blueprint $table) {
                $table->id();

                $table->string('name');
                $table->string('email');
                $table->string('phone')->nullable();
                $table->string('subject')->nullable();
                $table->text('message');

                $table->timestamp('read_at')->nullable();
                $table->text('admin_notes')->nullable();

                $table->timestamps();

                $table->index('email');
                $table->index('read_at');
            });

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Existing table
        |--------------------------------------------------------------------------
        | Add only missing columns.
        |--------------------------------------------------------------------------
        */
        Schema::table('contact_messages', function (Blueprint $table) {

            if (!Schema::hasColumn('contact_messages', 'name')) {
                $table->string('name')->nullable();
            }

            if (!Schema::hasColumn('contact_messages', 'email')) {
                $table->string('email')->nullable();
            }

            if (!Schema::hasColumn('contact_messages', 'phone')) {
                $table->string('phone')->nullable();
            }

            if (!Schema::hasColumn('contact_messages', 'subject')) {
                $table->string('subject')->nullable();
            }

            if (!Schema::hasColumn('contact_messages', 'message')) {
                $table->text('message')->nullable();
            }

            if (!Schema::hasColumn('contact_messages', 'read_at')) {
                $table->timestamp('read_at')->nullable();
            }

            if (!Schema::hasColumn('contact_messages', 'admin_notes')) {
                $table->text('admin_notes')->nullable();
            }
        });
    }

    public function down(): void
    {
        /*
        | Do not delete an existing contact_messages table automatically.
        | This table may contain real visitor messages.
        */
    }
};