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
