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
        Schema::create('harga_tiket', function (Blueprint $table) {
            $table->id();
            $table->enum('tipe_studio', ['regular', 'deluxe', 'imax']);
            $table->enum('tipe_hari', ['weekday', 'weekend']);
            $table->decimal('harga', 10, 2);
            $table->timestamps();
            $table->unique(['tipe_studio', 'tipe_hari']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('harga_tiket');
    }
};
