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
        Schema::create('criminal_case_playlist', function (Blueprint $table) {

            $table->id();

            $table->foreignId('criminal_case_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('playlist_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->unsignedInteger('position')
                ->default(1);

            $table->timestamps();

            $table->unique(
                ['criminal_case_id', 'playlist_id'],
                'case_playlist_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('criminal_case_playlist');
    }
};
