<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailIuran extends Model
{
    use HasFactory;

    public function iuran()
    {
        return $this->belongsTo(Iuran::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
