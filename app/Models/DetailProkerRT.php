<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailProkerRt extends Model
{
    use HasFactory;

    protected $fillable = [
        'proker_id',
        'rt_id'
    ];

    // Relasi ke model Proker
    public function proker()
    {
        return $this->belongsTo(Proker::class); // id_proker adalah foreign key di detail_proker_rt
    }

    // Relasi ke model RTModel
    public function rt()
    {
        return $this->belongsTo(Rt::class); // id_rt adalah foreign key di detail_proker_rt
    }
}
