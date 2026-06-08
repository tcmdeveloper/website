<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();

            // Public identifier
            $table->string('hex', 11)->unique();

            // Core content
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->text('content');

            // Relations
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('category_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            // Publishing
            $table->timestamp('published_at')->nullable();
            $table->boolean('is_published')->default(false);

            // SEO
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();

            // Stats (optional but useful for SaaS/blogs)
            $table->unsignedInteger('views')->default(0);

            $table->timestamps();

            $table->index(['is_published', 'published_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};