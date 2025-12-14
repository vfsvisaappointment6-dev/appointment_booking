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
        Schema::table('bookings', function (Blueprint $table) {
            $table->foreign(['service_id'], 'bookings_service_id_fkey')->references(['service_id'])->on('services')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['staff_id'], 'bookings_staff_id_fkey')->references(['user_id'])->on('users')->onUpdate('no action')->onDelete('set null');
            $table->foreign(['user_id'], 'bookings_user_id_fkey')->references(['user_id'])->on('users')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign('bookings_service_id_fkey');
            $table->dropForeign('bookings_staff_id_fkey');
            $table->dropForeign('bookings_user_id_fkey');
        });
    }
};
