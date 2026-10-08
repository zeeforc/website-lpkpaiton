<?php

namespace App\Services;

use App\Models\Application;
use App\Models\PklLetterTemplate;
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PklLetterGenerator
{
    public function generateForApplication(Application $application)
    {
        // Temukan template aktif
        $templateBalasan = PklLetterTemplate::where('type', 'surat_balasan')->where('is_active', true)->first();
        $templatePerjanjian = PklLetterTemplate::where('type', 'surat_perjanjian')->where('is_active', true)->first();

        $generatedFiles = [];

        if ($templateBalasan && Storage::disk('public')->exists($templateBalasan->file_path)) {
            $generatedFiles['surat_balasan'] = $this->processTemplate($templateBalasan->file_path, $application, 'balasan');
        }

        if ($templatePerjanjian && Storage::disk('public')->exists($templatePerjanjian->file_path)) {
            $generatedFiles['surat_perjanjian'] = $this->processTemplate($templatePerjanjian->file_path, $application, 'perjanjian');
        }

        return $generatedFiles;
    }

    private function processTemplate($templatePath, Application $application, $prefix)
    {
        $fullTemplatePath = Storage::disk('public')->path($templatePath);
        $templateProcessor = new TemplateProcessor($fullTemplatePath);

        // Siapkan variabel yang akan direplace
        $templateProcessor->setValue('NAMA', $application->nama_lengkap ?? '-');
        $templateProcessor->setValue('ASAL_SEKOLAH', $application->instansi ?? '-');
        $templateProcessor->setValue('NISN', '-'); // Belum ada di form
        $templateProcessor->setValue('PROGRAM_KEAHLIAN', $application->jurusan ?? '-');
        
        $tanggalMulai = $application->start_date ? \Carbon\Carbon::parse($application->start_date)->translatedFormat('d F Y') : '-';
        $tanggalSelesai = $application->end_date ? \Carbon\Carbon::parse($application->end_date)->translatedFormat('d F Y') : '-';
        
        $templateProcessor->setValue('TANGGAL_MASUK', $tanggalMulai);
        $templateProcessor->setValue('TANGGAL_KELUAR', $tanggalSelesai);

        // Tambahan variabel untuk perjanjian
        $templateProcessor->setValue('DURASI', $application->lama_durasi_bulan ? $application->lama_durasi_bulan . ' bulan' : '3 bulan');

        $persetujuanWali = ' dan dengan persetujuan orang tua/wali'; // default
        if ($application->user && $application->user->studentProfile && $application->user->studentProfile->tanggal_lahir) {
            $age = \Carbon\Carbon::parse($application->user->studentProfile->tanggal_lahir)->age;
            if ($age >= 18) {
                $persetujuanWali = ''; // Dihapus jika usia >= 18 tahun
            }
        }
        $templateProcessor->setValue('PERSETUJUAN_WALI', $persetujuanWali);

        // Buat folder jika belum ada
        $directory = 'application_documents/generated/' . $application->id;
        if (!Storage::disk('public')->exists($directory)) {
            Storage::disk('public')->makeDirectory($directory);
        }

        // Nama file output
        $filename = $directory . '/' . $prefix . '_' . Str::slug($application->nama_lengkap) . '_' . time() . '.docx';
        $fullOutputPath = Storage::disk('public')->path($filename);

        // Simpan file docx hasil generate
        $templateProcessor->saveAs($fullOutputPath);

        return $filename;
    }
}
