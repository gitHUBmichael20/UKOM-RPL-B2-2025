<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HargaTiket extends Model
{
    protected $fillable = ['tipe_studio', 'tipe_hari', 'harga'];

    protected $casts = [
        'tipe_studio' => 'string',
        'tipe_hari' => 'string',
        'harga' => 'decimal:2',
    ];

    
    public static function getHarga($tipeStudio, $isWeekend)
    {
        $tipeHari = $isWeekend ? 'weekend' : 'weekday';
        return self::where('tipe_studio', $tipeStudio)
            ->where('tipe_hari', $tipeHari)
            ->first()?->harga ?? 0;
    }
}
