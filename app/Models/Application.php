<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    protected $fillable = [
        'user_id', 'nama_lengkap', 'instansi', 'tingkat_pendidikan', 'jurusan', 'no_hp',
        'pengajuan', 'periode_gelombang', 'jumlah_peserta', 'lama_durasi_bulan',
        'fokus_studi', 'email_balasan', 'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function documents()
    {
        return $this->hasMany(ApplicationDocument::class);
    }

    public function notes()
    {
        return $this->hasMany(ApplicationNote::class);
    }
}
