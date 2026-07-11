<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    // RUN MIGRATION

    public function up(): void
    {
        Schema::create('images', function (Blueprint $table) {

            // Identifiers
            $table->id();
            $table->string('hex', 11)->unique();

            // Polymorphic relationship
            $table->morphs('imageable');

            // Core content
            $table->string('image_path');
            $table->string('alt_text')->nullable();
            $table->text('caption')->nullable();
            $table->string('credit_name')->nullable();
            $table->string('credit_url')->nullable();

            // Display
            $table->boolean('is_featured')->default(false);
            $table->boolean('has_multiformat')->default(false);
            $table->unsignedInteger('sort_order')->default(0);

            // Uploaded by
            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    
    // REVERSE MIGRATION

    public function down(): void
    {
        Schema::dropIfExists('images');
    }

};
