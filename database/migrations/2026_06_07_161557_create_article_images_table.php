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
        Schema::create('article_images', function (Blueprint $table) {

            // Identifiers
            $table->id();
            $table->string('hex', 11)->unique();

            // Core content
            $table->foreignId('article_id')->constrained()->cascadeOnDelete();
            $table->string('path');
            $table->string('caption')->nullable();
            $table->string('source')->nullable();
            $table->string('source_url')->nullable();
            $table->string('alt_text')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->integer('sort_order')->default(0);
            
            // Relations
            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('article_images');
    }
};
