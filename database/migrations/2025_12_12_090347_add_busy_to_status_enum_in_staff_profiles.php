<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add 'busy' to the enum constraint
        DB::statement("ALTER TABLE staff_profiles DROP CONSTRAINT staff_profiles_status_check");
        DB::statement("ALTER TABLE staff_profiles ADD CONSTRAINT staff_profiles_status_check CHECK (status IN ('active', 'inactive', 'on-leave', 'busy'))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE staff_profiles DROP CONSTRAINT staff_profiles_status_check");
        DB::statement("ALTER TABLE staff_profiles ADD CONSTRAINT staff_profiles_status_check CHECK (status IN ('active', 'inactive', 'on-leave'))");
    }
};
