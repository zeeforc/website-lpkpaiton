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
Sebagai mahasiswa, tahap selanjutnya adalah proses wawancara. Silakan hubungi Admin atau pihak penanggung jawab melalui WhatsApp untuk arahan proses Interview selanjutnya.
@else
Permohonan praktik kerja Anda telah disetujui. Silakan menunggu email konfirmasi lanjutan dari tim kami mengenai jadwal dan kapan Anda bisa mulai masuk.
@endif
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
