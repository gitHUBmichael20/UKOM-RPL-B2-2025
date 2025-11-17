<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HargaTiket extends Model
{
    use HasFactory;

    protected $table = 'harga_tiket';

    protected $fillable = [
        'tipe_studio',
        'tipe_hari',
        'harga'
    ];

    protected $casts = [
        'harga' => 'decimal:2'
    ];

    // Konstanta untuk tipe studio
    const TIPE_STUDIO = [
        'regular' => 'Regular',
        'deluxe' => 'Deluxe',
        'imax' => 'IMAX'
    ];

    // Konstanta untuk tipe hari
    const TIPE_HARI = [
        'weekday' => 'Weekday',
        'weekend' => 'Weekend'
    ];

    // buat format harga
    public function getFormattedHargaAttribute()
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }

    // buat label tipe studio
    public function getTipeStudioLabelAttribute()
    {
        return self::TIPE_STUDIO[$this->tipe_studio] ?? $this->tipe_studio;
    }

    // buat label tipe hari
    public function getTipeHariLabelAttribute()
    {
        return self::TIPE_HARI[$this->tipe_hari] ?? $this->tipe_hari;
    }
}