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
        Schema::create('criminal_cases', function (Blueprint $table) {

            // Identifiers
            $table->id();
            $table->string('hex', 11)->unique();

            // Core content
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->date('arrest_date')->nullable(); 
            $table->char('country_code', 2)->nullable();
            $table->char('state_code', 2)->nullable();

            // SEO
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();

            // Case data
            $table->string('criminal_case_number')->nullable()->unique();
            $table->string('criminal_case_number_display')->nullable()->unique();
            $table->string('court_provider')->nullable();
            $table->timestamp('last_docket_sync_at')->nullable();

            // Stats
            $table->unsignedInteger('views')->default(0);

            // Relations
            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            // Publishing
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            

            $table->timestamps();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('criminal_cases');
    }
};
