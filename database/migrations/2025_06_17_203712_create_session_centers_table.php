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
        Schema::create('session_centers', function (Blueprint $table) {
            $table->id();
            $table->morphs('sessionable');
            $table->string('session_name')->nullable();
            $table->json('diagnosis')->nullable();
            $table->text('symptoms')->nullable();
            $table->text('medicines')->nullable();
            $table->decimal('doctor_examination')->default(0);
            $table->decimal('doctor_examination_discount')->default(0);
            $table->decimal('all_discount')->default(0);
            $table->enum('session_type',['Relief','Clinic']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('session_centers');
    }
};
