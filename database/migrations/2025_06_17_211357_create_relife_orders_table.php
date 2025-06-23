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
        Schema::create('relife_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->enum('order_type', ['Call','FaceToFace','NonEmergency']);
            $table->enum('destination',['MedicalCenter','AlHiaHospital','AlHilalHospital','UnKnown']);
            $table->boolean('use_car')->default(false);
            $table->enum('status', ['InProgress','Completed','Canceled'])->default('InProgress');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('relife_orders');
    }
};
