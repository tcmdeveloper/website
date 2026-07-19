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
        Schema::create('timeline_events', function (Blueprint $table) {

            $table->id();

            $table->string('hex', 11)->unique();

            $table->foreignId('timeline_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('parent_event_id')
                ->nullable()
                ->constrained('timeline_events')
                ->nullOnDelete();
            
            // Event
            $table->string('title', 150);
            $table->text('description')->nullable();

            // Importance
            $table->boolean('is_key_event')->default(false);
            $table->unsignedTinyInteger('importance')
                ->default(3);

            // Date
            $table->dateTime('occurred_at')->nullable();

            $table->enum('accuracy', [
                'exact',
                'approximate',
                'disputed',
            ])->default('exact');

            // For approximate dates/times
            $table->string('date_label')->nullable();
            $table->string('time_label')->nullable();

            

            // Ordering
            $table->unsignedInteger('sort_order')->default(0);

            // Classification
            $table->string('type', 50)->nullable();
            // murder
            // disappearance
            // arrest
            // indictment
            // trial
            // verdict
            // sentencing
            // appeal

            // Optional styling
            $table->string('icon')->nullable();
            $table->string('color', 20)->nullable();

            // References
            $table->string('source_name')->nullable();
            $table->string('source_url')->nullable();

            $table->boolean('is_published')->default(true);
            $table->timestamp('published_at')->nullable();
            
            $table->timestamps();

            $table->index([
                'timeline_id',
                'occurred_at',
                'sort_order',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('timeline_events');
    }
};
