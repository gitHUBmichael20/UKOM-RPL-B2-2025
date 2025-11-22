<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
class Pemesanan extends Model
{
    protected $table = 'pemesanan';

    protected $fillable = [
        'kode_booking',
        'user_id',
        'jadwal_tayang_id',
        'jumlah_tiket',
        'total_harga',
        'metode_pembayaran',
        'jenis_pemesanan',
        'status_pembayaran',
        'tanggal_pemesanan',
        'kasir_id'
    ];

    protected $casts = [
        'tanggal_pemesanan' => 'datetime',
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
public function generateQRCode()
{
    if ($this->jenis_pemesanan !== 'online') {
        return null;
    }

    $qrCode = new QrCode(
        data: $this->kode_booking,
        encoding: new Encoding('UTF-8'),
        errorCorrectionLevel: ErrorCorrectionLevel::High,
        size: 300,
        margin: 10
    );
    
    $writer = new PngWriter();
    $result = $writer->write($qrCode);
    
    // Convert ke base64
    return 'data:image/png;base64,' . base64_encode($result->getString());
}

protected static function booted()
{
    static::created(function ($pemesanan) {
        if ($pemesanan->jenis_pemesanan === 'online') {
            $qrCode = $pemesanan->generateQRCode();
            $pemesanan->update(['qr_code' => $qrCode]);
        }
    });
}

}
