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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('inquiry_number')->nullable();
            $table->string('complaint_number')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('site_name')->nullable();
           // $table->string('site_name_ar')->nullable();
            $table->text('preface_en')->nullable();
            $table->text('preface_ar')->nullable();
            $table->text('wise_en')->nullable();
            $table->text('wise_ar')->nullable();
            $table->string('address_en')->nullable();
            //$table->string('address_ar')->nullable();
            $table->string('logo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
