<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PklStat extends Model
{
    protected $fillable = [
        'total_peserta',
        'peserta_aktif',
        'jumlah_jurusan',
        'jumlah_sekolah',
        'jumlah_program',
    ];

    protected $casts = [
        'total_peserta'  => 'integer',
        'peserta_aktif'  => 'integer',
        'jumlah_jurusan' => 'integer',
        'jumlah_sekolah' => 'integer',
        'jumlah_program' => 'integer',
    ];
}
