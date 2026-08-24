<?php

namespace App\Http\Controllers;

use App\Models\Galery;
use Illuminate\Support\Facades\Cache;

class GaleryController extends Controller
{
    public function galeri()
    {
        // Gunakan key cache yang SAMA PERSIS dengan yang ada di Model ('galeries-image').
        // Kita pakai rememberForever karena Model otomatis menghapusnya saat ada perubahan data.
        $images = Cache::rememberForever('galeries-image', function () {

            return Galery::select('galery_image')
                ->latest()
                ->get()
                // Karena di Model di-cast sebagai 'array', isinya bentuknya seperti:
                // [ ['img1.jpg', 'img2.jpg'], ['img3.jpg'] ]
                ->pluck('galery_image')
                // Collapse meleburkan array multi-dimensi di atas menjadi 1 dimensi:
                // ['img1.jpg', 'img2.jpg', 'img3.jpg']
                ->collapse()
                ->filter() // Memastikan tidak ada data null/kosong
                ->values() // Re-index array (0, 1, 2, dst)
                ->all();   // Ubah jadi array PHP biasa agar ringan dilempar ke View

        });
        return view('galeri', compact('images'));
    }
}
