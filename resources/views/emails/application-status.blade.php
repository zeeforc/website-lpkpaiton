<x-mail::message>
# Pemberitahuan Status Pendaftaran

Halo **{{ $application->nama_lengkap }}**,

Terima kasih telah mendaftar pada program LPK Paiton Selaras. Berikut adalah update mengenai status pendaftaran Anda:

@if($application->status === 'permohonan_diterima')
Permohonan awal Anda **Telah Diterima**. Langkah selanjutnya adalah melengkapi pendaftaran dengan mengunggah Dokumen Surat Pengantar Resmi & Proposal.

Silakan klik tombol di bawah ini untuk mengunggah dokumen Anda:
<x-mail::button :url="URL::signedRoute('application.upload', ['application' => $application->id])">
Upload Dokumen
</x-mail::button>
@elseif($application->status === 'document_review')
Status pendaftaran Anda saat ini: **Review Dokumen**. 
Dokumen yang Anda unggah sedang dalam proses peninjauan oleh tim kami. Harap menunggu informasi selanjutnya.
@elseif($application->status === 'accepted')
Selamat! Pendaftaran Anda dinyatakan **Lolos**.

@if($application->tingkat_pendidikan === 'Mahasiswa')
Sebagai mahasiswa, tahap selanjutnya adalah proses wawancara. Silakan segera menghubungi Admin kami melalui pesan WhatsApp di nomor **+62 811-3059-8801** untuk koordinasi jadwal dan arahan proses interview selanjutnya.
@else
Permohonan praktik kerja (PKL) Anda telah disetujui. Silakan segera menghubungi Admin kami melalui pesan WhatsApp di nomor **+62 811-3059-8801** untuk melakukan konfirmasi jadwal mulai masuk dan mendapatkan arahan selanjutnya.
@endif

---
**Akun Portal Siswa PKL Anda telah dibuat.**
Silakan login ke portal untuk melengkapi biodata, melihat informasi PKL, absen, dan mengumpulkan laporan.

<x-mail::button :url="url('/portal/login')">
Login Portal Siswa PKL
</x-mail::button>

**Email Login:** {{ $application->email_balasan }}
**Password:** {{ $password }}

*(Harap simpan informasi login ini dengan baik dan jangan bagikan kepada siapa pun)*

@elseif($application->status === 'rejected')
Mohon maaf, pendaftaran Anda **Ditolak / Membutuhkan Revisi**.

@if(!empty($note))
**Catatan dari Panitia:**
{{ $note }}
@endif
@endif

Terima kasih,<br>
Lembaga Pelatihan Kerja Paiton Selaras - Internship Program
</x-mail::message>
