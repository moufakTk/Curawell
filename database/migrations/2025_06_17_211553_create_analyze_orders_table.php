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
            $table->string('bill_num')->default('');
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('doctor_id')->nullable()->constrained('doctors')->cascadeOnDelete();
            $table->string('doctor_name');
            $table->string('name');
            $table->enum('status', ['Pending', 'Accepted','InProgress','Completed','Canceled'])->default('Pending');
            $table->decimal('price')->default(0);
            $table->string('sample_type')->nullable();

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
