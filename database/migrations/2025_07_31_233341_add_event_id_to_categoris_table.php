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
        Schema::table('categoris', function (Blueprint $table) {
            $table->unsignedBigInteger('event_id')->after('id');
            
            // Jika Anda ingin menambahkan foreign key constraint
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categoris', function (Blueprint $table) {
            // Drop foreign key constraint terlebih dahulu (jika ada)
            $table->dropForeign(['event_id']);
            
            // Kemudian drop kolom
            $table->dropColumn('event_id');
        });
    }
};