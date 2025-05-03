<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailIuranRwRt extends Model
{
    use HasFactory;

    protected $fillable = [
        'rt_id',
        'iuran_rw_id',
        'status',
        'nomer_rekening',
        'bukti_pembayaran'
    ];
}
