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
            $table->string('payment_status')->default('unpaid')->after('status'); // unpaid, paid, failed, cancelled
            $table->string('payment_method')->nullable()->after('payment_status'); // credit_card, bank_transfer, etc.
            $table->string('transaction_id')->nullable()->after('payment_method'); // Midtrans transaction ID
            $table->string('order_id')->nullable()->after('transaction_id'); // Custom order ID
            $table->json('payment_response')->nullable()->after('order_id'); // Store Midtrans response
            $table->timestamp('paid_at')->nullable()->after('payment_response');
            $table->timestamp('payment_expired_at')->nullable()->after('paid_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'payment_status',
                'payment_method',
                'transaction_id',
                'order_id',
                'payment_response',
                'paid_at',
                'payment_expired_at'
            ]);
        });
    }
};