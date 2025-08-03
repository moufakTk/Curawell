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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('doctor_id')->constrained('doctors')->cascadeOnDelete();
            $table->foreignId('doctor_session_id')->constrained('doctor_sessions')->cascadeOnDelete();
            $table->string('phone_number');
            $table->enum('status',['Confirmed','Occur','Don','Cancel']);
            $table->boolean('delivery')->default(false);
            $table->string('delivery_location_en')->nullable();
            //$table->string('delivery_location_ar')->nullable();
            $table->enum('appointment_type',['Electronically','FaceToFace','Point']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
