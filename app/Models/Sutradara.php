<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sutradara extends Model
{
    protected $fillable = ['nama_sutradara', 'foto_profil', 'biografi'];

    public function films()
    {
        return $this->hasMany(Film::class);
    }
}
