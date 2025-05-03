<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KritikSaranRt extends Model
{
    use HasFactory;

    protected $fillable = [
        'rt_id',
        'user_id',
        'isi',
        'status'
    ];
}
