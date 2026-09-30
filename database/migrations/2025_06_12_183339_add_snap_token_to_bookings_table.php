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
            // Add snap_token column if not exists
            if (!Schema::hasColumn('bookings', 'snap_token')) {
                $table->text('snap_token')->nullable()->after('order_id');
            }
            
            // Add indexes for better performance
            if (!Schema::hasColumn('bookings', 'payment_expired_at')) {
                $table->timestamp('payment_expired_at')->nullable()->after('paid_at');
            }
            
            // Add index on order_id for faster lookups
            $table->index('order_id');
            $table->index('payment_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['snap_token', 'payment_expired_at']);
            $table->dropIndex(['order_id']);
            $table->dropIndex(['payment_status']);
        });
    }
};