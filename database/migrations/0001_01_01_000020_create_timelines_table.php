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
        Schema::create('timelines', function (Blueprint $table) {
            $table->id();

            $table->string('hex', 11)->unique();

            $table->foreignId('criminal_case_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('name', 100);
            $table->string('slug')->nullable();

            $table->text('description')->nullable();

            // Timeline metadata
            $table->enum('type', [
                'case',
                'investigation',
                'trial',
                'appeal',
                'character',
                'custom',
            ])->default('case');

            $table->string('icon')->nullable();
            $table->string('color')->nullable();
            $table->unsignedInteger('sort_order')->default(0);

            // Stats
            $table->unsignedInteger('views')->default(0);


            $table->boolean('is_default')->default(false);
            $table->boolean('is_published')->default(true);
            $table->timestamp('published_at')->nullable();

            

            $table->timestamps();

            $table->unique(['criminal_case_id', 'name']);
            $table->unique(['criminal_case_id', 'slug']);
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('timelines');
    }
};
