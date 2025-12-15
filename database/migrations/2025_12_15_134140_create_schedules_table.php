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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('class_model_id'); // Foreign key to class_models table
            $table->string('subject');
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('day_of_week'); // 1 = Monday, 2 = Tuesday, etc.
            $table->date('date')->nullable(); // Specific date if needed
            $table->timestamps();

            $table->foreign('class_model_id')->references('id')->on('class_models')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
