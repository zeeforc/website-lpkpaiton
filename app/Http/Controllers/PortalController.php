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

            return redirect()->intended(route('portal.biodata'));
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
        $profile = $user->studentProfile ?? StudentProfile::create(['user_id' => $user->id]);
        $application = Application::where('user_id', $user->id)->first();
        
        return view('portal.biodata', compact('user', 'profile', 'application'));
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
        $application = Application::where('user_id', $user->id)->first();
        
        // Status Tanggungan calculation
        $adminComplete = $user->studentProfile && $user->studentProfile->nis ? true : false;
        $docComplete = $application && $application->documents()->count() >= 4 ? true : false;
        $laporan = ReportSubmission::where('user_id', $user->id)->latest()->first();
        
        return view('portal.informasi', compact('application', 'adminComplete', 'docComplete', 'laporan'));
    }

    public function absensi(Request $request)
    {
        $user = Auth::user();
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

    public function laporan()
    {
        $user = Auth::user();
        $laporan = ReportSubmission::where('user_id', $user->id)->latest()->first();
        $application = Application::where('user_id', $user->id)->first();
        $profile = $user->studentProfile;
        
        return view('portal.laporan', compact('laporan', 'application', 'profile'));
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
}
