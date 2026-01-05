{{-- resources/views/pages/dokumen-persil/create.blade.php --}}
@extends('layouts.admin.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Tambah Dokumen Persil: {{ $persil->kode_persil }}</h4>
                </div>
                <div class="card-body">

                    {{-- ===== ALERT SECTION (Sama seperti Edit) ===== --}}
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
                    {{-- ============================================= --}}

                    <form action="{{ route('dokumen-persil.store', $persil->persil_id) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="jenis_dokumen" class="form-label">Jenis Dokumen <span class="text-danger">*</span></label>
                                    <select class="form-select @error('jenis_dokumen') is-invalid @enderror"
                                        id="jenis_dokumen" name="jenis_dokumen" required>
                                        <option value="">Pilih Jenis Dokumen</option>
                                        @foreach(['Sertifikat', 'Girik', 'AJB', 'SHM', 'SHGB', 'SHGU', 'SKT', 'Lainnya'] as $jenis)
                                            <option value="{{ $jenis }}" {{ old('jenis_dokumen') == $jenis ? 'selected' : '' }}>
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
                                        id="nomor" name="nomor" value="{{ old('nomor') }}"
                                        placeholder="Masukkan nomor dokumen" required maxlength="100">
                                    @error('nomor')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <textarea class="form-control @error('keterangan') is-invalid @enderror"
                                id="keterangan" name="keterangan" rows="4"
                                placeholder="Masukkan keterangan tambahan tentang dokumen">{{ old('keterangan') }}</textarea>
                            @error('keterangan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="card mb-4 border">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Upload File Dokumen</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="dokumen_files" class="form-label fw-bold">Pilih File</label>

                                    {{-- Input Standar (Bukan Dropzone Besar) --}}
                                    <input type="file" class="form-control @error('dokumen_files.*') is-invalid @enderror"
                                        id="dokumen_files" name="dokumen_files[]" multiple
                                        accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">

                                    <div class="form-text">
                                        Format: PDF, JPG, PNG, DOC. Maksimal 5MB per file.
                                    </div>

                                    {{-- Area Preview --}}
                                    <div id="dokumenFilesPreview" class="mt-3 row g-2"></div>

                                    @error('dokumen_files.*')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title">Informasi Persil Terkait</h6>
                                        <p class="mb-1"><strong>Kode:</strong> {{ $persil->kode_persil }}</p>
                                        <p class="mb-1"><strong>Pemilik:</strong> {{ $persil->pemilik->nama ?? 'Tidak Ada' }}</p>
                                        <p class="mb-0"><strong>Alamat:</strong> {{ Str::limit($persil->alamat_lahan, 50) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('persil.show', $persil->persil_id) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan Dokumen
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
    /* Style sederhana untuk preview item */
    .preview-item {
        border: 1px solid #dee2e6;
        padding: 8px;
        border-radius: 4px;
        background: #f8f9fa;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .preview-item .file-info {
        flex: 1;
        overflow: hidden;
    }
    .preview-item .file-name {
        font-weight: 600;
        font-size: 0.9rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .preview-item .file-size {
        font-size: 0.75rem;
        color: #6c757d;
    }
    .cursor-pointer {
        cursor: pointer;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inisialisasi fungsi preview
    setupFilePreview('dokumen_files', 'dokumenFilesPreview');
});

function setupFilePreview(inputId, previewContainerId) {
    const input = document.getElementById(inputId);
    const container = document.getElementById(previewContainerId);

    // Batas ukuran file (5MB)
    const MAX_SIZE = 5 * 1024 * 1024;

    if (!input || !container) return;

    input.addEventListener('change', function(e) {
        // Reset preview container
        container.innerHTML = '';

        const files = Array.from(e.target.files);
        const validFiles = [];
        const errors = [];

        // Validasi ukuran file
        files.forEach(file => {
            if (file.size > MAX_SIZE) {
                errors.push(`File "${file.name}" terlalu besar (Max 5MB)`);
            } else {
                validFiles.push(file);
            }
        });

        // Tampilkan error jika ada
        if (errors.length > 0) {
            alert(errors.join('\n'));
            input.value = ''; // Reset input jika ada error
            return;
        }

        // Tampilkan preview
        validFiles.forEach((file, index) => {
            const col = document.createElement('div');
            col.className = 'col-md-6';

            // Tentukan ikon berdasarkan tipe file
            let icon = 'fa-file';
            let colorClass = 'text-secondary';

            if (file.type.startsWith('image/')) {
                icon = 'fa-file-image';
                colorClass = 'text-primary';
            } else if (file.type === 'application/pdf') {
                icon = 'fa-file-pdf';
                colorClass = 'text-danger';
            } else if (file.type.includes('word') || file.type.includes('doc')) {
                icon = 'fa-file-word';
                colorClass = 'text-primary';
            }

            col.innerHTML = `
                <div class="preview-item">
                    <i class="fas ${icon} fa-2x ${colorClass}"></i>
                    <div class="file-info ms-2">
                        <div class="file-name" title="${file.name}">${file.name}</div>
                        <div class="file-size">${formatFileSize(file.size)}</div>
                    </div>
                    {{-- Tombol Hapus (Visual Only - Reset Input) --}}
                    <button type="button" class="btn btn-sm text-danger" onclick="resetInput('${inputId}', '${previewContainerId}')">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
            container.appendChild(col);
        });
    });
}

// Helper: Format ukuran file
function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

// Helper: Reset Input jika user ingin membatalkan pemilihan
function resetInput(inputId, previewContainerId) {
    const input = document.getElementById(inputId);
    const container = document.getElementById(previewContainerId);

    input.value = ''; // Kosongkan input file
    container.innerHTML = ''; // Kosongkan preview
}
</script>
@endpush
