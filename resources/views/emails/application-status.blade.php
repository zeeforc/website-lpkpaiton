<x-mail::message>
# Pemberitahuan Status Pendaftaran

Halo **{{ $application->nama_lengkap }}**,

Terima kasih telah mendaftar pada program LPK Paiton Selaras. Berikut adalah update mengenai status pendaftaran Anda:

@if($application->status === 'permohonan_diterima')
Permohonan pendaftaran Anda telah diterima. Silakan unggah dokumen persyaratan (KTP, Pas Foto, SKCK, Surat Sehat, dan dokumen pendukung lainnya).

<x-mail::button :url="URL::signedRoute('application.upload', ['application' => $application->id])">
Unggah Dokumen
</x-mail::button>
@elseif($application->status === 'revisi_dokumen' || $application->documents->where('status', 'Revisi')->isNotEmpty())
Beberapa dokumen persyaratan Anda perlu diperbaiki. Silakan cek catatan dari tim kami dan unggah ulang dokumen yang sesuai.

<x-mail::button :url="URL::signedRoute('application.upload', ['application' => $application->id])">
Perbaiki Dokumen
</x-mail::button>
@elseif($application->status === 'document_review')
Dokumen yang Anda unggah sedang dalam proses peninjauan. Kami akan memberitahu Anda setelah peninjauan selesai.
@elseif($application->status === 'accepted')
Anda telah terdaftar sebagai peserta Internship Program di Lembaga Pelatihan Kerja Paiton Selaras.

@if($application->tingkat_pendidikan === 'Mahasiswa')
Sebagai mahasiswa, tahap wawancara diwajibkan. Hubungi admin melalui WhatsApp di **+62 811-3059-8801** untuk mengatur jadwal wawancara.
@else
Jadwal masuk Anda mengikuti periode gelombang yang telah dipilih.
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
Pendaftaran Anda tidak dapat kami terima.

@if(!empty($note))
**Catatan:**
{{ $note }}
@endif
@endif

Terima kasih,<br>
Lembaga Pelatihan Kerja Paiton Selaras - Internship Program
</x-mail::message>
