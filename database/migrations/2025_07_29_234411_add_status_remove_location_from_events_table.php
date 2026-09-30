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
        Schema::table('events', function (Blueprint $table) {
            // Tambah kolom status
            $table->enum('status', ['active', 'inactive', 'draft', 'completed'])->default('draft')->after('main_image');
            
            // Hapus kolom location
            $table->dropColumn('location');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            // Kembalikan kolom location
            $table->string('location')->nullable()->after('end_date');
            
            // Hapus kolom status
            $table->dropColumn('status');
        });
    }
};