<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->unique('order_id', 'bookings_order_id_unique');
            $table->index(['booth_id', 'status'], 'bookings_booth_status_index');
            $table->index(['user_id', 'created_at'], 'bookings_user_created_index');
            $table->index(['payment_status', 'payment_expired_at'], 'bookings_payment_expiry_index');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropUnique('bookings_order_id_unique');
            $table->dropIndex('bookings_booth_status_index');
            $table->dropIndex('bookings_user_created_index');
            $table->dropIndex('bookings_payment_expiry_index');
        });
    }
};
