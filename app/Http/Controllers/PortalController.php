<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\StudentProfile;
use App\Models\Attendance;
use App\Models\ReportSubmission;
use App\Models\Application;
use Carbon\Carbon;

class PortalController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            if (Auth::user()->role === 'guru_pondok') {
                return redirect()->route('portal.guru.absensi-rombongan');
            }
            return redirect()->route('portal.biodata');
        }
        return view('portal.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            $user = Auth::user();
            $user->last_login_at = now();
            $user->save();

            // Ensure student profile exists
            if (!$user->studentProfile) {
                StudentProfile::create(['user_id' => $user->id]);
            }

            if ($user->role === 'guru_pondok') {
                return redirect()->route('portal.guru.absensi-rombongan');
            }
            return redirect()->route('portal.biodata');
        }

        return back()->withErrors([
            'email' => 'Email atau Password salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('portal.login');
    }

    public function biodata()
    {
        $user = Auth::user();
        if ($user->role === 'guru_pondok') {
            return redirect()->route('portal.guru.absensi-rombongan');
        }
        $profile = $user->studentProfile ?? StudentProfile::create(['user_id' => $user->id]);
        $application = Application::where('user_id', $user->id)->first();
        $pasFoto = $application ? $application->documents()->where('original_name', 'like', '%Pas Foto%')->first() : null;
        
        return view('portal.biodata', compact('user', 'profile', 'application', 'pasFoto'));
    }

    public function updateBiodata(Request $request)
    {
        $user = Auth::user();
        $profile = $user->studentProfile;
        
        $validated = $request->validate([
            'nama_panggilan' => 'nullable|string|max:255',
            'nis' => 'nullable|string|max:50',
            'nisn' => 'nullable|string|max:50',
            'jenis_kelamin' => 'nullable|string|in:Laki-laki,Perempuan',
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'alamat_lengkap' => 'nullable|string',
            'npsn' => 'nullable|string|max:50',
            'kelas' => 'nullable|string|max:50',
            'tahun_ajaran' => 'nullable|string|max:50',
            'nama_wali_kelas' => 'nullable|string|max:255',
            'no_hp_wali_kelas' => 'nullable|string|max:50',
            'nama_kontak_darurat' => 'nullable|string|max:255',
            'hubungan_kontak_darurat' => 'nullable|string|max:255',
            'no_hp_kontak_darurat' => 'nullable|string|max:50',
            'alamat_kontak_darurat' => 'nullable|string',
        ]);

        $profile->update($validated);
        
        return back()->with('success', 'Biodata berhasil diperbarui.');
    }

    public function informasi()
    {
        $user = Auth::user();
        if ($user->role === 'guru_pondok') {
            return redirect()->route('portal.guru.absensi-rombongan');
        }
        $application = Application::where('user_id', $user->id)->first();
        
        // Status Tanggungan calculation
        $adminComplete = $user->studentProfile && $user->studentProfile->nis ? true : false;
        $docComplete = $application && $application->documents()->count() >= 4 ? true : false;
        $laporan = ReportSubmission::where('user_id', $user->id)->latest()->first();
        
        $tataTertib = \App\Models\Setting::where('key', 'tata_tertib')->first();
        $sopPkl = \App\Models\Setting::where('key', 'sop_pkl')->first();
        
        return view('portal.informasi', compact('application', 'adminComplete', 'docComplete', 'laporan', 'tataTertib', 'sopPkl'));
    }

    public function absensi(Request $request)
    {
        $user = Auth::user();
        if ($user->role === 'guru_pondok') {
            return redirect()->route('portal.guru.absensi-rombongan');
        }
        $month = $request->get('month', now()->format('m'));
        $year = $request->get('year', now()->format('Y'));
        
        $attendances = Attendance::where('user_id', $user->id)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->orderBy('date', 'desc')
            ->paginate(10);
            
        $totalDays = Attendance::where('user_id', $user->id)->count();
        $present = Attendance::where('user_id', $user->id)->where('status', 'Hadir')->count();
        $absent = Attendance::where('user_id', $user->id)->where('status', '!=', 'Hadir')->count();
        $percentage = $totalDays > 0 ? round(($present / $totalDays) * 100) : 0;
            
        return view('portal.absensi', compact('attendances', 'totalDays', 'present', 'absent', 'percentage', 'month', 'year'));
    }

    public function exportAbsensi(Request $request)
    {
        $user = Auth::user();
        if ($user->role === 'guru_pondok') {
            return redirect()->route('portal.guru.absensi-rombongan');
        }
        
        $month = $request->get('month', now()->format('m'));
        $year = $request->get('year', now()->format('Y'));
        
        $attendances = Attendance::where('user_id', $user->id)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->orderBy('date', 'asc')
            ->get();
            
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set Header
        $sheet->setCellValue('A1', 'Tanggal');
        $sheet->setCellValue('B1', 'Hari');
        $sheet->setCellValue('C1', 'Jam Masuk');
        $sheet->setCellValue('D1', 'Jam Pulang');
        $sheet->setCellValue('E1', 'Status');
        $sheet->setCellValue('F1', 'Keterangan');

        // Make Header Bold
        $sheet->getStyle('A1:F1')->getFont()->setBold(true);
        $sheet->getStyle('A1:F1')->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        $row = 2;
        foreach ($attendances as $att) {
            $dateParsed = \Carbon\Carbon::parse($att->date);
            $sheet->setCellValue('A' . $row, $dateParsed->format('d M Y'));
            $sheet->setCellValue('B' . $row, $dateParsed->isoFormat('dddd'));
            $sheet->setCellValue('C' . $row, $att->check_in ? \Carbon\Carbon::parse($att->check_in)->format('H:i') : '-');
            $sheet->setCellValue('D' . $row, $att->check_out ? \Carbon\Carbon::parse($att->check_out)->format('H:i') : '-');
            $sheet->setCellValue('E' . $row, $att->status);
            $sheet->setCellValue('F' . $row, $att->notes ?? '-');
            $row++;
        }

        // Auto size columns
        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $fileName = 'Laporan_Absensi_Saya_' . $month . '-' . $year . '.xlsx';
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');
        
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    public function laporan()
    {
        $user = Auth::user();
        if ($user->role === 'guru_pondok') {
            return redirect()->route('portal.guru.absensi-rombongan');
        }
        $laporan = ReportSubmission::where('user_id', $user->id)->latest()->first();
        $application = Application::where('user_id', $user->id)->first();
        $profile = $user->studentProfile;
        $certificates = \App\Models\Certificate::where('user_id', $user->id)->latest()->get();
        
        return view('portal.laporan', compact('laporan', 'application', 'profile', 'certificates'));
    }

    public function storeLaporan(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'file_path' => 'required|file|mimes:pdf|max:10240',
        ]);
        
        $path = $request->file('file_path')->store('reports', 'public');
        
        ReportSubmission::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'notes' => $request->notes,
            'file_path' => $path,
            'status' => 'pending',
        ]);
        
        return back()->with('success', 'Laporan berhasil diajukan dan sedang menunggu verifikasi admin.');
    }

    public function faceRegistration()
    {
        $user = Auth::user();
        $profile = StudentProfile::firstOrCreate(
            ['user_id' => $user->id],
            []
        );

        return view('portal.face-registration', compact('user', 'profile'));
    }

    private function checkFaceUniqueness($newDescriptorArray, $currentUserId)
    {
        $allProfiles = StudentProfile::whereNotNull('face_descriptor')
                        ->where('user_id', '!=', $currentUserId)
                        ->get();
                        
        foreach ($allProfiles as $profile) {
            $existingDescriptor = json_decode($profile->face_descriptor, true);
            if (!is_array($existingDescriptor) || count($existingDescriptor) !== 128) continue;
            
            // Calculate euclidean distance
            $sum = 0;
            for ($i = 0; $i < 128; $i++) {
                $diff = $newDescriptorArray[$i] - $existingDescriptor[$i];
                $sum += $diff * $diff;
            }
            $distance = sqrt($sum);
            
            // Threshold 0.55 for uniqueness check. 
            // Jika distance < 0.55, berarti wajah ini dianggap mirip / sama dengan yang sudah ada di database
            if ($distance < 0.55) {
                return false; // Wajah sudah terdaftar oleh orang lain
            }
        }
        
        return true;
    }

    public function storeFaceDescriptor(Request $request)
    {
        $request->validate([
            'face_descriptor' => 'required|string',
        ]);

        $user = Auth::user();
        $profile = StudentProfile::where('user_id', $user->id)->first();
        
        if ($profile) {
            $newDescriptorArray = json_decode($request->face_descriptor, true);
            
            // Cek apakah wajah ini sudah pernah didaftarkan oleh akun lain
            if (!$this->checkFaceUniqueness($newDescriptorArray, $user->id)) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Wajah ini sudah terdaftar di sistem pada akun lain. Hubungi admin jika ini adalah kesalahan.'
                ], 400);
            }

            $profile->update([
                'face_descriptor' => $request->face_descriptor
            ]);
            
            return response()->json(['success' => true, 'message' => 'Data wajah berhasil disimpan.']);
        }

        return response()->json(['success' => false, 'message' => 'Profil tidak ditemukan.'], 404);
    }

    public function checkIn()
    {
        $user = Auth::user();
        $profile = StudentProfile::where('user_id', $user->id)->first();
        
        if (!$profile || empty($profile->face_descriptor)) {
            return redirect()->route('portal.face-registration')->with('error', 'Silakan daftarkan wajah Anda terlebih dahulu sebelum absen.');
        }

        $settings = \App\Models\Setting::pluck('value', 'key')->toArray();

        // Cek absensi hari ini
        $attendance = \App\Models\Attendance::where('user_id', $user->id)
            ->whereDate('date', today())
            ->first();

        return view('portal.absensi-check-in', compact('user', 'profile', 'settings', 'attendance'));
    }

    public function storeAbsensi(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'type' => 'required|in:in,out',
        ]);
        
        $attendance = Attendance::firstOrCreate(
            ['user_id' => $user->id, 'date' => today()],
            ['status' => 'Tidak Hadir'] // Default awal
        );
        
        $now = now();
        $currentTime = $now->format('H:i:s');
        
        if ($request->type === 'in') {
            if ($attendance->check_in) {
                return redirect()->route('portal.absensi.check-in')->with('error', 'Anda sudah melakukan absen masuk hari ini.');
            }
            $attendance->check_in = $now;
            
            if ($currentTime <= '07:12:00') {
                $attendance->status = 'Hadir';
            } else {
                $attendance->status = 'Telat';
            }
            $attendance->notes = 'Masuk: ' . $now->format('H:i');
        } else {
            if (!$attendance->check_in) {
                return redirect()->route('portal.absensi.check-in')->with('error', 'Anda harus absen masuk terlebih dahulu.');
            }
            if ($attendance->check_out) {
                return redirect()->route('portal.absensi.check-in')->with('error', 'Anda sudah melakukan absen pulang hari ini.');
            }
            if ($currentTime < '16:00:00') {
                return redirect()->route('portal.absensi.check-in')->with('error', 'Belum waktunya pulang. Waktu pulang minimal adalah jam 16:00.');
            }
            
            $attendance->check_out = $now;
            $attendance->notes .= ' | Pulang: ' . $now->format('H:i');
        }
        
        $attendance->save();
        
        return redirect()->route('portal.absensi.check-in')->with('success', 'Berhasil! Absensi ' . ($request->type === 'in' ? 'masuk' : 'pulang') . ' telah dicatat.');
    }

    public function absensiRombongan()
    {
        $user = Auth::user();
        if ($user->role !== 'guru_pondok') {
            abort(403, 'Akses ditolak.');
        }

        // Ambil semua siswa pondok yang diasuh oleh guru ini, dan hanya yang sudah punya descriptor wajah
        $students = StudentProfile::where('guru_id', $user->id)
                    ->whereNotNull('face_descriptor')
                    ->with('user')
                    ->get();
                    
        $settings = \App\Models\Setting::pluck('value', 'key')->toArray();

        return view('portal.guru-absensi-rombongan', compact('user', 'students', 'settings'));
    }

    public function storeAbsensiRombongan(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'guru_pondok') {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }

        $request->validate([
            'student_ids' => 'required|array',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'type' => 'required|in:in,out',
        ]);
        
        // Logika jarak opsional di backend (dapat disamakan dengan reguler)
        
        $now = now();
        $currentTime = $now->format('H:i:s');
        
        if ($request->type === 'out' && $currentTime < '16:00:00') {
            return response()->json(['success' => false, 'message' => 'Belum waktunya pulang. Waktu pulang minimal adalah jam 16:00.'], 400);
        }
        
        $count = 0;
        foreach ($request->student_ids as $student_id) {
            $attendance = Attendance::firstOrCreate(
                ['user_id' => $student_id, 'date' => today()],
                ['status' => 'Tidak Hadir']
            );
            
            if ($request->type === 'in') {
                if (!$attendance->check_in) {
                    $attendance->check_in = $now;
                    if ($currentTime <= '07:12:00') {
                        $attendance->status = 'Hadir';
                    } else {
                        $attendance->status = 'Telat';
                    }
                    $attendance->notes = 'Rombongan (Masuk): ' . $now->format('H:i');
                    $attendance->save();
                    $count++;
                }
            } else {
                if (!$attendance->check_out && $attendance->check_in) {
                    $attendance->check_out = $now;
                    $attendance->notes .= ' | Rombongan (Pulang): ' . $now->format('H:i');
                    $attendance->save();
                    $count++;
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => "Berhasil mencatat $count absensi siswa."
        ]);
    }
}
