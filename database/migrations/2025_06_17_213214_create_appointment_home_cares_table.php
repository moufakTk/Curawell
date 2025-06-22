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
        Schema::create('appointment_home_cares', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('nurse_session_id')->constrained('nurse_sessions')->cascadeOnDelete();
            $table->enum('type',['CheckOut','Physical','Sample']);
            $table->enum('gender',['Male','Female']);
            $table->string('location_en')->nullable();
            $table->string('location_ar')->nullable();
            $table->string('phone_number');
            $table->decimal('price');
            $table->text('explain');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointment_home_cares');
    }
};
