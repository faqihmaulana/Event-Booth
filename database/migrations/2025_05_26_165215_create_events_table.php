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
        Schema::create('events', function (Blueprint $table) {
            $table->id();

            // Data umum event utama
            $table->string('main_title');              // Contoh: Tegal Tea Fiesta
            $table->text('main_description');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('main_image')->nullable();

            // Data sub-event (optional / bisa diisi jika ini adalah sub-event)
            $table->enum('category', ['EXHIBITION', 'PERFORMING ART', 'FOOD BAZAAR', 'MUSIC'])->nullable();
            $table->string('time')->nullable();         // contoh: "10.00 - 12.00"
            $table->string('title')->nullable();        // judul sub-event
            $table->text('description')->nullable();    // deskripsi sub-event
            $table->string('speaker')->nullable();
            $table->string('position')->nullable();
            $table->string('image')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
