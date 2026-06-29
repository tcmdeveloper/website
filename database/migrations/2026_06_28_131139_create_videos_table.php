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
        Schema::create('videos', function (Blueprint $table) {
            $table->id();

            $table->string('hex', 11)->unique();

            $table->string('youtube_url');
            $table->string('youtube_id');

            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->integer('duration')->nullable();

            $table->string('filename')->nullable();
            $table->string('thumbnail')->nullable();

            $table->string('status')->default('pending');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('videos');
    }
};
