<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KeuanganRt extends Model
{
    use HasFactory;

    protected $fillable = [
        'rt_id',
        'jenis',
        'jumlah',
        'path_file',
        'keterangan',
        'tanggal',
    ];

    public function rt()
    {
        return $this->belongsTo(Rt::class);
    }
}
