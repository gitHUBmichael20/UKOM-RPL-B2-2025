<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Film extends Model
{
    protected $fillable = [
        'sutradara_id',
        'judul',
        'durasi',
        'sinopsis',
        'poster',
        'rating',
        'tahun_rilis',
        'status'
    ];

    protected $casts = [
        'rating' => 'string',
        'status' => 'string',
        'tahun_rilis' => 'integer',
    ];

    public function sutradara()
    {
        return $this->belongsTo(Sutradara::class);
    }

    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'film_genre');
    }

    public function jadwalTayang()
    {
        return $this->hasMany(JadwalTayang::class);
    }
}
