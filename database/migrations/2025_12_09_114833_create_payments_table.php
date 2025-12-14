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
        Schema::create('payments', function (Blueprint $table) {
            $table->uuid('payment_id')->primary();
            $table->uuid('booking_id')->nullable();
            $table->decimal('amount', 10);
            $table->enum('method', ['paystack']);
            $table->string('transaction_id')->nullable();
            $table->enum('status', ['success', 'failed']);
            $table->timestamp('created_at')->nullable()->default(DB::raw("now()"));
            $table->timestamp('deleted_at')->nullable();

            $table->index('booking_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
