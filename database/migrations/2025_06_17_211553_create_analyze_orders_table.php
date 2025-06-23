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
        Schema::create('analyze_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('doctor_id')->nullable()->constrained('doctors')->cascadeOnDelete();
            $table->string('doctor_name_ar');
            $table->string('doctor_name_en');
            $table->enum('status', ['InProgress', 'InPreparation','Prepared','Canceled'])->default('InProgress');
            $table->decimal('price');
            $table->json('analyzed_ordering_ar');
            $table->json('analyzed_ordering_en');
            $table->string('sample_type');
            $table->integer('sample_num');
            //$table->boolean('bay');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('analyze_orders');
    }
};
