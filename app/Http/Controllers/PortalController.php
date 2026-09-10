<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\StudentProfile;
use App\Models\Attendance;
use App\Models\ReportSubmission;
use App\Models\Application;
use App\Models\LeaveRequest;
use App\Models\Holiday;
use Carbon\Carbon;

class PortalController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            if (Auth::user()->role === 'guru_pondok') {
                return redirect()->route('portal.guru.absensi-rombongan');
            } elseif (in_array(Auth::user()->role, ['karyawan_paving', 'instruktur_lpk'])) {
                return redirect()->route('portal.absensi.check-in');
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
            } elseif (in_array($user->role, ['karyawan_paving', 'instruktur_lpk'])) {
                return redirect()->route('portal.absensi.check-in');
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

    public function showRegisterKaryawan()
    {
        if (Auth::check()) {
            if (Auth::user()->role === 'guru_pondok') {
                return redirect()->route('portal.guru.absensi-rombongan');
            } elseif (in_array(Auth::user()->role, ['karyawan_paving', 'instruktur_lpk'])) {
                return redirect()->route('portal.absensi.check-in');
            }
            return redirect()->route('portal.biodata');
        }
        return view('portal.register-karyawan');
    }

    public function registerKaryawan(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:6'],
            'lokasi' => ['required', 'in:lpk,paving'],
        ]);

        $role = $request->lokasi === 'lpk' ? 'instruktur_lpk' : 'karyawan_paving';

        $user = \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'role' => $role,
        ]);

        Auth::login($user);

        return redirect()->route('portal.absensi.check-in')->with('success', 'Pendaftaran berhasil. Silakan daftarkan wajah Anda untuk absensi.');
    }

    public function biodata()
    {
        $user = Auth::user();
        if ($user->role === 'guru_pondok') {
            return redirect()->route('portal.guru.absensi-rombongan');
        } elseif (in_array($user->role, ['karyawan_paving', 'instruktur_lpk'])) {
            return redirect()->route('portal.absensi.check-in');
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
        } elseif (in_array($user->role, ['karyawan_paving', 'instruktur_lpk'])) {
            return redirect()->route('portal.absensi.check-in');
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
        
        $startDate = Carbon::createFromDate($year, $month, 1);
        $endDate = $startDate->copy()->endOfMonth();
        
        // Fetch all attendances for the month
        $attendancesData = Attendance::where('user_id', $user->id)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->get()
            ->keyBy(fn($item) => Carbon::parse($item->date)->format('Y-m-d'));
            
        // Fetch all holidays
        $holidays = Holiday::whereMonth('date', $month)
            ->whereYear('date', $year)
            ->get()
            ->keyBy(fn($item) => Carbon::parse($item->date)->format('Y-m-d'));
            
        // Fetch all approved leaves
        $leaves = LeaveRequest::where('user_id', $user->id)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->where('status', 'approved')
            ->get()
            ->keyBy(fn($item) => Carbon::parse($item->date)->format('Y-m-d'));

        // Generate all days in the month up to today (or end of month if it's a past month)
        $limitDate = ($month == now()->format('m') && $year == now()->format('Y')) ? now() : $endDate;
        
        $attendances = collect();
        $totalDays = 0;
        $present = 0;
        $absent = 0;

        for ($date = $endDate->copy(); $date->gte($startDate); $date->subDay()) {
            if ($date->gt($limitDate) && $date->format('Y-m-d') !== $limitDate->format('Y-m-d')) {
                continue;
            }
            
            $dateString = $date->format('Y-m-d');
            $isWeekend = $date->isWeekend();
            
            $status = 'Tidak Hadir';
            $notes = null;
            $checkIn = null;
            $checkOut = null;
            
            // Priorities: Holiday -> Weekend -> Leave -> Attendance -> Absent
            if (isset($holidays[$dateString])) {
                $status = 'Libur';
                $notes = $holidays[$dateString]->name;
            } elseif ($isWeekend) {
                $status = 'Libur';
                $notes = 'Libur Akhir Pekan';
            } elseif (isset($leaves[$dateString])) {
                $status = ucfirst($leaves[$dateString]->type); // Izin / Sakit
                $notes = 'Surat keterangan diajukan';
            } elseif (isset($attendancesData[$dateString])) {
                $att = $attendancesData[$dateString];
                $status = $att->status;
                $notes = $att->notes;
                $checkIn = $att->check_in;
                $checkOut = $att->check_out;
            }
            
            if ($status !== 'Libur') {
                $totalDays++;
                if ($status === 'Hadir' || $status === 'Telat') {
                    $present++;
                } else {
                    $absent++;
                }
            }

            // Create a pseudo object to match the view's expectation
            $attendances->push((object)[
                'date' => $dateString,
                'check_in' => $checkIn,
                'check_out' => $checkOut,
                'status' => $status,
                'notes' => $notes,
            ]);
        }
        
        // Manual pagination logic since it's a collection now
        $perPage = 10;
        $page = request()->get('page', 1);
        $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
            $attendances->forPage($page, $perPage),
            $attendances->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );
        $attendances = $paginator;
        
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
        
        $startDate = Carbon::createFromDate($year, $month, 1);
        $endDate = $startDate->copy()->endOfMonth();
        
        $attendancesData = Attendance::where('user_id', $user->id)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->get()
            ->keyBy(fn($item) => Carbon::parse($item->date)->format('Y-m-d'));
            
        $holidays = Holiday::whereMonth('date', $month)
            ->whereYear('date', $year)
            ->get()
            ->keyBy(fn($item) => Carbon::parse($item->date)->format('Y-m-d'));
            
        $leaves = LeaveRequest::where('user_id', $user->id)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->where('status', 'approved')
            ->get()
            ->keyBy(fn($item) => Carbon::parse($item->date)->format('Y-m-d'));
            
        $limitDate = ($month == now()->format('m') && $year == now()->format('Y')) ? now() : $endDate;
        
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
        
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            if ($date->gt($limitDate) && $date->format('Y-m-d') !== $limitDate->format('Y-m-d')) {
                continue;
            }
            
            $dateString = $date->format('Y-m-d');
            $isWeekend = $date->isWeekend();
            
            $status = 'Tidak Hadir';
            $notes = null;
            $checkIn = null;
            $checkOut = null;
            
            if (isset($holidays[$dateString])) {
                $status = 'Libur';
                $notes = $holidays[$dateString]->name;
            } elseif ($isWeekend) {
                $status = 'Libur';
                $notes = 'Libur Akhir Pekan';
            } elseif (isset($leaves[$dateString])) {
                $status = ucfirst($leaves[$dateString]->type);
                $notes = 'Surat keterangan diajukan';
            } elseif (isset($attendancesData[$dateString])) {
                $att = $attendancesData[$dateString];
                $status = $att->status;
                $notes = $att->notes;
                $checkIn = $att->check_in;
                $checkOut = $att->check_out;
            }

            $sheet->setCellValue('A' . $row, $date->format('d M Y'));
            $sheet->setCellValue('B' . $row, $date->isoFormat('dddd'));
            $sheet->setCellValue('C' . $row, $checkIn ? \Carbon\Carbon::parse($checkIn)->format('H:i') : '-');
            $sheet->setCellValue('D' . $row, $checkOut ? \Carbon\Carbon::parse($checkOut)->format('H:i') : '-');
            $sheet->setCellValue('E' . $row, $status);
            $sheet->setCellValue('F' . $row, $notes ?? '-');
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
        } elseif (in_array($user->role, ['karyawan_paving', 'instruktur_lpk'])) {
            return redirect()->route('portal.absensi.check-in');
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
        
        $today = today();
        
        // Cek Libur / Akhir Pekan
        if ($today->isWeekend()) {
            return redirect()->route('portal.absensi.check-in')->with('error', 'Hari ini adalah akhir pekan (Sabtu/Minggu). Absensi tidak diperlukan.');
        }
        
        $holiday = Holiday::whereDate('date', $today)->first();
        if ($holiday) {
            return redirect()->route('portal.absensi.check-in')->with('error', 'Hari ini adalah hari libur ('.$holiday->name.'). Absensi tidak diperlukan.');
        }
        
        // Cek Izin / Sakit
        $leave = LeaveRequest::where('user_id', $user->id)->whereDate('date', $today)->where('status', 'approved')->first();
        if ($leave) {
            return redirect()->route('portal.absensi.check-in')->with('error', 'Anda telah terdaftar '.ucfirst($leave->type).' hari ini. Absensi tidak diperlukan.');
        }
        
        $attendance = Attendance::firstOrCreate(
            ['user_id' => $user->id, 'date' => $today],
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
            
            if ($request->has('work_description')) {
                $attendance->work_description = $request->work_description;
            }
        }
        
        $attendance->save();
        
        return redirect()->route('portal.absensi.check-in')->with('success', 'Berhasil! Absensi ' . ($request->type === 'in' ? 'masuk' : 'pulang') . ' telah dicatat.');
    }
    
    public function izin()
    {
        $user = Auth::user();
        if ($user->role === 'guru_pondok') {
            return redirect()->route('portal.guru.absensi-rombongan');
        }
        
        $leaveRequests = LeaveRequest::where('user_id', $user->id)->orderBy('date', 'desc')->get();
        return view('portal.izin', compact('leaveRequests'));
    }
    
    public function storeIzin(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'date' => 'required|date',
            'type' => 'required|in:sakit,izin',
            'reason' => 'required|string',
            'attachment' => 'required|file|mimes:pdf,jpeg,png,jpg|max:2048',
        ]);
        
        $existing = LeaveRequest::where('user_id', $user->id)->whereDate('date', $request->date)->first();
        if ($existing) {
            return back()->with('error', 'Anda sudah pernah mengajukan perizinan untuk tanggal tersebut.');
        }
        
        $path = $request->file('attachment')->store('leave_attachments', 'public');
        
        LeaveRequest::create([
            'user_id' => $user->id,
            'date' => $request->date,
            'type' => $request->type,
            'reason' => $request->reason,
            'attachment_path' => $path,
            'status' => 'pending',
        ]);
        
        return back()->with('success', 'Perizinan berhasil diajukan. Silakan tunggu konfirmasi admin.');
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
