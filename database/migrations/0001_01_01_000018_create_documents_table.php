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
        Schema::create('documents', function (Blueprint $table) {

            // Identifiers
            $table->id();
            $table->string('hex', 11)->unique();

            // Core content
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('pdf_path');
            $table->unsignedInteger('pages')->default(0);
            $table->unsignedBigInteger('filesize')->nullable();

            // Meta
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('og_image')->nullable();

            // Stats
            $table->unsignedInteger('views')->default(0);

            // Relations
            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('category_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('criminal_case_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            // No foreign key constraint here
            $table->unsignedBigInteger('docket_entry_id')
                ->nullable();

            // Publishing
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();

            $table->timestamps();

            $table->index(['is_published', 'published_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};