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
        Schema::create('appointment_balls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ball_id')->constrained('balls')->cascadeOnDelete();
            $table->morphs('appointable');
            $table->decimal('total_treatment_amount');
            $table->decimal('paid_of_amount');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointment_balls');
    }
};
