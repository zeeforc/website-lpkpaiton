<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\ApplicationDocument;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ApplicationController extends Controller
{
    public function create()
    {
        return view('application-form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'instansi' => 'required|string|max:255',
            'tingkat_pendidikan' => 'required|string|in:Mahasiswa,Siswa SMK/SMA',
            'jurusan' => 'required|string|max:255',
            'no_hp' => 'required|string|max:50',
            'pengajuan' => 'required|string',
            'periode_gelombang' => 'required|string',
            'jumlah_peserta' => 'required|string',
            'lama_durasi_bulan' => 'required|integer|min:1',
            'fokus_studi' => 'required|string',
            'email_balasan' => 'required|email|max:255',
        ]);

        try {
            DB::beginTransaction();

            $user = User::firstOrCreate(
                ['email' => $request->email_balasan],
                [
                    'name' => $request->nama_lengkap,
                    'password' => Hash::make(Str::random(16)),
                ]
            );

            $application = Application::create([
                'user_id' => $user->id,
                'nama_lengkap' => $request->nama_lengkap,
                'instansi' => $request->instansi,
                'tingkat_pendidikan' => $request->tingkat_pendidikan,
                'jurusan' => $request->jurusan,
                'no_hp' => $request->no_hp,
                'pengajuan' => $request->pengajuan,
                'periode_gelombang' => $request->periode_gelombang,
                'jumlah_peserta' => $request->jumlah_peserta,
                'lama_durasi_bulan' => $request->lama_durasi_bulan,
                'fokus_studi' => $request->fokus_studi,
                'email_balasan' => $request->email_balasan,
                'status' => 'pending',
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Formulir pendaftaran berhasil dikirim. Kami akan memprosesnya dan memberikan balasan melalui email.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan saat memproses formulir. Silakan coba lagi.'])->withInput();
        }
    }

    public function showUploadForm(Request $request, Application $application)
    {
        if (! $request->hasValidSignature()) {
            abort(401, 'Link tidak valid atau sudah kadaluarsa.');
        }

        if (! in_array($application->status, ['permohonan_diterima', 'accepted'])) {
            return redirect('/cek-status')->with('error', 'Status aplikasi tidak mengizinkan unggah dokumen saat ini.');
        }

        return view('upload-dokumen', compact('application'));
    }

    public function uploadDocuments(Request $request, Application $application)
    {
        if (! $request->hasValidSignature()) {
            abort(401, 'Link tidak valid atau sudah kadaluarsa.');
        }

        if (! in_array($application->status, ['permohonan_diterima', 'accepted'])) {
            return redirect('/cek-status')->with('error', 'Status aplikasi tidak mengizinkan unggah dokumen saat ini.');
        }

        $request->validate([
            'dokumen_ktp' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'dokumen_foto' => 'required|file|mimes:jpg,jpeg,png|max:5120',
            'dokumen_skck' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'dokumen_sehat' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        try {
            DB::beginTransaction();

            $filesToUpload = [
                'dokumen_ktp' => 'KTP/Kartu Pelajar',
                'dokumen_foto' => 'Pas Foto 4x6',
                'dokumen_skck' => 'SKCK',
                'dokumen_sehat' => 'Surat Sehat',
            ];

            foreach ($filesToUpload as $inputName => $documentType) {
                if ($request->hasFile($inputName)) {
                    $file = $request->file($inputName);
                    $path = $file->store('documents', 'public');
                    
                    ApplicationDocument::create([
                        'application_id' => $application->id,
                        'file_path' => $path,
                        'original_name' => $documentType . ' - ' . $file->getClientOriginalName(),
                    ]);
                }
            }

            // Update status
            $application->update(['status' => 'document_review']);

            DB::commit();

            return redirect('/cek-status')->with('success', 'Dokumen berhasil diunggah. Silakan cek status pendaftaran secara berkala.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan saat mengunggah dokumen. Silakan coba lagi.']);
        }
    }

    public function cekStatus()
    {
        return view('cek-status');
    }

    public function checkStatus(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        
        $application = Application::with('notes')->where('email_balasan', $request->email)->latest()->first();
        
        if (!$application) {
            return redirect()->back()->with('error', 'Data pendaftaran dengan email tersebut tidak ditemukan.')->withInput();
        }
        
        return view('cek-status', compact('application'));
    }
}
