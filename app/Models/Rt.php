<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rt extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function rw()
    {
        return $this->belongsTo(Rw::class);
    }
    public function wargas()
    {
        return $this->hasMany(Warga::class);
    }
}
