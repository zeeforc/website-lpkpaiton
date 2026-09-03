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
    Route::post('/logout', [\App\Http\Controllers\PortalController::class, 'logout'])->name('logout');

    Route::middleware('auth')->group(function () {
        Route::get('/biodata', [\App\Http\Controllers\PortalController::class, 'biodata'])->name('biodata');
        Route::post('/biodata', [\App\Http\Controllers\PortalController::class, 'updateBiodata'])->name('biodata.update');
        
        Route::get('/informasi', [\App\Http\Controllers\PortalController::class, 'informasi'])->name('informasi');
        Route::get('/absensi', [\App\Http\Controllers\PortalController::class, 'absensi'])->name('absensi');
        Route::get('/absensi/check-in', [\App\Http\Controllers\PortalController::class, 'checkIn'])->name('absensi.check-in');
        Route::post('/absensi/check-in', [\App\Http\Controllers\PortalController::class, 'storeAbsensi'])->name('absensi.store');
        Route::get('/absensi/export', [\App\Http\Controllers\PortalController::class, 'exportAbsensi'])->name('absensi.export');
        
        Route::get('/face-registration', [\App\Http\Controllers\PortalController::class, 'faceRegistration'])->name('face-registration');
        Route::post('/face-registration', [\App\Http\Controllers\PortalController::class, 'storeFaceDescriptor'])->name('face-registration.store');
        
        Route::get('/guru/absensi-rombongan', [\App\Http\Controllers\PortalController::class, 'absensiRombongan'])->name('guru.absensi-rombongan');
        Route::post('/guru/absensi-rombongan', [\App\Http\Controllers\PortalController::class, 'storeAbsensiRombongan'])->name('guru.absensi-rombongan.store');
        
        Route::get('/laporan', [\App\Http\Controllers\PortalController::class, 'laporan'])->name('laporan');
        Route::post('/laporan', [\App\Http\Controllers\PortalController::class, 'storeLaporan'])->name('laporan.store');
    });
});

Route::get('/amsadmin/export-attendances', function () {
    $attendances = \App\Models\Attendance::with('user')->get();
    
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

    $fileName = 'Laporan_Absensi_' . date('Y-m-d') . '.xlsx';
    
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $fileName . '"');
    header('Cache-Control: max-age=0');
    
    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
})->name('admin.attendances.export');