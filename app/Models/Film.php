<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Film extends Model
{
    use HasFactory;

    protected $table = 'film';

    protected $fillable = [
        'sutradara_id',
        'judul',
        'durasi',
        'sinopsis',
        'poster',
        'rating',
        'tahun_rilis',
        'status',
    ];

    protected $casts = [
        'tahun_rilis' => 'integer',
    ];

    public $timestamps = true;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    /**
     * Relasi ke Sutradara
     */
    public function sutradara()
    {
        return $this->belongsTo(Sutradara::class, 'sutradara_id');
    }

    /**
     * Relasi ke FilmGenre
     */
    public function filmGenres()
    {
        return $this->hasMany(FilmGenre::class, 'film_id');
    }

    /**
     * Relasi ke Genre melalui FilmGenre
     */
    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'film_genre', 'film_id', 'genre_id')
                    ->withTimestamps();
    }

    /**
     * Relasi ke JadwalTayang
     */
    public function jadwalTayangs()
    {
        return $this->hasMany(JadwalTayang::class, 'film_id');
    }
}