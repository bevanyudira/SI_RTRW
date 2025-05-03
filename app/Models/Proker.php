<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proker extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul',
        'isi',
        'waktu',
        'tanggal_pelaksanaan',
        'status',
        'lokasi',
        'gambar'
    ];

    // Relasi ke model DetailProkerRT
    public function detailProkerRT()
    {
        return $this->hasMany(DetailProkerRt::class); // id_proker adalah foreign key di detail_proker_rt
    }

    // Relasi ke model DetailProkerRW
    public function detailProkerRW()
    {
        return $this->hasMany(DetailProkerRw::class); // id_proker adalah foreign key di detail_proker_rw
    }
}
