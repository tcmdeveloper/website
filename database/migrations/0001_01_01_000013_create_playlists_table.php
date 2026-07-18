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
        Schema::create('playlists', function (Blueprint $table) {

            $table->id();
            $table->string('hex', 11)->unique();

            $table->string('name');

            $table->string('slug')->unique();

            $table->text('description')->nullable();

            $table->string('thumbnail')->nullable();

            $table->unsignedInteger('views')->default(0);

            $table->boolean('is_published')->default(false);

            $table->timestamp('published_at')->nullable();

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
        Schema::dropIfExists('playlists');
    }
};
