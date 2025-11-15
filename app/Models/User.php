<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'foto_profil',
        'role'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getProfilePhotoUrlAttribute()
    {
        if ($this->foto_profil && Storage::disk('public')->exists($this->foto_profil)) {
            return Storage::disk('public')->url($this->foto_profil);
        }

        return asset('storage/default_profile.jpg');
    }

    public function pemesananOnline()
    {
        return $this->hasMany(Pemesanan::class, 'user_id')
            ->where('jenis_pemesanan', 'online');
    }

    public function pemesananOffline()
    {
        return $this->hasMany(Pemesanan::class, 'kasir_id');
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }
    public function isKasir()
    {
        return $this->role === 'kasir';
    }
    public function isPelanggan()
    {
        return $this->role === 'pelanggan';
    }

    protected static function booted()
    {
        static::creating(function ($user) {
            if (empty($user->role)) {
                $user->role = 'pelanggan';
            }
        });
    }
}
