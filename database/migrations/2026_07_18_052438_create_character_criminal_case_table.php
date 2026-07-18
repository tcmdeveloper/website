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
        Schema::create('character_criminal_case', function (Blueprint $table) {

            $table->id();

            $table->foreignId('character_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('criminal_case_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->enum('role', [
                'defendant',
                'victim',
                'witness',
                'lawyer',
                'judge',
                'investigator',
                'family',
                'other',
            ]);

            $table->timestamps();

            $table->unique(
                ['character_id', 'criminal_case_id', 'role'],
                'char_case_role_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('character_criminal_case');
    }
};
