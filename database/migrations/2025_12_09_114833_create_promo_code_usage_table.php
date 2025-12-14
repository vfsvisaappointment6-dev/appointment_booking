<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('promo_code_usage', function (Blueprint $table) {
            $table->uuid('promo_code_usage_id')->primary();
            $table->uuid('promo_code_id')->nullable();
            $table->uuid('user_id')->nullable();
            $table->uuid('booking_id')->nullable();
            $table->timestamp('used_at')->nullable();
            $table->timestamp('created_at')->nullable()->default(DB::raw("now()"));
            $table->timestamp('updated_at')->nullable()->default(DB::raw("now()"));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promo_code_usage');
    }
};
