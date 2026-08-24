# Implementasi Application Management System (AMS)

- `[x]` **Fase 1: Database & Model (Pondasi Data)**
  - `[x]` Buat/update Model & Migration `Applicant` atau integrasikan dengan tabel `users`.
  - `[x]` Buat Model & Migration `Application` (Nama, Instansi, Jurusan, Periode, dll beserta status `pending`, `document_review`, dll).
  - `[x]` Buat Model & Migration `ApplicationDocument` (untuk lampiran proposal/pengantar).
  - `[x]` Buat Model & Migration `ApplicationNote` (untuk riwayat catatan admin ke pendaftar).

- `[x]` **Fase 2: Filament Admin Panel Khusus (AmsAdmin)**
  - `[x]` Buat Panel Provider `AmsAdminPanelProvider` (terpisah dari admin utama).
  - `[x]` Buat `ApplicationResource` untuk memuat data pendaftar.
  - `[x]` Desain Custom Infolist untuk detail pendaftaran.
  - `[x]` Buat Custom Actions (Verifikasi, Lolos, Ditolak + Form modal catatan revisi).

- `[ ]` **Fase 3: Portal Pendaftar & Form Pendaftaran (Frontend)**
  - `[ ]` Buat Halaman Registrasi / Pengisian Form.
  - `[ ]` Integrasi sistem upload file (PDF, maks 10MB, 5 file).
  - `[ ]` Buat Dashboard Pendaftar sederhana untuk tracking status.

- `[ ]` **Fase 4: Sistem Notifikasi Email (Automasi)**
  - `[ ]` Buat `ApplicationObserver` untuk mendeteksi perubahan status.
  - `[ ]` Buat Mailable Template.
  - `[ ]` Konfigurasi Queue untuk email.

- `[ ]` **Fase 5: Verifikasi**
  - `[ ]` Test submission form & upload.
  - `[ ]` Test admin panel actions (approve/reject).
  - `[ ]` Test email notifications.
