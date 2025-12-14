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
        Schema::table('promo_code_usage', function (Blueprint $table) {
            $table->foreign(['promo_code_id'], 'promo_code_usage_promo_code_id_fkey')->references(['promo_code_id'])->on('promo_codes')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['user_id'], 'promo_code_usage_user_id_fkey')->references(['user_id'])->on('users')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['booking_id'], 'promo_code_usage_booking_id_fkey')->references(['booking_id'])->on('bookings')->onUpdate('no action')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('promo_code_usage', function (Blueprint $table) {
            $table->dropForeign('promo_code_usage_promo_code_id_fkey');
            $table->dropForeign('promo_code_usage_user_id_fkey');
            $table->dropForeign('promo_code_usage_booking_id_fkey');
        });
    }
};
