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

@if($application)
    @foreach($expectedDocs as $docType => $docLabel)
        @php
            $doc = $existingDocs->first(function($d) use ($docType) {
                return str_starts_with($d->original_name, $docType . ' -');
            });
        @endphp
        @if($doc)
            <!-- Modal Reupload -->
            <div class="modal fade" id="reuploadModal{{ $doc->id }}" tabindex="-1" aria-labelledby="reuploadModalLabel{{ $doc->id }}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="reuploadModalLabel{{ $doc->id }}">Upload Ulang Dokumen</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ $isPortal ? route('portal.dokumen.reupload', $doc->id) : URL::signedRoute('application.dokumen.reupload', ['application' => $application->id, 'document' => $doc->id]) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-body text-start">
                                <div class="alert alert-warning small mb-3">
                                    <strong>Peringatan:</strong> File dokumen lama Anda akan dihapus dan digantikan secara permanen dengan file baru ini.
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Pilih File Baru ({{ $doc->original_name }})</label>
                                    <input type="file" class="form-control" name="dokumen_baru" accept=".pdf,.jpg,.jpeg,.png" required>
                                    <div class="form-text">Format: PDF, JPG, PNG. Maksimal 5MB.</div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Simpan Dokumen</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @else
            <!-- Modal Upload New -->
            <div class="modal fade" id="uploadNewModal_{{ Str::slug($docType) }}" tabindex="-1" aria-labelledby="uploadNewLabel_{{ Str::slug($docType) }}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="uploadNewLabel_{{ Str::slug($docType) }}">Upload {{ $docLabel }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ $isPortal ? route('portal.dokumen.upload-missing') : URL::signedRoute('application.dokumen.upload-missing', ['application' => $application->id]) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="document_type" value="{{ $docType }}">
                            <div class="modal-body text-start">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Pilih File</label>
                                    <input type="file" class="form-control" name="dokumen_baru" accept=".pdf,.jpg,.jpeg,.png" required>
                                    <div class="form-text">Format: PDF, JPG, PNG. Maksimal 5MB.</div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Upload Dokumen</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
@endif
