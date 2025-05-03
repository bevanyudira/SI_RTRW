<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekeningRt extends Model
{
    use HasFactory;

    protected $fillable = [
        'rt_id',
        'nomor_rekening',
        'saldo'
    ];
}
