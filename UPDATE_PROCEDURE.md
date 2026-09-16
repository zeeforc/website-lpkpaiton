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

## SAFETY NET (SUDAH DITERAPKAN DI DALAM KODE)
Sebagai lapisan keamanan tambahan, kode sistem ini telah dipasangi *Graceful Error Handling* (Metode 2 & 3). 

**Contoh Kasus:** 
Jika Anda *lupa* menjalankan `php artisan migrate` setelah melakukan `git pull`, sistem akan secara otomatis mengecek ketersediaan kolom di database secara langsung (misal menggunakan `Schema::hasColumn(...)`). 
Jika kolom belum ada, sistem **tidak akan memunculkan layar error merah (crash)**, melainkan akan melewati proses penyimpanan data tersebut secara halus dan tetap melanjutkan operasi utama pengguna (misal absen tetap sukses tercatat jamnya).

*Penting: Safety net ini bukan pengganti Metode Utama, melainkan hanya sebagai sistem asuransi agar aplikasi tidak mati total saat terjadi kesalahan manusia.*
