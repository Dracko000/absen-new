<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, change the column type from enum to varchar/string
        DB::statement('ALTER TABLE attendances ALTER COLUMN status TYPE VARCHAR(255)');

        // Update existing records to use string values instead of enum values
        // This is handled automatically by PostgreSQL during the ALTER TABLE

        // Add a check constraint to maintain data integrity
        DB::statement("ALTER TABLE attendances ADD CONSTRAINT chk_attendance_status CHECK (status IN ('Hadir', 'Terlambat', 'Tidak Hadir', 'Izin'))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove the check constraint
        DB::statement('ALTER TABLE attendances DROP CONSTRAINT chk_attendance_status');

        // Change column type back to enum (with only original values)
        DB::statement('ALTER TABLE attendances ALTER COLUMN status TYPE VARCHAR(255) USING CASE WHEN status = \'Izin\' THEN \'Tidak Hadir\' ELSE status END');
    }
};
