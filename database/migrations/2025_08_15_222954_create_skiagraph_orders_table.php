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
        Schema::create('skiagraph_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('small_service_id')->constrained('small_services')->cascadeOnDelete();
            $table->string('doctor_name');
            $table->decimal('price');
            $table->enum('status', ['InPreparation', 'Prepared','Canceled'])->default('InPreparation');
            //$table->boolean('bay')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skiagraph_orders');
    }
};
