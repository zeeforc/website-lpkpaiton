# Prosedur Update Server Production LPK Paiton Selaras

Dokumen ini berisi standar operasional prosedur (SOP) untuk memperbarui (update) kode di server production (live server) tanpa menyebabkan error atau mengganggu user yang sedang aktif menggunakan sistem.

## METODE UTAMA (WAJIB DIIKUTI)
Gunakan metode **Maintenance Mode** ini setiap kali Anda melakukan update kode dari GitHub ke server production, terutama jika update tersebut melibatkan perubahan struktur database (`migrations`).

### Langkah-langkah Update:

1. **Aktifkan Maintenance Mode**
   Masuk ke terminal server production Anda (melalui cPanel SSH atau Terminal web) dan arahkan ke direktori project. Terdapat 2 opsi yang bisa dijalankan:
   
   **Opsi A (Update Kecil - Cepat)**
   Jalankan perintah standar jika proses update dan migrate dirasa sangat cepat (kurang dari 10 detik):
   ```bash
   php artisan down
   ```
   
   **Opsi B (Update Besar - Perlu Testing di Production)**
   Gunakan perintah ini jika Anda perlu mengunci website dari orang luar, namun Anda butuh waktu masuk ke dalam website untuk mengecek/testing apakah semuanya berjalan lancar sebelum website dibuka kembali untuk publik:
   ```bash
   php artisan down --secret="testinglpk"
   ```
   *Note: Setelah menjalankan ini, Anda dapat menembus mode maintenance dengan mengakses URL rahasia: `https://lpkpaiton.site/testinglpk` melalui browser Anda.*

2. **Tarik Kode Terbaru (Pull)**
   ```bash
   git pull
   ```
   *Fungsi: Mengunduh fitur terbaru dan perubahan kode dari repositori GitHub.*

3. **Perbarui Struktur Database (Migrate)**
   ```bash
   php artisan migrate
   ```
   *Fungsi: Menerapkan penambahan/pengurangan tabel atau kolom baru di database sesuai dengan kode yang baru.*

4. **Matikan Maintenance Mode**
   ```bash
   php artisan up
   ```
   *Fungsi: Membuka kembali akses website untuk semua user. Website sekarang menggunakan kode terbaru dengan aman.*

> Waktu pengerjaan prosedur ini idealnya kurang dari 10 detik.

---

## CRON WAJIB UNTUK CLEANUP STORAGE

Jadwal Laravel hanya berjalan jika cron server memanggil scheduler. Di cPanel **Cron Jobs**, tambahkan cron yang berjalan setiap menit:

```bash
* * * * * cd /home/lpkpait1/website-lpkpaiton && php artisan schedule:run >> /dev/null 2>&1
```

Jika `php` pada cron berbeda dari versi PHP website, gunakan path binary PHP 8.3 dari cPanel, misalnya `ea-php83`, sesuai konfigurasi hosting. Pastikan cron aktif dan gunakan path project yang benar.

Scheduler akan:

- menghapus ZIP ekspor absensi dan file unduhan lama yang tidak selesai (`.part`) setelah berumur lebih dari 6 jam;
- memproses antrean kompresi foto checkout tiap menit, satu worker, supaya request absensi cepat selesai meskipun beberapa karyawan mengirim foto bersamaan;
- mengonversi maksimal 10 foto absensi atau dokumentasi lama ke WebP setiap 5 menit sebagai pemrosesan arsip dan cadangan jika dispatch job gagal. Proses memakai lock yang sama dengan export supaya foto tidak berubah saat sedang diunduh;
- mempertahankan satu dokumen syarat PKL terbaru; saat dokumen baru disimpan, dokumen lama dan file-nya dihapus.

Jadwal penghapusan foto absensi **dinonaktifkan sementara**. Jangan menjalankan command cleanup foto sampai dokumentasi Oktober sudah berhasil diekspor dan kebijakan retensi dikonfirmasi kembali.

Setelah `git pull`, sinkronkan dependency yang tercantum di Composer:

```bash
composer install --no-dev --optimize-autoloader
php artisan optimize:clear
```

Kemudian hapus ZIP sementara lama yang berasal dari percobaan export gagal. Pastikan tidak ada unduhan dokumentasi lama yang sedang berjalan:

```bash
php artisan app:cleanup-attendance-exports --all
```

Perintah migrasi foto aman dijalankan berulang, maksimal 10 gambar per batch:

```bash
php artisan app:optimize-attendance-photos --limit=10
```

Karyawan harus mengunggah 2 foto checkout, masing-masing harus berukuran di bawah 1 MB (maksimal 1023 KB). File asli disimpan dan absensi dicatat terlebih dahulu; job antrean lalu mengonversi foto ke WebP dengan sisi terpanjang awal maksimal 1920 piksel dan kualitas 80. Kualitas kemudian dimensi diturunkan seperlunya agar hasil maksimal 100 KB. Bila batas ukuran atau jumlah foto terlampaui, halaman absensi menampilkan pesan validasi. Foto selfie hanya dikirim saat absen masuk supaya request pulang tidak menjalankan proses kompresi sinkron tambahan.

Download dokumentasi karyawan menggunakan streaming ZIP langsung ke koneksi HTTP, tanpa membuat arsip sementara di storage. ZIP memakai metode STORE karena JPEG/WebP sudah terkompresi, sehingga server tidak menghabiskan waktu dan CPU untuk mengompres ulang foto.

---

## SAFETY NET (SUDAH DITERAPKAN DI DALAM KODE)
Sebagai lapisan keamanan tambahan, kode sistem ini telah dipasangi *Graceful Error Handling* (Metode 2 & 3). 

**Contoh Kasus:** 
Jika Anda *lupa* menjalankan `php artisan migrate` setelah melakukan `git pull`, sistem akan secara otomatis mengecek ketersediaan kolom di database secara langsung (misal menggunakan `Schema::hasColumn(...)`). 
Jika kolom belum ada, sistem **tidak akan memunculkan layar error merah (crash)**, melainkan akan melewati proses penyimpanan data tersebut secara halus dan tetap melanjutkan operasi utama pengguna (misal absen tetap sukses tercatat jamnya).

*Penting: Safety net ini bukan pengganti Metode Utama, melainkan hanya sebagai sistem asuransi agar aplikasi tidak mati total saat terjadi kesalahan manusia.*
