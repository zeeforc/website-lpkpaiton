<?php

use App\Http\Controllers\BeritaUtamaController;
use App\Http\Controllers\KurikulumController;
use App\Http\Controllers\PelatihanController;
use App\Http\Controllers\SaranaController;
use App\Models\DokumenSyaratPkl;
use App\Models\Galery;
use App\Models\Home;
use App\Models\Team;
use App\Models\Vimi;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $home = cache()->remember('home_hero', now()->addMinutes(5), function () {
        return Home::select('id', 'title', 'bg_image')
            ->latest('id')
            ->first();
    });

    $vimi = cache()->remember('vision_mission', now()->addMinutes(5), function () {
        return Vimi::select('visi_title', 'visi_text', 'misi_title', 'misi_text')
            ->latest('id')
            ->first();
    });

    $teams = cache()->remember('home_teams', now()->addMinutes(5), function () {
        return Team::select('id', 'name', 'position', 'photo')
            ->orderBy('id')
            ->get();
    });

    return view('index', compact('home', 'vimi', 'teams'));
})->name('home');

Route::get('/index', function () {
    return redirect()->route('home');
});

Route::get('/index', function () {
    return redirect()->route('home');
});

Route::get('/kurikulum', [KurikulumController::class, 'index'])->name('kurikulum.index');
Route::get('/kurikulum/{kurikulum}', [KurikulumController::class, 'show'])->name('kurikulum.show');

Route::get('/pelatihan', [PelatihanController::class, 'index'])->name('pelatihan.index');

Route::get('/sarana', [SaranaController::class, 'detail'])
    ->name('sarana');

Route::get('/berita', [BeritaUtamaController::class, 'index'])
    ->name('berita.index');

Route::get('/berita/{beritaUtama:slug}', [BeritaUtamaController::class, 'show'])
    ->name('berita.show');

Route::get('/galeri', function () {
    $galeries = cache()->remember('galeries-image', now()->addMinutes(5), function () {
        return Galery::orderBy('id')->get();
    });

    return view('galeri', compact('galeries'));
});

Route::get('/syarat', function () {
    $dokumen = cache()->remember('syarat_pkl_doc', now()->addHours(6), function () {
        return DokumenSyaratPkl::latest('id')->first();
    });

    return view('syarat', compact('dokumen'));
})->name('syarat');

// Route::get('/detail', function () {
//     return view('detail');
// });
