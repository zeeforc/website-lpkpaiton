<?php

namespace App\Http\Controllers;

use App\Models\Home as ModelsHome;

class Home extends Controller
{
    public function index()
    {
        // Cache 5 menit sebagai contoh, bisa kamu sesuaikan
        $homes = cache()->remember('homes', now()->addMinutes(5), function () {
            return ModelsHome::published()
                ->orderByDesc('published_at')
                ->select('id', 'title', 'body', 'image_path', 'published_at')
                ->limit(6)       // jangan ambil semua, cukup beberapa untuk homepage
                ->get();
        });

        return view('index', compact('posts'));
    }
}
