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
            // Menambahkan kolom lokasi
            $table->string('location')->nullable()->after('main_image');
            
            // Mengubah category dari enum menjadi string
            $table->string('category')->nullable()->change();
            
            // Menghapus kolom image
            $table->dropColumn('image');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            // Mengembalikan kolom image
            $table->string('image')->nullable();
            
            // Mengembalikan category ke enum
            $table->enum('category', ['EXHIBITION', 'PERFORMING ART', 'FOOD BAZAAR', 'MUSIC'])->nullable()->change();
            
            // Menghapus kolom location
            $table->dropColumn('location');
        });
    }
};