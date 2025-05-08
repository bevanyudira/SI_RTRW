<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kritik extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function rw()
    {
        if ($this->rw_id) {
            return $this->belongsTo(Rw::class);
        }
        return null;
    }
    public function rt()
    {
        if ($this->rw_id) {
            return $this->belongsTo(Rt::class);
        }
        return null;
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
