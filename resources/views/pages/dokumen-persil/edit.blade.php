@extends('layouts.admin.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Edit Dokumen Persil: {{ $dokumen->nomor }}</h4>
                </div>
                <div class="card-body">

                    {{-- ===== ALERT SECTION ===== --}}
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    {{-- ======================== --}}

                    <form action="{{ route('dokumen-persil.update', $dokumen->dokumen_id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="jenis_dokumen" class="form-label">Jenis Dokumen <span class="text-danger">*</span></label>
                                    <select class="form-select @error('jenis_dokumen') is-invalid @enderror" id="jenis_dokumen" name="jenis_dokumen" required>
                                        <option value="">Pilih Jenis Dokumen</option>
                                        @foreach(['Sertifikat', 'Girik', 'AJB', 'SHM', 'SHGB', 'SHGU', 'SKT', 'Lainnya'] as $jenis)
                                            <option value="{{ $jenis }}" {{ old('jenis_dokumen', $dokumen->jenis_dokumen) == $jenis ? 'selected' : '' }}>
                                                {{ $jenis }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('jenis_dokumen')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nomor" class="form-label">Nomor Dokumen <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('nomor') is-invalid @enderror"
                                        id="nomor" name="nomor" value="{{ old('nomor', $dokumen->nomor) }}"
                                        placeholder="Masukkan nomor dokumen" required maxlength="100">
                                    @error('nomor')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <textarea class="form-control @error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan"
                                rows="4" placeholder="Masukkan keterangan tambahan">{{ old('keterangan', $dokumen->keterangan) }}</textarea>
                            @error('keterangan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="card mb-4 border">
                            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">File Dokumen</h5>
                                <small class="text-muted">{{ $mediaFiles->count() }} file terupload</small>
                            </div>
                            <div class="card-body">
                                @if ($mediaFiles->count() > 0)
                                    <div class="mb-4">
                                        <h6>File Terupload:</h6>
                                        <div class="row">
                                            @foreach ($mediaFiles as $media)
                                                <div class="col-md-4 mb-3">
                                                    <div class="card file-card h-100 border">
                                                        <div class="card-body text-center p-3">
                                                            @if (in_array($media->mime_type, ['image/jpeg', 'image/jpg', 'image/png']))
                                                                <a href="{{ route('dokumen-persil.preview', $media->media_id) }}" target="_blank">
                                                                    <img src="{{ asset('storage/' . $media->file_url) }}"
                                                                        class="img-fluid rounded mb-2"
                                                                        style="max-height: 100px; object-fit: cover;"
                                                                        alt="{{ $media->caption }}">
                                                                </a>
                                                            @else
                                                                <a href="{{ route('dokumen-persil.preview', $media->media_id) }}" target="_blank">
                                                                    <i class="fas fa-file-alt fa-3x text-secondary mb-2"></i>
                                                                </a>
                                                            @endif

                                                            <div class="small text-truncate fw-bold" title="{{ $media->caption }}">{{ $media->caption }}</div>
                                                            <div class="small text-muted mb-2">{{ strtoupper(pathinfo($media->file_url, PATHINFO_EXTENSION)) }}</div>

                                                            <div class="form-check d-flex justify-content-center">
                                                                <input class="form-check-input me-2" type="checkbox"
                                                                    name="delete_media[]" value="{{ $media->media_id }}"
                                                                    id="delete_{{ $media->media_id }}">
                                                                <label class="form-check-label small text-danger fw-bold"
                                                                    for="delete_{{ $media->media_id }}">
                                                                    Hapus
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <div class="mb-3">
                                    <label for="dokumen_files" class="form-label fw-bold">Upload File Dokumen Baru</label>
                                    <input type="file" class="form-control @error('dokumen_files.*') is-invalid @enderror"
                                        id="dokumen_files" name="dokumen_files[]" multiple
                                        accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                                    <div class="form-text">Maksimal 5MB per file.</div>
                                    <div id="dokumenFilesPreview" class="mt-3 row g-2"></div>
                                    @error('dokumen_files.*')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('persil.show', $dokumen->persil_id) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .file-card { transition: all 0.3s; }
        .file-card:hover { transform: translateY(-2px); box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); }
        .preview-item { border: 1px solid #ddd; padding: 5px; border-radius: 4px; background: #f9f9f9; display: flex; align-items: center; gap: 10px; }
    </style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    setupFilePreview('dokumen_files', 'dokumenFilesPreview');
});

function setupFilePreview(inputId, previewContainerId) {
    const input = document.getElementById(inputId);
    const container = document.getElementById(previewContainerId);
    if (!input || !container) return;

    input.addEventListener('change', function(e) {
        container.innerHTML = '';
        Array.from(e.target.files).forEach(file => {
            const col = document.createElement('div');
            col.className = 'col-md-6';
            col.innerHTML = `
                <div class="preview-item">
                    <i class="fas fa-file fa-lg text-muted"></i>
                    <div class="text-truncate flex-fill ms-2 fw-bold" style="font-size: 0.9rem;">${file.name}</div>
                    <div class="small text-muted">${(file.size/1024).toFixed(2)} KB</div>
                </div>
            `;
            container.appendChild(col);
        });
    });
}
</script>
@endpush
