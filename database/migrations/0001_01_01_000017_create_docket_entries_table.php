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
        Schema::create('docket_entries', function (Blueprint $table) {

            // Identifiers
            $table->id();
            $table->string('hex', 11)->unique();

            $table->foreignId('criminal_case_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->integer('sequence_number');

            $table->string('docket_code')->nullable();

            $table->date('filed_at')->nullable();

            $table->text('title');

            $table->boolean('has_document')->default(false);

            $table->string('input_document_id')->nullable();

            $table->string('document_title')->nullable();

            $table->string('viewer_qs')->nullable();

            $table->longText('viewer_parameters')
                ->nullable();

            $table->longText('mobile_viewer_parameters')
                ->nullable();

            $table->string('encoded_document_path')->nullable();

            $table->unsignedInteger('page_count')->nullable();


            $table->json('raw_data')->nullable();

            $table->timestamps();

            $table->unique([
                'criminal_case_id',
                'sequence_number',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
{
    Schema::table('docket_entries', function (Blueprint $table) {
        $table->dropForeign(['criminal_case_id']);
    });

    Schema::dropIfExists('docket_entries');
}
};
