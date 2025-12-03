<?php

namespace App\Http\Controllers;

use App\Models\Galery;

class GaleryController extends Controller
{
    public function galeri(){
        $galeries = Galery::all();

        return view('galeri', compact('galeries'));
    }
}
