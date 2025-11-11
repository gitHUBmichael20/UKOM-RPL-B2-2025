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
        Schema::create('pemesanan', function (Blueprint $table) {
            $table->id();
            $table->char('kode_booking', 14)->unique();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('jadwal_tayang_id')->constrained('jadwal_tayang')->onDelete('cascade');
            $table->integer('jumlah_tiket');
            $table->decimal('total_harga', 10, 2);
            $table->enum('metode_pembayaran', ['cash', 'transfer', 'qris', 'debit']);
            $table->enum('jenis_pemesanan', ['online', 'offline']);
            $table->enum('status_pembayaran', ['pending', 'lunas', 'batal', 'redeemed'])->default('pending');
            $table->dateTime('tanggal_pemesanan');
            $table->foreignId('kasir_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemesanan');
    }
};
