<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MutasiRekeningRt extends Model
{
    use HasFactory;

    protected $fillable = [
        'rekening_rt_id',
        'jenis',
        'jumlah',
        'saldo_awal',
        'saldo_akhir',
        'keterangan'
    ];
}
