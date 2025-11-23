<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pemesanan extends Model
{
    protected $table = 'pemesanan';

    protected $fillable = [
        'kode_booking',
        'user_id',
        'user_name',
        'jadwal_tayang_id',
        'jumlah_tiket',
        'total_harga',
        'metode_pembayaran',
        'jenis_pemesanan',
        'status_pembayaran',
        'tanggal_pemesanan',
        'kasir_id',
        'snap_token',
        'expired_at'
    ];

    protected $casts = [
        'tanggal_pemesanan' => 'datetime',
        'expired_at' => 'datetime',
        'total_harga' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kasir()
    {
        return $this->belongsTo(User::class, 'kasir_id');
    }

    public function jadwalTayang()
    {
        return $this->belongsTo(JadwalTayang::class);
    }

    public function detailPemesanan()
    {
        return $this->hasMany(DetailPemesanan::class);
    }

    public static function generateKodeBooking()
    {
        $last = self::latest('id')->first();
        $number = $last ? $last->id + 1 : 1;
        return 'BK' . now()->format('Ymd') . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    public function getNamaPembayaranAttribute()
    {
        return nama_pembayaran($this->metode_pembayaran);
    }

    public function getIkonPembayaranAttribute()
    {
        return ikon_pembayaran($this->metode_pembayaran);
    }
}
