<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPemesanan extends Model
{
    protected $fillable = ['pemesanan_id', 'kursi_id'];

    public function pemesanan()
    {
        return $this->belongsTo(Pemesanan::class);
    }

    public function kursi()
    {
        return $this->belongsTo(Kursi::class);
    }
}
