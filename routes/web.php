<?php

use App\Http\Controllers\BeritaUtamaController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\GaleryController;
use App\Http\Controllers\KurikulumController;
use App\Http\Controllers\PelatihanController;
use App\Http\Controllers\SaranaController;
use App\Models\DokumenSyaratPkl;
use App\Models\Galery;
use App\Models\Home;
use App\Models\Team;
use App\Models\Vimi;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $home = cache()->remember('home_hero', now()->addMinutes(5), function () {
        return Home::select('id', 'title', 'bg_image')
            ->latest('id')
            ->first();
    });

    $vimi = cache()->remember('vision_mission', now()->addMinutes(5), function () {
        return Vimi::select('visi_title', 'visi_text', 'misi_title', 'misi_text')
            ->latest('id')
            ->first();
    });

    $teams = cache()->remember('home_teams', now()->addMinutes(5), function () {
        return Team::select('id', 'name', 'position', 'photo')
            ->orderBy('id')
            ->get();
    });

    return view('index', compact('home', 'vimi', 'teams'));
})->name('home');

Route::get('/index', function () {
    return redirect()->route('home');
});

Route::post('/kontak', [ContactController::class, 'store'])
    ->name('contact.store');

Route::get('/kurikulum', [KurikulumController::class, 'index'])->name('kurikulum.index');
Route::get('/kurikulum/{kurikulum}', [KurikulumController::class, 'show'])->name('kurikulum.show');
Route::get('/kurikulum/{kurikulum}/matrix-html', [KurikulumController::class, 'matrixHtml'])->name('kurikulum.matrix-html');

Route::get('/pelatihan', [PelatihanController::class, 'index'])->name('pelatihan.index');

Route::get('/sarana', [SaranaController::class, 'detail'])
    ->name('sarana');

Route::get('/berita', [BeritaUtamaController::class, 'index'])
    ->name('berita.index');

Route::get('/berita/{beritaUtama:slug}', [BeritaUtamaController::class, 'show'])
    ->name('berita.show');

Route::get('/galeri', [GaleryController::class, 'galeri'])->name('galeri.index');

Route::get('/syarat', function () {
    $dokumen = cache()->remember('syarat_pkl_doc', now()->addHours(6), function () {
        return DokumenSyaratPkl::latest('id')->first();
    });

    return view('syarat', compact('dokumen'));
})->name('syarat');

Route::get('/pendaftaran', [\App\Http\Controllers\ApplicationController::class, 'create'])->name('application.create');
Route::post('/pendaftaran', [\App\Http\Controllers\ApplicationController::class, 'store'])->name('application.store');

Route::get('/cek-status', [\App\Http\Controllers\ApplicationController::class, 'cekStatus'])->name('application.cekStatus');
Route::post('/cek-status', [\App\Http\Controllers\ApplicationController::class, 'checkStatus'])->name('application.check');

Route::get('/pendaftaran/{application}/upload', [\App\Http\Controllers\ApplicationController::class, 'showUploadForm'])->name('application.upload')->middleware('signed');
Route::post('/pendaftaran/{application}/upload', [\App\Http\Controllers\ApplicationController::class, 'uploadDocuments'])->name('application.upload.store')->middleware('signed');

// Portal Siswa PKL Routes
Route::prefix('portal')->name('portal.')->group(function () {
    Route::get('/login', [\App\Http\Controllers\PortalController::class, 'showLogin'])->name('login');
    Route::post('/login', [\App\Http\Controllers\PortalController::class, 'login'])->name('login.post');
    Route::get('/register-karyawan', [\App\Http\Controllers\PortalController::class, 'showRegisterKaryawan'])->name('register-karyawan');
    Route::post('/register-karyawan', [\App\Http\Controllers\PortalController::class, 'registerKaryawan'])->name('register-karyawan.post');
    Route::post('/logout', [\App\Http\Controllers\PortalController::class, 'logout'])->name('logout');
    Route::get('/test-419', function () {
        throw new \Illuminate\Session\TokenMismatchException;
    });

    Route::middleware('auth')->group(function () {
        Route::get('/biodata', [\App\Http\Controllers\PortalController::class, 'biodata'])->name('biodata');
        Route::post('/biodata', [\App\Http\Controllers\PortalController::class, 'updateBiodata'])->name('biodata.update');
        
        Route::get('/informasi', [\App\Http\Controllers\PortalController::class, 'informasi'])->name('informasi');
        Route::get('/absensi', [\App\Http\Controllers\PortalController::class, 'absensi'])->name('absensi');
        Route::get('/absensi/check-in', [\App\Http\Controllers\PortalController::class, 'checkIn'])->name('absensi.check-in');
        Route::post('/absensi/check-in', [\App\Http\Controllers\PortalController::class, 'storeAbsensi'])->name('absensi.store');
        Route::get('/absensi/export', [\App\Http\Controllers\PortalController::class, 'exportAbsensi'])->name('absensi.export');
        
        Route::get('/izin', [\App\Http\Controllers\PortalController::class, 'izin'])->name('izin');
        Route::post('/izin', [\App\Http\Controllers\PortalController::class, 'storeIzin'])->name('izin.store');
        
        Route::get('/face-registration', [\App\Http\Controllers\PortalController::class, 'faceRegistration'])->name('face-registration');
        Route::post('/face-registration', [\App\Http\Controllers\PortalController::class, 'storeFaceDescriptor'])->name('face-registration.store');
        
        Route::get('/guru/absensi-rombongan', [\App\Http\Controllers\PortalController::class, 'absensiRombongan'])->name('guru.absensi-rombongan');
        Route::post('/guru/absensi-rombongan', [\App\Http\Controllers\PortalController::class, 'storeAbsensiRombongan'])->name('guru.absensi-rombongan.store');
        
        Route::get('/laporan', [\App\Http\Controllers\PortalController::class, 'laporan'])->name('laporan');
        Route::post('/laporan', [\App\Http\Controllers\PortalController::class, 'storeLaporan'])->name('laporan.store');
    });
});

Route::get('/amsadmin/export-attendances', function () {
    $attendances = \App\Models\Attendance::with('user')->whereHas('user', function ($query) {
        $query->where('role', '!=', 'karyawan_paving');
    })->get();
    
    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    
    // Set Header
    $sheet->setCellValue('A1', 'ID');
    $sheet->setCellValue('B1', 'Nama Siswa');
    $sheet->setCellValue('C1', 'Tanggal');
    $sheet->setCellValue('D1', 'Status');
    $sheet->setCellValue('E1', 'Check In');
    $sheet->setCellValue('F1', 'Check Out');
    $sheet->setCellValue('G1', 'Catatan');

    // Make Header Bold
    $sheet->getStyle('A1:G1')->getFont()->setBold(true);
    // Add border to Header
    $sheet->getStyle('A1:G1')->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

    $row = 2;
    foreach ($attendances as $attendance) {
        $sheet->setCellValue('A' . $row, $attendance->id);
        $sheet->setCellValue('B' . $row, $attendance->user ? $attendance->user->name : '-');
        $sheet->setCellValue('C' . $row, $attendance->date);
        $sheet->setCellValue('D' . $row, $attendance->status);
        $sheet->setCellValue('E' . $row, $attendance->check_in);
        $sheet->setCellValue('F' . $row, $attendance->check_out);
        $sheet->setCellValue('G' . $row, $attendance->notes);
        $row++;
    }

    // Auto size columns
    foreach (range('A', 'G') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    $fileName = 'Laporan_Absensi_Siswa_' . date('Y-m-d') . '.xlsx';
    
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $fileName . '"');
    header('Cache-Control: max-age=0');
    
    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
})->name('admin.attendances.export');

Route::get('/amsadmin/export-paving-attendances', function (\Illuminate\Http\Request $request) {
    $currentMonth = date('m');
    $currentYear = date('Y');
    $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $currentMonth, $currentYear);
    $monthNames = [1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    $monthName = $monthNames[(int)$currentMonth];
    
    $holidays = \App\Models\Holiday::whereMonth('date', $currentMonth)
        ->whereYear('date', $currentYear)
        ->get()
        ->keyBy(function($item) {
            return (int) \Carbon\Carbon::parse($item->date)->format('j');
        });
        
    $roleFilter = $request->input('role', 'karyawan_paving');
    
    if ($roleFilter === 'semua') {
        $users = \App\Models\User::whereIn('role', ['karyawan_paving', 'instruktur_lpk'])->get();
    } else {
        $users = \App\Models\User::where('role', $roleFilter)->get();
    }
    
    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $spreadsheet->removeSheetByIndex(0); // Remove default sheet
    
    foreach ($users as $index => $user) {
        // Excel sheet names max 31 characters
        $sheetName = substr(str_replace(['*', ':', '/', '\\', '?', '[', ']'], '', $user->name), 0, 31);
        $sheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, $sheetName);
        $spreadsheet->addSheet($sheet, $index);
        
        // Fetch attendances
        $attendances = \App\Models\Attendance::where('user_id', $user->id)
            ->whereMonth('date', $currentMonth)
            ->whereYear('date', $currentYear)
            ->get()
            ->keyBy(function($item) {
                return (int) \Carbon\Carbon::parse($item->date)->format('j');
            });
            
        // Header info
        $sheet->mergeCells('A1:F1');
        $sheet->getRowDimension(1)->setRowHeight(60);
        if (file_exists(public_path('images/paving_header.png'))) {
            $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
            $drawing->setName('Logo');
            $drawing->setDescription('Logo');
            $drawing->setPath(public_path('images/paving_header.png'));
            $drawing->setCoordinates('A1');
            $drawing->setOffsetY(5);
            $drawing->setOffsetX(5);
            $drawing->setHeight(70);
            $drawing->setWorksheet($sheet);
        } else {
            $sheet->setCellValue('A1', 'LEMBAGA PELATIHAN KERJA PAITON SELARAS');
            $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
            $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');
        }
        
        $sheet->mergeCells('A2:B2');
        $sheet->setCellValue('A2', 'EMPLOYEE DAILY TIME SHEET');
        $sheet->setCellValue('C2', 'NAME');
        $sheet->mergeCells('D2:F2');
        $sheet->setCellValue('D2', $user->name);
        
        $sheet->mergeCells('A3:B3');
        $sheet->setCellValue('A3', "01-{$daysInMonth} {$monthName} {$currentYear}");
        $sheet->setCellValue('C3', 'JOB TITLE');
        $sheet->mergeCells('D3:F3');
        $jobTitle = $user->role === 'instruktur_lpk' ? 'Instruktur LPK' : 'Karyawan Paving';
        $sheet->setCellValue('D3', $jobTitle);
        
        // Table Headers
        $sheet->mergeCells('A4:A5');
        $sheet->setCellValue('A4', 'DATE');
        
        $sheet->mergeCells('B4:D4');
        $sheet->setCellValue('B4', 'TIME RECORD');
        $sheet->setCellValue('B5', 'START');
        $sheet->setCellValue('C5', 'FINISH');
        $sheet->setCellValue('D5', 'HOURS');
        
        $sheet->mergeCells('E4:E5');
        $sheet->setCellValue('E4', 'DESCRIPTION');
        
        $sheet->mergeCells('F4:F5');
        $sheet->setCellValue('F4', 'ACQUISITION');
        
        // Styling headers
        $headerStyle = [
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
            'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]],
        ];
        $sheet->getStyle('A2:F5')->applyFromArray($headerStyle);
        
        // Data Rows
        $row = 6;
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $currentDateStr = "{$currentYear}-{$currentMonth}-" . str_pad($day, 2, '0', STR_PAD_LEFT);
            $isWeekend = \Carbon\Carbon::parse($currentDateStr)->isWeekend();
            $holiday = $holidays->get($day);
            
            $sheet->setCellValue('A' . $row, $day);
            $sheet->getStyle('A'.$row)->getAlignment()->setHorizontal('center');
            
            $attendance = $attendances->get($day);
            
            if ($holiday) {
                // Red background for holiday
                $sheet->getStyle("A{$row}:F{$row}")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                      ->getStartColor()->setARGB('FFFFCCCC'); // Light red
                $sheet->setCellValue('E' . $row, 'Libur Nasional: ' . $holiday->name);
                $sheet->getStyle("E{$row}")->getFont()->setItalic(true)->getColor()->setARGB('FFCC0000'); // Red text
            } elseif ($isWeekend) {
                // Blue background for weekend
                $sheet->getStyle("A{$row}:F{$row}")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                      ->getStartColor()->setARGB('FFB4C6E7'); // Light blue
            } else {
                if ($attendance && $attendance->status === 'Hadir') {
                    $sheet->setCellValue('B' . $row, '07:00');
                    $sheet->setCellValue('C' . $row, '16:00');
                    $sheet->setCellValue('D' . $row, '8'); // HOURS
                    
                    // Center align B, C, D
                    $sheet->getStyle("B{$row}:D{$row}")->getAlignment()->setHorizontal('center');
                }
                
                if ($attendance) {
                    $sheet->setCellValue('E' . $row, $attendance->work_description ?? '');
                }
            }
            
            // Borders
            $sheet->getStyle("A{$row}:F{$row}")->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            
            $row++;
        }
        
        // Signatures
        $signRowStart = $row;
        $sheet->setCellValue('A' . $row, 'Prepared by,');
        $sheet->setCellValue('B' . $row, 'Approved by,');
        $sheet->mergeCells("C{$row}:F{$row}");
        $sheet->setCellValue('C' . $row, 'Confirmed & Acknowledged by,');
        
        $row += 4;
        $sheet->setCellValue('A' . $row, $user->name);
        $sheet->getStyle('A' . $row)->getFont()->setUnderline(true)->setItalic(true);
        $sheet->setCellValue('B' . $row, 'User');
        $sheet->getStyle('B' . $row)->getFont()->setUnderline(true)->setItalic(true);
        $sheet->mergeCells("C{$row}:F{$row}");
        $sheet->setCellValue('C' . $row, '........................................................');
        $sheet->getStyle("C{$row}")->getAlignment()->setHorizontal('center');
        
        $row += 1;
        $sheet->setCellValue('A' . $row, 'Employee');
        $sheet->getStyle('A' . $row)->getFont()->setItalic(true);
        
        $signRowEnd = $row + 1; // Tambah satu baris kosong di bawah nama agar lebih mirip
        
        $sheet->getStyle("A{$signRowStart}:A{$signRowEnd}")->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $sheet->getStyle("B{$signRowStart}:B{$signRowEnd}")->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $sheet->getStyle("C{$signRowStart}:F{$signRowEnd}")->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        
        // Auto size columns
        $sheet->getColumnDimension('A')->setWidth(10);
        $sheet->getColumnDimension('B')->setWidth(15);
        $sheet->getColumnDimension('C')->setWidth(15);
        $sheet->getColumnDimension('D')->setWidth(15);
        $sheet->getColumnDimension('E')->setWidth(40);
        $sheet->getColumnDimension('F')->setWidth(20);
    }
    
    // If no users
    if ($users->isEmpty()) {
        $sheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'No Data');
        $spreadsheet->addSheet($sheet, 0);
        $sheet->setCellValue('A1', 'Tidak ada data karyawan.');
    }
    
    $spreadsheet->setActiveSheetIndex(0);
    
    $roleStr = $roleFilter === 'semua' ? 'Semua_Karyawan' : ($roleFilter === 'instruktur_lpk' ? 'Instruktur_LPK' : 'Karyawan_Paving');
    $fileName = 'Laporan_Absensi_' . $roleStr . '_' . $monthNames[(int)$currentMonth] . '_' . $currentYear . '.xlsx';
    
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $fileName . '"');
    header('Cache-Control: max-age=0');
    
    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
})->name('admin.paving-attendances.export');