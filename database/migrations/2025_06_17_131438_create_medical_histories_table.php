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
        Schema::create('medical_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->json('chronic_diseases-en')->nullable();
            $table->json('chronic_diseases-ar')->nullable();
            $table->json('hereditary_diseases-en')->nullable();
            $table->json('hereditary_diseases-ar')->nullable();
            $table->json('new_diseases-en')->nullable();
            $table->json('new_diseases-ar')->nullable();
            $table->json('sensitivities-en')->nullable();
            $table->json('sensitivities-ar')->nullable();
            $table->string('bloodGroup')->nullable();
            $table->decimal('weight')->nullable();
            $table->decimal('height')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medical_histories');
    }
};
