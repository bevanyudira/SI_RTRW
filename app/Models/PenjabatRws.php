<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenjabatRw extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'rw_id'
    ];
}
