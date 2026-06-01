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
        Schema::create('transcriptions', function (Blueprint $table) {
            $table->id();

            // Public identifier
            $table->string('hex', 11)->unique();

            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->enum('source_type', ['youtube', 'upload']);

            $table->text('source_url')->nullable();

            $table->string('title')->nullable();

            $table->string('original_filename')->nullable();

            $table->string('video_path')->nullable();
            $table->string('audio_path')->nullable();

            $table->string('json_path')->nullable();
            $table->string('srt_path')->nullable();
            $table->string('vtt_path')->nullable();

            $table->integer('duration_seconds')->nullable();

            $table->enum('status', [
                'pending',
                'processing',
                'completed',
                'failed',
            ])->default('pending');

            $table->unsignedTinyInteger('progress')->default(0);

            $table->text('error_message')->nullable();

            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transcriptions');
    }
};
