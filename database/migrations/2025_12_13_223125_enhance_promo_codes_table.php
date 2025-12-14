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
        Schema::table('promo_codes', function (Blueprint $table) {
            $table->string('discount_type')->default('percentage')->comment('percentage or fixed_amount');
            $table->decimal('discount_amount', 10, 2)->nullable()->comment('Fixed amount for fixed_amount type');
            $table->string('description')->nullable();
            $table->integer('usage_limit')->nullable()->comment('Max times code can be used');
            $table->integer('times_used')->default(0);
            $table->string('applicable_to')->default('all')->comment('all, first_booking, specific_service');
            $table->uuid('service_id')->nullable();
            $table->decimal('minimum_order_value', 10, 2)->default(0)->comment('Minimum order value to use code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('promo_codes', function (Blueprint $table) {
            $table->dropColumn(['discount_type', 'discount_amount', 'description', 'usage_limit', 'times_used', 'applicable_to', 'service_id', 'minimum_order_value']);
        });
    }
};
