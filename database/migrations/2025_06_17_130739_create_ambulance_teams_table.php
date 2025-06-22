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
        Schema::create('ambulance_teams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_doctor_id')->constrained('users')->cascadeOnDelete();  //As Doctor
            $table->foreignId('user_nurse_id')->constrained('users')->cascadeOnDelete();  //As Nurse
            $table->foreignId('user_driver_id')->constrained('users')->cascadeOnDelete();  //As driver
            $table->boolean('status')->default(true);
            $table->boolean('working')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ambulance_teams');
    }
};
