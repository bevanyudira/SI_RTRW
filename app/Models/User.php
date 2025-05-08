<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Kolom yang dapat diisi secara massal.
     */
    protected $guarded = [];

    /**
     * Kolom yang disembunyikan saat serialisasi JSON.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Relasi ke tabel Warga.
     */
    public function warga()
    {
        return $this->belongsTo(Warga::class);
    }
    public function kritiks()
    {
        return $this->hasMany(Kritik::class);
    }
    public function iurans()
    {
        return $this->hasMany(Iuran::class);
    }
}
