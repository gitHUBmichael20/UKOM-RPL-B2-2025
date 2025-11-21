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
        Schema::create('film', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sutradara_id')->constrained('sutradara')->onDelete('cascade');
            $table->string('judul', 200);
            $table->integer('durasi');
            $table->text('sinopsis')->nullable();
            $table->string('poster', 255)->nullable();
            $table->enum('rating', ['SU', 'R13+', 'D17+', 'D21+']);
            $table->year('tahun_rilis');
            $table->enum('status', ['tayang', 'segera', 'selesai'])->default('segera');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('film');
    }
};
