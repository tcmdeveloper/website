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
        Schema::create('document_pages', function (Blueprint $table) {
            
            // Identifiers
            $table->id();
            $table->string('hex', 11)->unique();

            // Relations
            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('document_id')
                ->constrained()
                ->cascadeOnDelete();
            
            // Core content
            $table->unsignedInteger('page_number');
            $table->string('image_path');
            $table->unsignedInteger('width')->nullable();
            $table->unsignedInteger('height')->nullable();

            // Stats
            $table->unsignedInteger('views')->default(0);

            $table->timestamps();

            // For a given document, each page number can only exist once
            $table->unique(['document_id', 'page_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_pages');
    }
};
