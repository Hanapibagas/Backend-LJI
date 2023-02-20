<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatAbsensi extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'masuk_kerja_id',
        'pulang_kerja_id',
        'keterangan',
        'tanggal',
    ];
}
