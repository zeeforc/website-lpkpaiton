<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validasi input
        $data = $request->validate([
            'name'    => ['required', 'string', 'max:255'],
            'email'   => ['required', 'email', 'max:255'],
            'message' => ['required', 'string'],
        ]);

        // 2. Simpan ke database
        Contact::create($data);

        // 3. Redirect balik dengan pesan sukses
        return back()->with('success', 'Pesan Anda sudah terkirim. Terima kasih.');
    }
}
