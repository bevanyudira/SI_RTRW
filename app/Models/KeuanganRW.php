<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KeuanganRw extends Model
{
    use HasFactory;

    protected $fillable = [
        'rw_id',
        'jenis',
        'jumlah',
        'path_file',
        'keterangan'
    ];

    public function rw()
    {
        return $this->belongsTo(Rw::class);
    }
}
