<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Casts\Attribute;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Primary key tabel.
     */
    protected $primaryKey = 'id';

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
     * Mutator untuk mengenkripsi password saat diset (gunakan plain string saat set).
     */
    protected function password(): Attribute
    {
        return Attribute::make(
            set: fn($value) => bcrypt($value), // Jangan pakai Hash::make manual di luar
        );
    }

    /**
     * Relasi ke tabel Warga.
     */
    public function warga()
    {
        return $this->hasOne(Warga::class, 'id_warga', 'id_warga');
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

    public function getAuthPassword()
    {
        return $this->password;
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
