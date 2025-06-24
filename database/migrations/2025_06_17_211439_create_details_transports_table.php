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
        Schema::create('details_transports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('relief_order_id')->constrained('relief_orders')->cascadeOnDelete();
            $table->foreignId('ambulance_team_id')->constrained('ambulance_teams')->cascadeOnDelete();
            $table->string('location_en')->nullable();
            $table->string('location_ar')->nullable();
            $table->decimal('price_transport')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('details_transports');
    }
};
