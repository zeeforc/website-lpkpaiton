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
        $templateProcessor->setValue('ASAL_SEKOLAH', $application->asal_sekolah ?? '-');
        $templateProcessor->setValue('NISN', $application->nisn ?? '-');
        $templateProcessor->setValue('PROGRAM_KEAHLIAN', $application->program_keahlian ?? '-');
        
        $tanggalMulai = $application->tanggal_mulai ? \Carbon\Carbon::parse($application->tanggal_mulai)->translatedFormat('d F Y') : '-';
        $tanggalSelesai = $application->tanggal_selesai ? \Carbon\Carbon::parse($application->tanggal_selesai)->translatedFormat('d F Y') : '-';
        
        $templateProcessor->setValue('TANGGAL_MASUK', $tanggalMulai);
        $templateProcessor->setValue('TANGGAL_KELUAR', $tanggalSelesai);

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
