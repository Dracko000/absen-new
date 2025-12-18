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
        Schema::table('class_models', function (Blueprint $table) {
            $table->time('entry_time')->nullable()->after('description'); // Class entry time
            $table->time('exit_time')->nullable()->after('entry_time');  // Class exit time
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('class_models', function (Blueprint $table) {
            $table->dropColumn(['entry_time', 'exit_time']);
        });
    }
};
