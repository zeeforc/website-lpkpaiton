# Dokumentasi Proyek LPK Paiton Selaras

## 1. Pendahuluan
Dokumen ini merangkum arsitektur, dependensi, alur sistem (flow), dan logika cara kerja aplikasi web **LPK Paiton Selaras**. Dokumentasi ini ditujukan sebagai panduan komprehensif untuk pengembang di masa depan.

---

## 2. Arsitektur & Teknologi (Tech Stack)
Aplikasi ini dibangun menggunakan framework **Laravel 12 (PHP 8.2+)** dengan pendekatan monolitik. Sistem terbagi menjadi tiga antarmuka utama:
1.  **Landing Page**: Halaman publik untuk pengunjung umum (Berita, Profil, Visi Misi, dll).
2.  **Portal Pengguna**: Panel khusus untuk pendaftar PKL, Siswa aktif, Karyawan, Instruktur LPK, dan Guru Pondok.
3.  **Admin Panel**: Panel manajemen sistem menggunakan **Filament v3**.

### Dependensi Utama:
*   **Backend:** Laravel Framework (^12.0)
*   **Admin Panel:** Filament (^4.0) - *Digunakan untuk operasi CRUD Admin secara otomatis dan cepat.*
*   **Ekspor Dokumen:** PhpOffice/PhpSpreadsheet (^5.3) - *Digunakan untuk mengekspor data absensi ke format Excel yang disesuaikan.*
*   **Frontend Tools:** TailwindCSS (^4.0), Vite, Bootstrap (di beberapa sisi Portal & Landing Page).
*   **Face Recognition:** Integrasi *face-api.js* (atau pustaka serupa di frontend) untuk absensi wajah.
*   **Database:** MySQL (MariaDB).

---

## 3. Struktur Database (Model Utama)
Sistem memiliki beberapa entitas utama yang saling berelasi:
*   `User`: Menyimpan data autentikasi. Dibedakan berdasarkan field `role` (`amsadmin`, `siswa`, `karyawan_paving`, `instruktur_lpk`, `guru_pondok`, `pendaftar`).
*   `Application` & `ApplicationDocument`: Sistem pendaftaran PKL.
*   `Attendance`: Rekam jejak absensi harian (Jam Masuk, Jam Pulang, Deskripsi Pekerjaan).
*   `LeaveRequest`: Sistem pengajuan izin/sakit multi-hari.
*   `FaceDescriptor`: Data vektor wajah untuk keperluan absensi biometrik via kamera.
*   `Visitor`: Mencatat statistik kunjungan ke Landing Page.
*   *Model Landing Page*: `Home`, `Vimi`, `Team`, `Testimoni`, `BeritaUtama`, `Galery`, `Kurikulum`, dll.

---

## 4. Alur Kerja (Flow) & Logika Sistem

### A. Alur Pendaftaran PKL & Dokumen
1.  **Pendaftaran Awal**: Calon siswa mendaftar melalui form publik (`/pendaftaran`). Status awal adalah `pending`.
2.  **Upload Dokumen**: Calon pendaftar diarahkan ke halaman spesifik (menggunakan *signed route* untuk keamanan) untuk mengunggah dokumen persyaratan.
3.  **Review Admin**: Admin memeriksa setiap dokumen via Filament.
4.  **Observer (`ApplicationDocumentObserver`)**:
    *   Setiap kali status dokumen berubah (misal: di-*reject* atau di-*approve*), Observer otomatis mengirim **Notifikasi Email** ke pendaftar.
    *   **Auto-Accept Logic**: Jika admin menyetujui *semua* dokumen wajib dari pendaftar tersebut, sistem otomatis mengubah status `Application` (Pendaftaran) menjadi `accepted` (Lolos). Pendaftar kemudian dibuatkan akun `User` resmi dengan role `siswa`.

### B. Alur Absensi & Face Recognition (Face-API)
1.  **Registrasi Wajah**: Siswa/Karyawan baru harus melakukan pemindaian wajah di menu *Face Registration*. Data titik wajah (descriptor) disimpan di tabel `FaceDescriptor`.
2.  **Check-In / Check-Out**:
    *   Pengguna membuka menu Absensi. Sistem menyalakan kamera depan.
    *   Wajah pengguna dicocokkan dengan descriptor yang tersimpan.
    *   Jika cocok, sistem mengirim koordinat (opsional) dan status `type` (masuk/pulang) ke `PortalController@storeAbsensi`.
    *   Data absensi (Waktu, Bukti Selfie) disimpan di tabel `Attendance`.

### C. Alur Perizinan (Leave Requests)
1.  **Pengajuan Multi-Hari**: Siswa/Karyawan mengisi rentang tanggal (`date` s/d `end_date`), alasan, tipe (Izin/Sakit), dan bukti surat.
2.  **Validasi Bentrok (Anti-Collision)**: Sistem mengecek apakah pengguna sudah mengajukan izin di rentang tanggal tersebut. (Sistem akan mengabaikan pengajuan terdahulu jika statusnya `rejected`).
3.  **Pembatalan (Cancel)**: Selama status masih `pending`, pemohon dapat membatalkan izinnya sendiri, sistem akan menghapus data dan lampiran suratnya.
4.  **Review Admin & Observer (`LeaveRequestObserver`)**:
    *   Admin menyetujui (`approved`) via Filament.
    *   Observer mendeteksi perubahan ke `approved`. Sistem akan melakukan *looping* dari `date` hingga `end_date`, lalu **Otomatis Mengisi Absensi** (tabel `Attendance`) di hari-hari tersebut dengan status 'Izin' atau 'Sakit'.

### D. Export Laporan (Excel)
Digunakan `PhpOffice/PhpSpreadsheet` di route terpisah. Laporan digenerate secara manual baris demi baris, dengan styling sel tabel langsung dari PHP untuk menghasilkan file `.xlsx` absensi bulanan yang *ready-to-print*.

---

## 5. UI/UX & Standar "Antislop"
Desain aplikasi, khususnya di area Error Pages, Landing Page, dan Portal menganut gaya **Antislop** (sebuah set aturan estetika kustom):
*   **Visual**: Tampilan bersih, vektor 3D yang relevan (bukan stok foto abal-abal), tanpa bayangan (*glow*) berlebih. Penggunaan `container`, `glass-card`, dan tipografi (font Poppins/Inter).
*   **Copywriting**: Bahasa yang membumi, manusiawi, jelas, dan menghindari jargon teknis berlebihan. Status-status memiliki indikator warna (*badge*) yang jelas.

---

## 6. Manajemen Peran (RBAC) & Keamanan
*   **Middleware**: Akses setiap rute dilindungi oleh middleware bawaan Laravel (`auth`).
*   **Filament Policies / Roles**: Akses menu admin Filament dikunci khusus untuk pengguna yang memiliki peran administrator.
*   **Uploads**: Semua dokumen/foto pengguna (surat izin, bukti absensi, foto profil) disimpan dalam disk `public` dan diakses menggunakan symlink (`php artisan storage:link`). Divalidasi secara ketat di *Controller* (mimes, maks ukuran file).

---

## Kesimpulan & Panduan Pengembangan Lanjutan
1.  **Jika ingin menambah Fitur Portal**: Tambahkan method di `App\Http\Controllers\PortalController.php`, lalu buat view baru di `resources/views/portal/`.
2.  **Jika ingin memanipulasi logika "setelah data disimpan"**: Selalu gunakan **Observer** (contoh: `LeaveRequestObserver`, `ApplicationDocumentObserver`). Jangan menumpuk kode logika pengiriman email atau *auto-generate* data di *Controller*.
3.  **Jika menambah kolom database**: Buat *migration*, sesuaikan file Model (tambahkan di `$fillable` dan `$casts`), lalu perbaharui antarmuka *Resource* Filament terkait di folder `App\Filament\`.
