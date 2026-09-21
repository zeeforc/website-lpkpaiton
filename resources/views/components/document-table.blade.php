@php
    $expectedDocs = [
        'KTP/Kartu Pelajar' => 'KTP/Kartu Pelajar',
        'Pas Foto 4x6' => 'Pas Foto 4x6',
        'SKCK' => 'Surat Kelakuan Baik / SKCK',
        'Surat Sehat' => 'Surat Keterangan Sehat',
        'Portofolio' => 'Portofolio',
        'Dokumen Tambahan' => 'Dokumen Tambahan (Opsional)'
    ];
    $existingDocs = optional($application)->documents ?? collect();
    $isPortal = request()->is('portal/*');
@endphp

<div class="table-responsive">
    <table class="table table-hover mb-0">
        <thead class="table-light">
            <tr>
                <th>Nama Dokumen</th>
                <th class="text-end">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @if(!$application)
            <tr>
                <td colspan="2" class="text-center text-secondary py-4">
                    Data pendaftaran Anda tidak ditemukan di sistem. Anda tidak dapat mengunggah dokumen.
                </td>
            </tr>
            @else
                @foreach($expectedDocs as $docType => $docLabel)
                    @php
                        // Find existing doc that starts with the $docType prefix
                        $doc = $existingDocs->first(function($d) use ($docType) {
                            return str_starts_with($d->original_name, $docType . ' -');
                        });
                    @endphp
                    <tr>
                        <td class="align-middle">
                            <div class="d-flex align-items-center gap-2">
                                <i class="fa-solid fa-file-pdf {{ $doc ? 'text-danger' : 'text-secondary' }} fs-4"></i>
                                <div>
                                    <div class="fw-semibold {{ $doc ? 'text-dark' : 'text-secondary' }} d-flex align-items-center gap-2">
                                        {{ $doc ? $doc->original_name : $docLabel }}
                                        @if($doc)
                                            @if($doc->status === 'Valid')
                                                <span class="badge bg-success" style="font-size: 0.7rem;">Valid</span>
                                            @elseif($doc->status === 'Revisi')
                                                <span class="badge bg-danger" style="font-size: 0.7rem;">Revisi</span>
                                            @else
                                                <span class="badge bg-warning text-dark" style="font-size: 0.7rem;">Menunggu Review</span>
                                            @endif
                                        @endif
                                    </div>
                                    @if($doc)
                                        <a href="{{ Storage::url($doc->file_path) }}" target="_blank" class="text-primary text-decoration-none" style="font-size: 0.85rem">Lihat File Saat Ini</a>
                                        @if($doc->status === 'Revisi' && !empty($doc->keterangan))
                                            <div class="mt-2 text-danger small bg-danger bg-opacity-10 p-2 rounded border border-danger border-opacity-25">
                                                <i class="fa-solid fa-triangle-exclamation me-1"></i> <strong>Catatan Admin:</strong> {{ $doc->keterangan }}
                                            </div>
                                        @endif
                                    @else
                                        <span class="text-danger" style="font-size: 0.85rem">Belum Diunggah</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-end">
                            @if($doc)
                                @if($doc->status !== 'Valid')
                                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#reuploadModal{{ $doc->id }}">
                                        <i class="fa-solid fa-cloud-arrow-up"></i> Upload Ulang
                                    </button>
                                @else
                                    <span class="text-success small fw-medium"><i class="fa-solid fa-check-circle me-1"></i> Dokumen Disetujui</span>
                                @endif
                            @else
                                <button type="button" class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#uploadNewModal_{{ Str::slug($docType) }}">
                                    <i class="fa-solid fa-upload"></i> Upload
                                </button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</div>


