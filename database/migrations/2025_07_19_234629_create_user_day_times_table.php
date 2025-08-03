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
        Schema::create('user_day_times', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('day_en');
            $table->string('day_ar');
           // $table->enum('day_en',['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday']);
           // $table->enum('day_ar',['الاثنين','الثلاثاء','الأربعاء','الخميس','الجمعة','السبت','الأحد']);
            $table->time('timeStart');
            $table->time('timeEnd');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_day_times');
    }
};
