<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KritikSaranRw extends Model
{
    use HasFactory;

    protected $fillable = [
        'rw_id',
        'user_id',
        'isi',
        'status'
    ];
}
