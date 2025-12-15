<?php

namespace App\Http\Controllers;

use App\Models\Kurikulum;
use Illuminate\Support\Facades\Cache;

class KurikulumController extends Controller
{
    public function index()
    {
        $kurikulums = Cache::rememberForever('kurikulums_all', function () {
            return Kurikulum::orderBy('id')->get();
        });

        return view('kurikulum', compact('kurikulums'));
    }

    public function show(Kurikulum $kurikulum)
    {
        $prev = Kurikulum::where('id', '<', $kurikulum->id)->orderBy('id', 'desc')->first();
        $next = Kurikulum::where('id', '>', $kurikulum->id)->orderBy('id')->first();

        return view('detail-kurikulum', [
            'kurikulum' => $kurikulum,
            'prevKurikulum' => $prev,
            'nextKurikulum' => $next,
        ]);
    }

    public function matrixHtml(Kurikulum $kurikulum)
    {
        $html = $kurikulum->matrix_html ?? '';

        return response($html, 200)
            ->header('Content-Type', 'text/html; charset=utf-8');
    }
}
