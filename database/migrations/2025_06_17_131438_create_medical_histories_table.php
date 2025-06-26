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
            $table->json('chronic_diseases')->nullable();
            $table->json('chronic_diseases_ar')->nullable();
            $table->json('hereditary_diseases')->nullable();
            $table->json('hereditary_diseases_ar')->nullable();
            $table->json('new_diseases')->nullable();
            $table->json('new_diseases_ar')->nullable();
            $table->json('allergies')->nullable();
            $table->json('allergies_ar')->nullable();
            $table->string('blood_group')->nullable();
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
