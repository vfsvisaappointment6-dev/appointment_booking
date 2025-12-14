<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Alter the sessions.user_id column to varchar(36) so UUIDs are accepted.
        // Use raw SQL to avoid requiring the doctrine/dbal package.
        $driver = DB::getDriverName();

        if ($driver === 'pgsql') {
            DB::statement("ALTER TABLE sessions ALTER COLUMN user_id TYPE varchar(36) USING (user_id::varchar);");
        } else {
            // For MySQL/SQLite, alter column to varchar(36)
            Schema::table('sessions', function (Blueprint $table) {
                $table->string('user_id', 36)->nullable()->index()->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'pgsql') {
            // Attempt to cast back to bigint where possible; otherwise set NULL.
            // This may fail if values are non-numeric; use NULL to be safe.
            DB::statement("ALTER TABLE sessions ALTER COLUMN user_id TYPE bigint USING (NULL::bigint);");
        } else {
            Schema::table('sessions', function (Blueprint $table) {
                $table->bigInteger('user_id')->nullable()->index()->change();
            });
        }
    }
};
