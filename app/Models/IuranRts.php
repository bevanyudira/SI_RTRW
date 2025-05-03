<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IuranRt extends Model
{
    use HasFactory;

    protected $fillable = [
        'rt_id',
        'nama_iuran',
        'total_iuran',
        'bulan',
        'jenis_iuran'
    ];
}
