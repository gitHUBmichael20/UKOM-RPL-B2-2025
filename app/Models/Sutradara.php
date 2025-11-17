<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sutradara extends Model
{
    protected $table = 'sutradara'; // use your actual table name

    protected $fillable = ['nama_sutradara', 'foto_profil', 'biografi'];
    protected $guarded = ['id'];

    public function films()
    {
        return $this->hasMany(Film::class);
    }
}
