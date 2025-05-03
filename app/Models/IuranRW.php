<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IuranRw extends Model
{
    use HasFactory;

    protected $fillable = [
        'rw_id',
        'nama_iuran',
        'total_iuran',
        'bulan',
        'jenis_iuran'
    ];
}
