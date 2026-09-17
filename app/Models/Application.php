<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    protected $fillable = [
        'user_id', 'nama_lengkap', 'instansi', 'tingkat_pendidikan', 'jurusan', 'no_hp',
        'pengajuan', 'periode_gelombang', 'jumlah_peserta', 'lama_durasi_bulan',
        'fokus_studi', 'email_balasan', 'status',
        'start_date', 'end_date', 'is_jalur_khusus'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_jalur_khusus' => 'boolean',
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

    public static function getActiveQuotaCount()
    {
        return self::where('status', 'accepted')
            ->where('is_jalur_khusus', false)
            ->where('end_date', '>=', now()->toDateString())
            ->count();
    }

    public static function getPredictedOpenMonth()
    {
        $closestEnd = self::where('status', 'accepted')
            ->where('is_jalur_khusus', false)
            ->where('end_date', '>=', now()->toDateString())
            ->orderBy('end_date', 'asc')
            ->first();

        if ($closestEnd && $closestEnd->end_date) {
            $nextDay = $closestEnd->end_date->addDay();
            $months = [
                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
            ];
            return $months[$nextDay->month] . ' ' . $nextDay->year;
        }

        return 'Segera';
    }
}
