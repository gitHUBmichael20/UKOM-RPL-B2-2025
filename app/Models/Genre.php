<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    use HasFactory;

    protected $table = 'genre';

    protected $fillable = [
        'nama_genre',
    ];

    public $timestamps = true;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public function filmGenres()
    {
        return $this->hasMany(FilmGenre::class, 'genre_id');
    }

    /**
     * Relasi ke Film melalui FilmGenre
     */
    public function films()
    {
        return $this->belongsToMany(Film::class, 'film_genre', 'genre_id', 'film_id')
                    ->withTimestamps();
    }
}