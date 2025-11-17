<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalTayang extends Model
{
    protected $table = 'jadwal_tayang';
    protected $fillable = [
        'film_id',
        'studio_id',
        'tanggal_tayang',
        'jam_tayang'
    ];

    protected $casts = [
        'tanggal_tayang' => 'date',
        'jam_tayang' => 'datetime:H:i',
    ];

    public function film()
    {
        return $this->belongsTo(Film::class);
    }

    public function studio()
    {
        return $this->belongsTo(Studio::class);
    }

    public function pemesanan()
    {
        return $this->hasMany(Pemesanan::class);
    }

    public function kursiTersedia()
    {
        return $this->studio->kursi()->whereDoesntHave('detailPemesanan', function ($q) {
            $q->whereHas('pemesanan', function ($p) {
                $p->where('jadwal_tayang_id', $this->id)
                    ->whereIn('status_pembayaran', ['lunas', 'pending']);
            });
        });
    }
}
