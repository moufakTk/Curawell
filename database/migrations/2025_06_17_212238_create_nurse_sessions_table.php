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
        Schema::create('nurse_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_employee_id')->constrained('work_employees')->cascadeOnDelete();
            $table->enum('status',['Available','Reserved','UnAvailable','TurnOff']);
            $table->time('time_in');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nurse_sessions');
    }
};
