<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jail_call_logs', function (Blueprint $table) {

            // Identifiers
            $table->id();

            $table->string('site')->nullable();

            $table->string('term_group_name')->nullable();
            $table->string('term_name')->nullable();

            $table->text('raw_start_time')->nullable();
            $table->dateTime('start_time')->nullable();
            $table->string('start_time_error')->nullable();

            $table->text('raw_end_time')->nullable();
            $table->dateTime('end_time')->nullable();
            $table->string('end_time_error')->nullable();

            

            // duration in seconds
            $table->unsignedInteger('duration')->nullable();

            $table->string('service_type')->nullable();
            $table->string('comm_type')->nullable();

            $table->string('comm_status')->nullable();

            $table->string('termination_category')->nullable();

            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();

            $table->string('account_number')->nullable();

            // some inmate phone systems use long PINs
            $table->string('pin')->nullable();

            // phone number or called party identifier
            $table->string('other_party')->nullable();

            $table->boolean('is_private')->default(false);

            $table->string('language')->nullable();

            $table->timestamps();

            // useful indexes
            $table->index(['last_name', 'first_name']);
            $table->index('account_number');
            $table->index('pin');
            $table->index('start_time');
            $table->index('end_time');
            $table->index('term_name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jail_call_logs');
    }
};