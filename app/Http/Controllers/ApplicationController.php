<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\ApplicationDocument;
use App\Models\Setting;
use App\Models\StudentProfile;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ApplicationController extends Controller
{
    public function create()
    {
        $activeQuota = Application::getActiveQuotaCount();
        $isFull = $activeQuota >= 35;
        $predictedOpenMonth = Application::getPredictedOpenMonth();

        return view('application-form', compact('isFull', 'predictedOpenMonth'));
    }

    public function store(Request $request)
    {
        if (Application::getActiveQuotaCount() >= 35) {
            return redirect()->back()->withErrors(['error' => 'Pendaftaran ditutup karena kuota penuh.']);
        }

        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'instansi' => 'required|string|max:255',
            'tingkat_pendidikan' => 'required|string|in:Mahasiswa,Siswa SMK/SMA',
            'nomor_induk' => ['required', 'string', 'max:50', 'regex:/^[0-9]+$/'],
            'jurusan' => 'required|string|max:255',
            'no_hp' => 'required|string|max:50',
            'pengajuan' => 'required|string',
            'periode_gelombang' => 'required|string',
            'lama_durasi_bulan' => 'required|integer|min:2|max:6',
            'fokus_studi' => 'required|string',
            'email_balasan' => 'required|email|max:255',
            'documents' => 'required|array',
            'documents.*' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ], [
            'documents.*.mimes' => 'Format file dokumen harus berupa PDF, JPG, atau PNG.',
            'documents.*.max' => 'Ukuran file dokumen maksimal adalah 5MB per file.',
            'nomor_induk.required' => 'NISN atau NIM wajib diisi.',
            'nomor_induk.regex' => 'NISN atau NIM hanya boleh berisi angka.',
        ]);

        if ($this->hasDuplicateApplicant($request->nama_lengkap, $request->nomor_induk)) {
            return redirect()->back()->withInput()->with('duplicate_registration', true);
        }

        $request->validate(['email_balasan' => 'unique:users,email'], [
            'email_balasan.unique' => 'Email ini sudah pernah digunakan untuk mendaftar. Silakan gunakan email lain.',
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
                'nomor_induk' => $request->nomor_induk,
                'jurusan' => $request->jurusan,
                'no_hp' => $request->no_hp,
                'pengajuan' => $request->pengajuan,
                'periode_gelombang' => $request->periode_gelombang,
                'jumlah_peserta' => '1 ORANG',
                'lama_durasi_bulan' => $request->lama_durasi_bulan,
                'fokus_studi' => $request->fokus_studi,
                'email_balasan' => $request->email_balasan,
                'status' => 'pending',
            ]);

            StudentProfile::updateOrCreate(
                ['user_id' => $user->id],
                $request->tingkat_pendidikan === 'Mahasiswa'
                    ? ['nim' => $request->nomor_induk]
                    : ['nisn' => $request->nomor_induk],
            );

            if ($request->hasFile('documents')) {
                foreach ($request->file('documents') as $file) {
                    $path = $file->store('applications/documents', 'public');
                    ApplicationDocument::create([
                        'application_id' => $application->id,
                        'file_path' => $path,
                        'original_name' => $file->getClientOriginalName(),
                    ]);
                }
            }

            DB::commit();

            return redirect()->back()->with('success', 'Formulir pendaftaran berhasil dikirim. Kode referensi Anda: '.$application->reference_code.'. Simpan kode ini untuk mengecek status pendaftaran.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan saat memproses formulir. Silakan coba lagi.'])->withInput();
        }
    }

    private function hasDuplicateApplicant(string $name, string $identityNumber): bool
    {
        $matchingApplication = Application::query()
            ->whereRaw('HEX(LOWER(TRIM(nama_lengkap))) = HEX(LOWER(TRIM(?)))', [$name])
            ->where(function (Builder $query) use ($identityNumber): void {
                $query
                    ->whereRaw('HEX(TRIM(nomor_induk)) = HEX(?)', [$identityNumber])
                    ->orWhereHas('user.studentProfile', function (Builder $profile) use ($identityNumber): void {
                        $profile
                            ->whereRaw('HEX(TRIM(nisn)) = HEX(?)', [$identityNumber])
                            ->orWhereRaw('HEX(TRIM(nim)) = HEX(?)', [$identityNumber]);
                    });
            })
            ->exists();

        if ($matchingApplication) {
            return true;
        }

        return User::query()
            ->whereRaw('HEX(LOWER(TRIM(name))) = HEX(LOWER(TRIM(?)))', [$name])
            ->whereHas('studentProfile', function (Builder $profile) use ($identityNumber): void {
                $profile
                    ->whereRaw('HEX(TRIM(nisn)) = HEX(?)', [$identityNumber])
                    ->orWhereRaw('HEX(TRIM(nim)) = HEX(?)', [$identityNumber]);
            })
            ->exists();
    }

    public function showUploadForm(Request $request, Application $application)
    {
        if (! $request->hasValidSignature()) {
            abort(401, 'Link tidak valid atau sudah kadaluarsa.');
        }

        if (! in_array($application->status, ['permohonan_diterima', 'accepted', 'revisi_dokumen'])) {
            return redirect('/cek-status')->with('error', 'Status aplikasi tidak mengizinkan unggah dokumen saat ini.');
        }

        return view('upload-dokumen', compact('application'));
    }

    public function uploadDocuments(Request $request, Application $application)
    {
        if (! $request->hasValidSignature()) {
            abort(401, 'Link tidak valid atau sudah kadaluarsa.');
        }

        if (! in_array($application->status, ['permohonan_diterima', 'accepted', 'revisi_dokumen'])) {
            return redirect('/cek-status')->with('error', 'Status aplikasi tidak mengizinkan unggah dokumen saat ini.');
        }

        $request->validate([
            'dokumen_ktp' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'dokumen_foto' => 'required|file|mimes:jpg,jpeg,png|max:2048',
            'dokumen_skck' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'dokumen_sehat' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'dokumen_portofolio' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'dokumen_perjanjian_pkl' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'dokumen_lainnya' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ], [
            'dokumen_ktp.max' => 'Ukuran fotocopy KTP maksimal 2MB.',
            'dokumen_foto.max' => 'Ukuran pas foto maksimal 2MB.',
            'dokumen_skck.max' => 'Ukuran Surat Kelakuan Baik maksimal 2MB.',
            'dokumen_sehat.max' => 'Ukuran Surat Keterangan Sehat maksimal 2MB.',
            'dokumen_portofolio.max' => 'Ukuran Portofolio maksimal 5MB.',
            'dokumen_perjanjian_pkl.max' => 'Ukuran Surat Perjanjian PKL maksimal 5MB.',
            'dokumen_lainnya.max' => 'Ukuran BPJS Kesehatan maksimal 5MB.',
        ]);

        try {
            DB::beginTransaction();

            $filesToUpload = [
                'dokumen_ktp' => 'KTP/Kartu Pelajar',
                'dokumen_foto' => 'Pas Foto 4x6',
                'dokumen_skck' => 'SKCK',
                'dokumen_sehat' => 'Surat Sehat',
                'dokumen_portofolio' => 'Portofolio',
                'dokumen_perjanjian_pkl' => 'Surat Perjanjian PKL',
                'dokumen_lainnya' => 'Dokumen Tambahan',
            ];

            foreach ($filesToUpload as $inputName => $documentType) {
                if ($request->hasFile($inputName)) {
                    $file = $request->file($inputName);
                    $path = $file->store('documents', 'public');

                    ApplicationDocument::create([
                        'application_id' => $application->id,
                        'file_path' => $path,
                        'original_name' => $documentType.' - '.$file->getClientOriginalName(),
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
        $request->validate([
            'email' => 'required|email',
            'reference_code' => 'required|string|max:10',
        ]);

        $application = Application::with('notes')
            ->where('email_balasan', $request->email)
            ->where('reference_code', $request->reference_code)
            ->latest()
            ->first();

        if (! $application) {
            return redirect()->back()->with('error', 'Data tidak ditemukan. Pastikan email dan kode referensi sudah benar.')->withInput();
        }

        return view('cek-status', compact('application'));
    }

    public function reuploadDocumentExternal(Request $request, Application $application, ApplicationDocument $document)
    {
        if (! $request->hasValidSignature()) {
            abort(401, 'Link tidak valid atau sudah kadaluarsa.');
        }

        if ($document->application_id !== $application->id) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'dokumen_baru' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        try {
            if (Storage::disk('public')->exists($document->file_path)) {
                Storage::disk('public')->delete($document->file_path);
            }

            $file = $request->file('dokumen_baru');
            $path = $file->store('applications/documents', 'public');

            $originalNameParts = explode(' - ', $document->original_name);
            $prefix = count($originalNameParts) > 1 ? $originalNameParts[0].' - ' : '';
            $newOriginalName = $prefix.$file->getClientOriginalName();

            $document->update([
                'file_path' => $path,
                'original_name' => $newOriginalName,
                'status' => 'Menunggu Review',
                'keterangan' => null,
            ]);

            $this->moveApplicationToDocumentReview($application);

            return back()->with('success', 'Dokumen berhasil diunggah ulang.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal mengunggah ulang dokumen.']);
        }
    }

    public function uploadMissingDocumentExternal(Request $request, Application $application)
    {
        if (! $request->hasValidSignature()) {
            abort(401, 'Link tidak valid atau sudah kadaluarsa.');
        }

        $request->validate([
            'document_type' => 'required|string|in:KTP/Kartu Pelajar,Pas Foto 4x6,SKCK,Surat Sehat,Portofolio,Surat Perjanjian PKL,Dokumen Tambahan',
            'dokumen_baru' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        try {
            $file = $request->file('dokumen_baru');
            $path = $file->store('applications/documents', 'public');
            $documentType = $request->document_type;

            ApplicationDocument::create([
                'application_id' => $application->id,
                'file_path' => $path,
                'original_name' => $documentType.' - '.$file->getClientOriginalName(),
                'status' => 'Menunggu Review',
            ]);

            $this->moveApplicationToDocumentReview($application);

            return back()->with('success', 'Dokumen berhasil diunggah.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal mengunggah dokumen.']);
        }
    }

    private function moveApplicationToDocumentReview(Application $application): void
    {
        if (in_array($application->status, ['permohonan_diterima', 'revisi_dokumen'], true)) {
            $application->update(['status' => 'document_review']);
        }
    }

    public function downloadContohPortofolio()
    {
        $setting = Setting::where('key', 'contoh_portofolio')->first();
        if ($setting && $setting->value && Storage::disk('public')->exists($setting->value)) {
            return response()->download(Storage::disk('public')->path($setting->value));
        }

        // Fallback jika tidak ada di settings
        $defaultPath = public_path('contoh_portofolio.pdf');
        if (file_exists($defaultPath)) {
            return response()->download($defaultPath);
        }

        abort(404, 'File contoh portofolio tidak ditemukan.');
    }
}
