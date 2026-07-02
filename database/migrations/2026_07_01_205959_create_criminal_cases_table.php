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
        Schema::create('criminal_cases', function (Blueprint $table) {

            // Identifiers
            $table->id();
            $table->string('hex', 11)->unique();

            // Relations
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            // Core content
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();

            // Publishing
            $table->timestamp('published_at')->nullable();
            $table->boolean('is_published')->default(false);

            // Stats (optional but useful for SaaS/blogs)
            $table->unsignedInteger('views')->default(0);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('criminal_cases');
    }
};
