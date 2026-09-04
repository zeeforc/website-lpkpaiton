# Panduan Desain (Design Guidelines) - Portal Siswa LPK Paiton Selaras

Dokumen ini adalah panduan gaya (style guide) utama untuk antarmuka pengguna (UI) Portal Siswa LPK Paiton Selaras. Agen AI (seperti Anti-slop) **wajib mematuhi panduan ini** saat membuat, memodifikasi, atau memberikan ulasan terhadap komponen antarmuka web ini.

## 1. Konsep Utama & Vibe
- **Soft UI & Modern Glassmorphism:** Desain mengutamakan tampilan yang sangat lembut, bersih, bernapas (banyak *white space*), dan modern. Hindari garis batas (border) yang tajam atau warna latar yang saling bertabrakan secara kaku.
- **Mempertahankan Layout, Mengubah Gaya:** Struktur dasar halaman (*layout*, letak navigasi, letak konten utama) tidak perlu dirombak total hingga menyalahi fungsionalitas aslinya. Fokus pada modifikasi **gaya visual per komponen** (kartu, form, tombol, teks) agar setara dengan estetika *dashboard* modern seperti referensi.

## 2. Skema Warna (Color Palette)
- **Background Utama Aplikasi (Body):** Abu-abu kebiruan yang sangat lembut (misal: `#F3F5F9` atau `#F0F3F8`). Jangan gunakan warna putih murni untuk *background* utama agar batas *card* terlihat jelas.
- **Card Background:** 
  - **Tipe 1 (Soft Clean):** Putih murni (`#FFFFFF`) dengan sudut melengkung.
  - **Tipe 2 (Glassmorphism):** Latar agak transparan (misal `rgba(255, 255, 255, 0.65)`) dipadukan dengan efek *backdrop blur*. Sangat cocok bila ada latar belakang elemen grafis (*blobs*) berwarna di bawahnya.
  - **Tipe 3 (Gradient Accent):** Biru cerah bergradasi halus (ke arah ungu/cyan muda) khusus untuk menyoroti statistik penting atau data krusial.
- **Teks:**
  - **Teks Primer (Judul, Value Utama):** Biru gelap (*Slate/Navy*) yang tegas (misal: `#1E293B` atau `#0F172A`). Jangan gunakan hitam `#000000`.
  - **Teks Sekunder (Label, Subjudul, Tanggal):** Abu-abu kalem (misal: `#64748B` atau `#94A3B8`).
- **Elemen Aksen & Status:**
  - Biru utama (`#3B82F6`), Ungu (`#8B5CF6`).
  - Bentuk kapsul ("Pill" melengkung penuh) untuk *badge* status, tanggal, atau tag kategori.
- **Tombol (Buttons):**
  - **Primer:** Gelap solid (Hitam/`#111827`) dengan teks putih cerah. Memberikan kesan berkelas dan modern.
  - **Sekunder/Ghost:** Latar transparan atau putih dengan batas halus, atau warna abu-abu sangat muda dengan teks gelap.

## 3. Tipografi & Hierarki
- **Font Family:** Gunakan font *sans-serif* geometris yang berkesan kekinian. Pertahankan **'Inter'** (jika sudah standar proyek) tapi variasikan ketebalannya, atau ganti ke **'Outfit'** / **'Plus Jakarta Sans'** untuk *feel* yang lebih premium.
- **Hierarchy:** 
  - Label di atas *card* harus kecil, berwarna abu-abu, namun tebal (*semibold*).
  - Angka atau poin data utama (saldo, jumlah kehadiran) harus menggunakan ukuran besar (*Hero size*) dengan warna Primer.
  - Berikan jarak yang lapang (*line-height* dan *margin*) antar tumpukan teks.

## 4. Gaya Komponen Khusus
- **Sudut Melengkung (Border Radius):** Wajib menggunakan lengkungan besar.
  - *Card* besar/utama: `20px` hingga `24px`.
  - Tombol dan komponen dalam *card*: `10px` hingga `14px`.
- **Shadows (Bayangan):** Haram menggunakan *shadow* yang kecil, gelap, dan kaku.
  - Gunakan *shadow* yang lebar, sangat pudar, dan lembut untuk mengangkat komponen: `box-shadow: 0 10px 40px -10px rgba(0, 0, 0, 0.05);`
- **Trik Glassmorphism:** 
  - Untuk memunculkan efek *glass*, gunakan CSS ini: `backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px); background: rgba(255,255,255,0.7);`.
  - Selalu tambahkan *border* putih super tipis di sekitar efek kaca untuk memberi kesan memantulkan cahaya: `border: 1px solid rgba(255, 255, 255, 0.8);`
- **Pemetaan Latar Belakang (Blobs):** Jika *card* memakai *glassmorphism*, usahakan untuk meletakkan bentuk melingkar dengan *blur* ekstrim berwarna biru muda atau ungu lembut di lapisan paling bawah *container*, agar warnanya "tembus" lewat kartu transparan.

## 5. Liveliness (Animasi & Interaksi)
Setiap elemen yang bisa diklik atau disentuh kursor wajib bereaksi untuk menandakan bahwa desain ini "hidup":
- **Transisi Dasar:** `transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);` di setiap tombol dan *card*.
- **Hover States:** 
  - *Card*: Saat dilewati kursor, *card* terangkat halus ke atas (`transform: translateY(-4px);`) dan *shadow*-nya membesar merata.
  - Tombol: Warna sedikit memudar atau berubah kontras.
- **Active/Click States:** Saat diklik, elemen mengecil secara wajar (`transform: scale(0.97);`) memberikan rasa taktil (tekanan fisik) pada tombol/elemen tersebut.
