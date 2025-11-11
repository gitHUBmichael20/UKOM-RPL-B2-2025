<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class FilmGenre extends Pivot
{
    protected $table = 'film_genre';
    public $timestamps = true;
}