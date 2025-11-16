<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FilmGenre extends Model
{
    use HasFactory;

    protected $table = 'film_genre';

    protected $fillable = [
        'film_id',
        'genre_id',
    ];

    public $timestamps = true;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    /**
     * Relasi ke Film
     */
    public function film()
    {
        return $this->belongsTo(Film::class, 'film_id');
    }

    /**
     * Relasi ke Genre
     */
    public function genre()
    {
        return $this->belongsTo(Genre::class, 'genre_id');
    }
}