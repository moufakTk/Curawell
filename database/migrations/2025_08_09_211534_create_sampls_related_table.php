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
        Schema::create('sampls_related', function (Blueprint $table) {
            $table->id();
            $table->foreignId('analyze_order_id')->constrained('analyze_orders')->cascadeOnDelete();
            $table->foreignId('sample_id')->constrained('samples');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sampls_related');
    }
};
