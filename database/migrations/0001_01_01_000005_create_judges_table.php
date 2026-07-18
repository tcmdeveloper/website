<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('judges', function (Blueprint $table) {
            $table->id();

            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');

            $table->string('title')->nullable(); // e.g. "Chief Judge"

            $table->string('court')->nullable(); // e.g. "Leon County Circuit Court"

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('judges');
    }
};