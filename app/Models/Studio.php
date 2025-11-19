<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Studio extends Model
{
    protected $table = 'studio';
    protected $fillable = ['nama_studio', 'kapasitas_kursi', 'tipe_studio'];

    protected $casts = [
        'tipe_studio' => 'string',
    ];

    public function kursi()
    {
        return $this->hasMany(Kursi::class);
    }

    public function jadwalTayang()
    {
        return $this->hasMany(JadwalTayang::class);
    }

    // Add this missing relationship
    public function hargaTiket()
    {
        return $this->hasMany(HargaTiket::class, 'tipe_studio', 'tipe_studio');
    }
}
