<?php

namespace App\Http\Controllers;

use App\Models\Kurikulum;

class KurikulumController extends Controller
{
    public function index()
    {
        $kurikulums = Kurikulum::orderBy('id')->get();
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
}
