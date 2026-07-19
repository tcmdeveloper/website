<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('criminal_case_court_identifiers', function (Blueprint $table) {

            $table->id();

            $table->foreignId('criminal_case_id')
                ->constrained()
                ->cascadeOnDelete();

            /*
             * Provider name:
             * leon
             * miamidade
             */
            $table->string('provider');


            /*
             * Identifier name:
             * caseid
             * jiscaseid
             * uri
             */
            $table->string('key');


            /*
             * Identifier value:
             * 2785598
             * 1129969
             */
            $table->string('value');


            $table->timestamps();


            $table->unique(
                ['criminal_case_id', 'provider', 'key'],
                'ccci_case_provider_key_unique'
            );
        });
    }


    public function down(): void
    {
        Schema::dropIfExists(
            'criminal_case_court_identifiers'
        );
    }
};