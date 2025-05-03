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
    protected $fillable = [
        'email',
        'password',
        'no_hp',
        'role',
        'aktivasi',
        'warga_id'
    ];

    /**
     * Kolom yang disembunyikan saat serialisasi JSON.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Kolom yang akan otomatis dikonversi ke tipe data tertentu.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Relasi ke tabel Warga.
     */
    public function warga()
    {
        return $this->hasOne(Warga::class);
    }

    /**
     * Implementasi autentikasi.
     */
    public function getAuthIdentifierName()
    {
        return $this->getKeyName();
    }

    public function getAuthIdentifier()
    {
        return $this->getKey();
    }

    public function getRememberToken()
    {
        return $this->remember_token;
    }

    public function setRememberToken($value)
    {
        $this->remember_token = $value;
    }

    public function getRememberTokenName()
    {
        return 'remember_token';
    }
}
