<?php

namespace App\Http\Controllers;

use App\Models\BeritaUtama;
use App\Models\PklStat;
// use Illuminate\Support\Facades\Cache;

class BeritaUtamaController extends Controller
{
    public function index()
    {
        $latest = cache()->remember('berita_latest', now()->addMinutes(10), function () {
            return BeritaUtama::latest('tgl_berita')->first();
        });

        $others = BeritaUtama::when($latest, function ($q) use ($latest) {
            $q->where('id', '!=', $latest->id);
        })
            ->latest('tgl_berita')
            ->paginate(6);

        $pklStat = cache()->remember('pkl_stat', now()->addHours(1), function () {
            return PklStat::first();
        });

        return view('berita', compact('latest', 'others', 'pklStat'));
    }

    public function show(BeritaUtama $beritaUtama)
    {
        // cari berita sebelumnya (tgl lebih kecil, paling dekat)
        $prevBerita = BeritaUtama::where('tgl_berita', '<', $beritaUtama->tgl_berita)
            ->orderBy('tgl_berita', 'desc')
            ->first();

        // cari berita berikutnya (tgl lebih besar, paling dekat)
        $nextBerita = BeritaUtama::where('tgl_berita', '>', $beritaUtama->tgl_berita)
            ->orderBy('tgl_berita', 'asc')
            ->first();

        return view('detail', [
            'berita'     => $beritaUtama,
            'prevBerita' => $prevBerita,
            'nextBerita' => $nextBerita,
        ]);
    }
}
