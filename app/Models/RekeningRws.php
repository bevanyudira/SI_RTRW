<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekeningRw extends Model
{
    use HasFactory;

    protected $fillable = [
        'rw_id',
        'nomor_rekening',
        'saldo'
    ];
}
