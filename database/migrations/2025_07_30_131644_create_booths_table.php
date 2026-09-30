<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('booths', function (Blueprint $table) {
            $table->id();

            // Relasi ke tabel events
            $table->foreignId('event_id')->constrained()->onDelete('cascade');

            $table->string('booth_id'); // Hapus ->unique() dari sini
            $table->string('booth_name');
            $table->string('section'); // A, B, C, D, E, F, T
            $table->decimal('price', 10, 2);
            $table->integer('position_x'); // X coordinate
            $table->integer('position_y'); // Y coordinate
            $table->integer('width')->default(25);
            $table->integer('height')->default(25);
            $table->enum('status', ['available', 'booked', 'maintenance'])->default('available');
            $table->string('color')->nullable(); // Background color

            $table->timestamps();
            
            // Tambahkan unique constraint untuk kombinasi event_id + booth_id
            $table->unique(['event_id', 'booth_id'], 'booths_event_booth_unique');
        });
    }

    public function down()
    {
        Schema::dropIfExists('booths');
    }
};