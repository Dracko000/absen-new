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
        // First drop the existing check constraint
        DB::statement('ALTER TABLE attendances DROP CONSTRAINT IF EXISTS attendances_status_check');

        // Change the column type from enum to varchar/string
        DB::statement("ALTER TABLE attendances ALTER COLUMN status TYPE VARCHAR(255) USING status::text");

        // Add a new check constraint with extended values
        DB::statement("ALTER TABLE attendances ADD CONSTRAINT attendances_status_check CHECK (status IN ('Hadir', 'Terlambat', 'Tidak Hadir', 'Izin', 'Sakit'))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove the extended check constraint
        DB::statement('ALTER TABLE attendances DROP CONSTRAINT IF EXISTS attendances_status_check');

        // Change column type back to the original enum type, converting 'Izin' and 'Sakit' to 'Tidak Hadir'
        DB::statement("ALTER TABLE attendances ALTER COLUMN status TYPE VARCHAR(255) USING CASE WHEN status IN ('Izin', 'Sakit') THEN 'Tidak Hadir' ELSE status END");

        // Restore the original constraint
        DB::statement("ALTER TABLE attendances ADD CONSTRAINT attendances_status_check CHECK (status IN ('Hadir', 'Terlambat', 'Tidak Hadir'))");
    }
};
