<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('email_change_requests', function (Blueprint $table) {
            $table->id();

            // User requesting the change
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            // Current email at time of request
            $table->string('old_email');

            // Email user wants to switch to
            $table->string('new_email');

            // Secure verification token
            $table->string('token', 64)->unique();

            $table->string('status');

            // Verification lifecycle
            $table->timestamp('verified_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();

            $table->timestamps();

            // Helpful indexes
            $table->index(['user_id', 'verified_at']);
            $table->index('expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_change_requests');
    }
};
