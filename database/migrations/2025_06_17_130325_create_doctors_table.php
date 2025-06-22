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
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->text('respective_en')->nullable();
            $table->text('respective_ar')->nullable();
            $table->integer('experience_years')->nullable();
            $table->json('services-en')->nullable();
            $table->json('services-ar')->nullable();
            $table->string('bloodGroup')->nullable();
            $table->date('start_in')->nullable();
            $table->date('hold_end')->nullable();
            $table->decimal('evaluation')->nullable();
            $table->enum('doctor_type',['Clinic','Laboratory','Radiographer','Relief']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctors');
    }
};
