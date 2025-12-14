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
        Schema::table('reviews', function (Blueprint $table) {
            $table->foreign(['booking_id'], 'reviews_booking_id_fkey')->references(['booking_id'])->on('bookings')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['customer_id'], 'reviews_customer_id_fkey')->references(['user_id'])->on('users')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['staff_id'], 'reviews_staff_id_fkey')->references(['user_id'])->on('users')->onUpdate('no action')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropForeign('reviews_booking_id_fkey');
            $table->dropForeign('reviews_customer_id_fkey');
            $table->dropForeign('reviews_staff_id_fkey');
        });
    }
};
