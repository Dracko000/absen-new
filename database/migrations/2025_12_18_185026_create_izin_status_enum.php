<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modify the status column to allow the new values (for SQLite compatibility)
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->string('status', 255)->default('Tidak Hadir')->after('time_out');
        });

        // Update existing records to ensure valid status values
        DB::table('attendances')->whereIn('status', ['Hadir', 'Terlambat', 'Tidak Hadir', 'Izin', 'Sakit'])->update(['status' => DB::raw('status')]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->enum('status', ['Hadir', 'Terlambat', 'Tidak Hadir'])->default('Tidak Hadir');
        });
    }
};
