<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailIuranRtPengguna extends Model
{
    use HasFactory;

    protected $fillable = [
        'iuran_rt_id',
        'user_id',
        'status',
        'nomor_rekening',
        'bukti_pembayaran'
    ];
}
