<?php

namespace App\Filament\Amsadmin\Pages;

use Filament\Pages\Page;
use App\Models\Application;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use BackedEnum;

class Laporan extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Laporan Excel';
    protected static ?string $title = 'Laporan Pendaftaran PKL';
    protected static ?int $navigationSort = 3;

    protected string $view = 'filament.amsadmin.pages.laporan';

    public function downloadExcel()
    {
        $applications = Application::whereIn('status', ['accepted', 'permohonan_diterima'])->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Laporan PKL');

        // Header
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Nama Lengkap');
        $sheet->setCellValue('C1', 'Jumlah');
        $sheet->setCellValue('D1', 'Keahlian/Jurusan');
        $sheet->setCellValue('E1', 'Asal Sekolah/Kampus');
        $sheet->setCellValue('F1', 'Periode Masa PKL');

        // Style Header
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
            'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF4F46E5'] // Indigo
            ],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
        ];
        $sheet->getStyle('A1:F1')->applyFromArray($headerStyle);

        $row = 2;
        $totalPeserta = 0;
        foreach ($applications as $index => $app) {
            $jumlah = (int) $app->jumlah_peserta;
            $totalPeserta += $jumlah;

            $periode = $app->periode_gelombang;
            $durasi = (int) $app->lama_durasi_bulan;
            
            if (strpos($periode, ' : ') !== false) {
                list($gelombang, $tanggalStr) = explode(' : ', $periode);
                
                $bulanIndo = ['JANUARI', 'FEBRUARI', 'MARET', 'APRIL', 'MEI', 'JUNI', 'JULI', 'AGUSTUS', 'SEPTEMBER', 'OKTOBER', 'NOVEMBER', 'DESEMBER'];
                $bulanEng = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                
                $tanggalStrEng = str_ireplace($bulanIndo, $bulanEng, $tanggalStr);
                
                try {
                    $startDate = \Carbon\Carbon::parse($tanggalStrEng);
                    $endDate = $startDate->copy()->addMonths($durasi);
                    
                    $startDateIndo = str_ireplace($bulanEng, $bulanIndo, strtoupper($startDate->format('j F Y')));
                    $endDateIndo = str_ireplace($bulanEng, $bulanIndo, strtoupper($endDate->format('j F Y')));
                    
                    $periode = $gelombang . ' : ' . $startDateIndo . ' s/d ' . $endDateIndo;
                } catch (\Exception $e) {
                    // Fall back to string if parse fails
                }
            }

            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $app->nama_lengkap);
            $sheet->setCellValue('C' . $row, $jumlah);
            $sheet->setCellValue('D' . $row, $app->jurusan);
            $sheet->setCellValue('E' . $row, $app->instansi);
            $sheet->setCellValue('F' . $row, $periode);
            
            // Borders
            $sheet->getStyle("A{$row}:F{$row}")->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            
            // Center align column A and C
            $sheet->getStyle("A{$row}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("C{$row}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

            $row++;
        }

        $uniqueSchools = $applications->pluck('instansi')->map(function ($item) { return strtolower(trim($item)); })->unique()->count();
        $uniqueMajors = $applications->pluck('jurusan')->map(function ($item) { return strtolower(trim($item)); })->unique()->count();

        // Summary row
        $sheet->setCellValue('A' . $row, 'TOTAL KESELURUHAN PESERTA PKL');
        $sheet->mergeCells("A{$row}:B{$row}");
        $sheet->setCellValue('C' . $row, $totalPeserta);
        $sheet->setCellValue('D' . $row, $uniqueMajors . ' Jurusan Berbeda');
        $sheet->setCellValue('E' . $row, $uniqueSchools . ' Sekolah/Kampus');
        
        $summaryStyle = [
            'font' => ['bold' => true],
            'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFFDE047']], // Yellow
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
        ];
        $sheet->getStyle("A{$row}:F{$row}")->applyFromArray($summaryStyle);
        // Right align text for "TOTAL KESELURUHAN"
        $sheet->getStyle("A{$row}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

        // Auto size columns
        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        
        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, 'Laporan_Pendaftar_PKL.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
}
