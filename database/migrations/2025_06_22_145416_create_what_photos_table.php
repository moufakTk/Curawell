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
        Schema::create('what_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('skiagraph_order_id')->constrained('skiagraph_orders')->cascadeOnDelete();
            $table->foreignId('small_service_id')->constrained('small_services')->cascadeOnDelete();
            $table->decimal('price');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('what_photos');
    }
};
