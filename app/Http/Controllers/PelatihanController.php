<?php

namespace App\Http\Controllers;

use App\Models\Pelatihan;
use Illuminate\Support\Facades\Cache;

class PelatihanController extends Controller
{
    public function index()
    {
        $pelatihans = Cache::remember('pelatihans_all', 3600, function () {
            return Pelatihan::all();
        });

        return view('pelatihan', compact('pelatihans'));
    }
}
