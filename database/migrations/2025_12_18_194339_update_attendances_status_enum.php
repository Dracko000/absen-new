<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // This migration is no longer needed as the previous migration (create_izin_status_enum)
        // has already converted the column from enum to VARCHAR with the required check constraint
        // that supports additional status values like 'Izin' and 'Sakit'
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration does nothing, so the reverse also does nothing
    }
};
