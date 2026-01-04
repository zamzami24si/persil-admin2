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
                    <form action="{{ route('dokumen-persil.store', $persil->persil_id) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="jenis_dokumen" class="form-label">Jenis Dokumen <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select @error('jenis_dokumen') is-invalid @enderror"
                                        id="jenis_dokumen" name="jenis_dokumen" required>
                                        <option value="">Pilih Jenis Dokumen</option>
                                        <option value="Sertifikat" {{ old('jenis_dokumen') == 'Sertifikat' ? 'selected' : '' }}>Sertifikat</option>
                                        <option value="Girik" {{ old('jenis_dokumen') == 'Girik' ? 'selected' : '' }}>Girik</option>
                                        <option value="AJB" {{ old('jenis_dokumen') == 'AJB' ? 'selected' : '' }}>Akta Jual Beli (AJB)</option>
                                        <option value="SHM" {{ old('jenis_dokumen') == 'SHM' ? 'selected' : '' }}>Sertifikat Hak Milik (SHM)</option>
                                        <option value="SHGB" {{ old('jenis_dokumen') == 'SHGB' ? 'selected' : '' }}>Sertifikat Hak Guna Bangunan (SHGB)</option>
                                        <option value="SHGU" {{ old('jenis_dokumen') == 'SHGU' ? 'selected' : '' }}>Sertifikat Hak Guna Usaha (SHGU)</option>
                                        <option value="SKT" {{ old('jenis_dokumen') == 'SKT' ? 'selected' : '' }}>Surat Keterangan Tanah</option>
                                        <option value="Lainnya" {{ old('jenis_dokumen') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                    </select>
                                    @error('jenis_dokumen')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nomor" class="form-label">Nomor Dokumen <span
                                            class="text-danger">*</span></label>
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

                        <!-- ===== FILE UPLOAD SECTION ===== -->
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Upload File Dokumen</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="dokumen_files" class="form-label">File Dokumen</label>
                                    <div class="file-upload-area">
                                        <div class="dropzone border rounded p-5 text-center mb-3" id="dokumenFilesDropzone">
                                            <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                                            <h5>Drop file dokumen di sini</h5>
                                            <p class="text-muted">atau klik untuk memilih file</p>
                                            <button type="button" class="btn btn-outline-primary mt-2" onclick="document.getElementById('dokumen_files').click()">
                                                <i class="fas fa-folder-open me-2"></i> Pilih File
                                            </button>
                                        </div>
                                        <input type="file" class="d-none @error('dokumen_files.*') is-invalid @enderror"
                                            id="dokumen_files" name="dokumen_files[]" multiple
                                            accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                                        <div class="form-text">
                                            Upload file dokumen pendukung (PDF, JPG, PNG, DOC). Maksimal 5MB per file.
                                        </div>
                                        <div id="dokumenFilesPreview" class="mt-3"></div>
                                        @error('dokumen_files.*')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- ===== END FILE UPLOAD SECTION ===== -->

                        <div class="row">
                            <div class="col-md-6">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title">Informasi Persil</h6>
                                        <p class="mb-1"><strong>Kode:</strong> {{ $persil->kode_persil }}</p>
                                        <p class="mb-1"><strong>Pemilik:</strong> {{ $persil->pemilik->nama }}</p>
                                        <p class="mb-1"><strong>Luas:</strong> {{ number_format($persil->luas_m2, 2) }} m²</p>
                                        <p class="mb-0"><strong>Alamat:</strong> {{ Str::limit($persil->alamat_lahan, 50) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('persil.show', $persil->persil_id) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali ke Detail Persil
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
.dropzone {
    border: 2px dashed #ccc;
    transition: all 0.3s;
    cursor: pointer;
    background-color: #f8f9fa;
}
.dropzone:hover, .dropzone.dragover {
    border-color: #0d6efd;
    background-color: rgba(13, 110, 253, 0.05);
}
.file-preview-item {
    display: flex;
    align-items: center;
    padding: 10px;
    border: 1px solid #dee2e6;
    border-radius: 5px;
    margin-bottom: 5px;
    background: white;
}
.file-preview-icon {
    margin-right: 10px;
    color: #6c757d;
}
.file-preview-info {
    flex: 1;
}
.file-preview-name {
    font-weight: 500;
    font-size: 0.9rem;
}
.file-preview-size {
    font-size: 0.8rem;
    color: #6c757d;
}
.file-preview-remove {
    color: #dc3545;
    cursor: pointer;
}
.file-preview-remove:hover {
    color: #bb2d3b;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    setupFilePreview('dokumen_files', 'dokumenFilesPreview', 5 * 1024 * 1024, [
        'application/pdf',
        'image/jpeg', 'image/jpg', 'image/png',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
    ]);
});

function setupFilePreview(inputId, previewId, maxSize, allowedTypes) {
    const input = document.getElementById(inputId);
    const preview = document.getElementById(previewId);
    const dropzone = document.getElementById(inputId + 'Dropzone');

    function updatePreview() {
        preview.innerHTML = '';

        if (input.files.length > 0) {
            const fileCount = document.createElement('div');
            fileCount.className = 'alert alert-info mb-3';
            fileCount.innerHTML = `<i class="fas fa-file me-2"></i>${input.files.length} file dipilih`;
            preview.appendChild(fileCount);
        }

        Array.from(input.files).forEach((file, index) => {
            const fileItem = document.createElement('div');
            fileItem.className = 'file-preview-item';

            let icon = 'fa-file';
            if (file.type.startsWith('image/')) {
                icon = 'fa-image';
            } else if (file.type === 'application/pdf') {
                icon = 'fa-file-pdf';
            } else if (file.type.includes('word')) {
                icon = 'fa-file-word';
            }

            fileItem.innerHTML = `
                <div class="file-preview-icon">
                    <i class="fas ${icon} fa-lg"></i>
                </div>
                <div class="file-preview-info">
                    <div class="file-preview-name">${file.name}</div>
                    <div class="file-preview-size">${formatFileSize(file.size)}</div>
                </div>
                <div class="file-preview-remove" onclick="removeFile('${inputId}', ${index})">
                    <i class="fas fa-times"></i>
                </div>
            `;
            preview.appendChild(fileItem);
        });
    }

    input.addEventListener('change', function(e) {
        const files = e.target.files;
        const validFiles = [];
        const errors = [];

        Array.from(files).forEach(file => {
            if (file.size > maxSize) {
                errors.push(`"${file.name}" melebihi ${maxSize / 1024 / 1024}MB`);
                return;
            }

            if (allowedTypes.length > 0 && !allowedTypes.includes(file.type)) {
                errors.push(`"${file.name}" format tidak didukung`);
                return;
            }

            validFiles.push(file);
        });

        if (errors.length > 0) {
            alert('Error:\n' + errors.join('\n'));
            input.value = '';
            preview.innerHTML = '';
            return;
        }

        const dataTransfer = new DataTransfer();
        validFiles.forEach(file => dataTransfer.items.add(file));
        input.files = dataTransfer.files;
        updatePreview();
    });

    ['dragover', 'dragenter'].forEach(eventName => {
        dropzone.addEventListener(eventName, (e) => {
            e.preventDefault();
            dropzone.classList.add('dragover');
        });
    });

    ['dragleave', 'dragend'].forEach(eventName => {
        dropzone.addEventListener(eventName, () => {
            dropzone.classList.remove('dragover');
        });
    });

    dropzone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropzone.classList.remove('dragover');
        input.files = e.dataTransfer.files;
        input.dispatchEvent(new Event('change'));
    });

    dropzone.addEventListener('click', () => {
        input.click();
    });
}

function removeFile(inputId, index) {
    const input = document.getElementById(inputId);
    const files = Array.from(input.files);
    files.splice(index, 1);

    const dataTransfer = new DataTransfer();
    files.forEach(file => dataTransfer.items.add(file));
    input.files = dataTransfer.files;
    input.dispatchEvent(new Event('change'));
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}
</script>
@endpush
