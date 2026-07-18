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
        Schema::create('characters', function (Blueprint $table) {

            $table->id();

            // URLs
            $table->string('slug')->unique();
            $table->string('hex', 11)->unique();

            // Basic details
            $table->string('name');

            $table->enum('type', [
                'defendant',
                'victim',
                'witness',
                'lawyer',
                'judge',
                'investigator',
                'family',
                'other',
            ])->default('other');

            // Personal details
            $table->date('date_of_birth')->nullable();
            $table->date('date_of_death')->nullable();

            $table->string('gender')->nullable();
            $table->string('nationality')->nullable();

            // Content
            $table->text('description')->nullable();

            // SEO
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();

            // Media
            $table->string('image_path')->nullable();

            // Stats
            $table->unsignedInteger('views')->default(0);

            // Publishing
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();

            $table->timestamps();

            $table->index('slug');
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('characters');
    }
};
