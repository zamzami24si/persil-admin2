{{-- resources/views/pages/persil/edit.blade.php --}}
@extends('layouts.admin.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0 fw-bold">
                                <i class="fas fa-edit text-warning me-2"></i>Edit Data Persil: {{ $persil->kode_persil }}
                            </h4>
                            <small class="text-muted">Perbarui informasi persil dan upload file pendukung</small>
                        </div>
                        <a href="{{ route('persil.show', $persil->persil_id) }}" class="btn btn-sm btn-info">
                            <i class="fas fa-eye me-1"></i> Lihat Detail
                        </a>
                    </div>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('persil.update', $persil->persil_id) }}" method="POST" enctype="multipart/form-data" id="persilForm">
                        @csrf
                        @method('PUT')

                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-exclamation-circle me-2 fs-4"></i>
                                    <div>
                                        <strong class="mb-1">Terjadi kesalahan:</strong>
                                        <ul class="mb-0 mt-1 ps-3">
                                            @foreach ($errors->all() as $error)
                                                <li class="small">{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <!-- Informasi Dasar -->
                        <div class="card mb-4 border">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">
                                    <i class="fas fa-info-circle me-2"></i>Informasi Dasar
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="kode_persil" class="form-label fw-semibold">
                                                <i class="fas fa-hashtag me-1 text-primary"></i>
                                                Kode Persil <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="form-control @error('kode_persil') is-invalid @enderror"
                                                   id="kode_persil" name="kode_persil" value="{{ old('kode_persil', $persil->kode_persil) }}"
                                                   placeholder="Contoh: PSL-001" required maxlength="50">
                                            @error('kode_persil')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="pemilik_warga_id" class="form-label fw-semibold">
                                                <i class="fas fa-user me-1 text-primary"></i>
                                                Pemilik <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-select @error('pemilik_warga_id') is-invalid @enderror"
                                                    id="pemilik_warga_id" name="pemilik_warga_id" required
                                                    data-placeholder="Pilih pemilik">
                                                <option value=""></option>
                                                @foreach ($wargaOptions as $warga)
                                                    <option value="{{ $warga->warga_id }}" {{ old('pemilik_warga_id', $persil->pemilik_warga_id) == $warga->warga_id ? 'selected' : '' }}>
                                                        {{ $warga->nama }} - {{ $warga->no_ktp }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('pemilik_warga_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            @if($persil->pemilik_warga_id && !$wargaOptions->contains('warga_id', $persil->pemilik_warga_id))
                                                <div class="alert alert-warning mt-2 py-2">
                                                    <small>
                                                        <i class="fas fa-exclamation-triangle me-1"></i>
                                                        Pemilik dengan ID {{ $persil->pemilik_warga_id }} tidak ditemukan. Pilih pemilik baru.
                                                    </small>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="luas_m2" class="form-label fw-semibold">
                                                <i class="fas fa-ruler-combined me-1 text-primary"></i>
                                                Luas (m²) <span class="text-danger">*</span>
                                            </label>
                                            <input type="number" step="0.01" class="form-control @error('luas_m2') is-invalid @enderror"
                                                   id="luas_m2" name="luas_m2" value="{{ old('luas_m2', $persil->luas_m2) }}"
                                                   placeholder="Masukkan luas dalam meter persegi" required min="0">
                                            @error('luas_m2')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="penggunaan" class="form-label fw-semibold">
                                                <i class="fas fa-landmark me-1 text-primary"></i>
                                                Penggunaan <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-select @error('penggunaan') is-invalid @enderror"
                                                    id="penggunaan" name="penggunaan" required
                                                    data-placeholder="Pilih jenis penggunaan">
                                                <option value=""></option>
                                                @foreach($jenisPenggunaanOptions as $jenis)
                                                    <option value="{{ $jenis->nama_penggunaan }}"
                                                        {{ old('penggunaan', $persil->penggunaan) == $jenis->nama_penggunaan ? 'selected' : '' }}>
                                                        {{ $jenis->nama_penggunaan }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('penggunaan')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="alamat_lahan" class="form-label fw-semibold">
                                        <i class="fas fa-location-dot me-1 text-primary"></i>
                                        Alamat Lahan <span class="text-danger">*</span>
                                    </label>
                                    <textarea class="form-control @error('alamat_lahan') is-invalid @enderror"
                                              id="alamat_lahan" name="alamat_lahan" rows="3"
                                              placeholder="Masukkan alamat lengkap lahan (jalan, desa, kecamatan)" required>{{ old('alamat_lahan', $persil->alamat_lahan) }}</textarea>
                                    @error('alamat_lahan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="rt" class="form-label fw-semibold">
                                                <i class="fas fa-house me-1 text-primary"></i>
                                                RT <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="form-control @error('rt') is-invalid @enderror"
                                                   id="rt" name="rt" value="{{ old('rt', $persil->rt) }}"
                                                   placeholder="Contoh: 001" required maxlength="3">
                                            @error('rt')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="rw" class="form-label fw-semibold">
                                                <i class="fas fa-city me-1 text-primary"></i>
                                                RW <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="form-control @error('rw') is-invalid @enderror"
                                                   id="rw" name="rw" value="{{ old('rw', $persil->rw) }}"
                                                   placeholder="Contoh: 002" required maxlength="3">
                                            @error('rw')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ===== FILE UPLOAD SECTION ===== -->
                        <div class="card mb-4 border">
                            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">
                                    <i class="fas fa-file-upload me-2"></i>File Pendukung
                                </h5>
                                <small class="text-muted">{{ $mediaFiles->count() }} file terupload</small>
                            </div>
                            <div class="card-body">
                                <!-- Tampilkan file yang sudah ada -->
                                @if($mediaFiles->count() > 0)
                                    <div class="mb-4">
                                        <h6>File Terupload:</h6>
                                        <div class="row">
                                            @foreach($mediaFiles as $media)
                                                <div class="col-md-4 mb-3">
                                                    <div class="card file-card">
                                                        <div class="card-body">
                                                            @if(strpos($media->mime_type, 'image/') === 0)
                                                                <img src="{{ asset('storage/' . $media->file_url) }}"
                                                                     class="img-fluid rounded mb-2"
                                                                     style="max-height: 100px; object-fit: cover;"
                                                                     alt="{{ $media->caption }}">
                                                            @else
                                                                <div class="text-center py-3">
                                                                    @if($media->mime_type == 'application/pdf')
                                                                        <i class="fas fa-file-pdf fa-3x text-danger"></i>
                                                                    @elseif(in_array($media->mime_type, ['application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document']))
                                                                        <i class="fas fa-file-word fa-3x text-primary"></i>
                                                                    @elseif(in_array($media->mime_type, ['application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']))
                                                                        <i class="fas fa-file-excel fa-3x text-success"></i>
                                                                    @else
                                                                        <i class="fas fa-file fa-3x text-muted"></i>
                                                                    @endif
                                                                </div>
                                                            @endif
                                                            <p class="mb-1 small text-truncate" title="{{ $media->caption }}">
                                                                <strong>{{ $media->caption }}</strong>
                                                            </p>
                                                            <p class="mb-2 small text-muted">
                                                                {{ strtoupper(pathinfo($media->file_url, PATHINFO_EXTENSION)) }} •
                                                                {{ $media->created_at->format('d/m/Y') }}
                                                            </p>
                                                            <div class="d-flex justify-content-between">
                                                                <a href="{{ url('persil/' . $media->media_id . '/preview') }}"
                                                                   target="_blank"
                                                                   class="btn btn-sm btn-outline-primary">
                                                                    <i class="fas fa-eye"></i>
                                                                </a>
                                                                <a href="{{ url('persil/' . $media->media_id . '/download') }}"
                                                                   download
                                                                   class="btn btn-sm btn-outline-success">
                                                                    <i class="fas fa-download"></i>
                                                                </a>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox"
                                                                           name="delete_media[]"
                                                                           value="{{ $media->media_id }}"
                                                                           id="delete_{{ $media->media_id }}">
                                                                    <label class="form-check-label small text-danger"
                                                                           for="delete_{{ $media->media_id }}">
                                                                        Hapus
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <!-- Upload File Baru -->
                                <div class="mb-4">
                                    <label class="form-label fw-semibold">
                                        <i class="fas fa-plus-circle me-1 text-primary"></i>
                                        Upload File Baru
                                    </label>
                                    <div class="file-upload-area">
                                        <div class="dropzone border rounded p-5 text-center mb-3" id="fileUploadDropzone">
                                            <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                                            <h5>Drop file di sini</h5>
                                            <p class="text-muted">atau klik untuk memilih file</p>
                                            <button type="button" class="btn btn-outline-primary mt-2" onclick="document.getElementById('file_uploads').click()">
                                                <i class="fas fa-folder-open me-2"></i> Pilih File
                                            </button>
                                        </div>
                                        <input type="file" class="d-none @error('file_uploads.*') is-invalid @enderror"
                                               id="file_uploads" name="file_uploads[]" multiple
                                               accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.xls,.xlsx">
                                        <div class="form-text small">
                                            <i class="fas fa-info-circle me-1"></i>
                                            Format: JPG, PNG, GIF, PDF, DOC, XLS | Maksimal 2MB per file | Maksimal 10 file
                                        </div>
                                        <div id="fileUploadsPreview" class="mt-3"></div>
                                        @error('file_uploads.*')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- ===== END FILE UPLOAD SECTION ===== -->

                        <!-- Tombol Aksi -->
                        <div class="d-flex justify-content-between border-top pt-4 mt-3">
                            <a href="{{ route('persil.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-1"></i> Batal
                            </a>
                            <div>
                                <button type="reset" class="btn btn-outline-warning me-2">
                                    <i class="fas fa-redo me-1"></i> Reset Form
                                </button>
                                <button type="submit" class="btn btn-primary" id="submitBtn">
                                    <i class="fas fa-save me-1"></i> Update Data
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
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
.file-card {
    transition: all 0.3s;
    border: 1px solid #dee2e6;
}
.file-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}
.file-card .card-body {
    padding: 1rem;
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    // Initialize Select2
    $('#pemilik_warga_id, #penggunaan').select2({
        theme: 'bootstrap-5',
        width: '100%',
        allowClear: true,
        placeholder: function() {
            return $(this).data('placeholder');
        }
    });

    // Setup file preview untuk semua file
    setupFilePreview('file_uploads', 'fileUploadsPreview', 2 * 1024 * 1024, [
        'image/jpeg', 'image/jpg', 'image/png', 'image/gif',
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
    ]);
});

// Function untuk setup file preview
function setupFilePreview(inputId, previewId, maxSize, allowedTypes) {
    const input = document.getElementById(inputId);
    const preview = document.getElementById(previewId);
    const dropzone = document.getElementById('fileUploadDropzone');

    function updatePreview() {
        preview.innerHTML = '';

        if (input.files.length > 0) {
            const fileCount = document.createElement('div');
            fileCount.className = 'alert alert-info mb-3';
            fileCount.innerHTML = `<i class="fas fa-file me-2"></i>${input.files.length} file baru dipilih`;
            preview.appendChild(fileCount);
        }

        Array.from(input.files).forEach((file, index) => {
            const fileItem = document.createElement('div');
            fileItem.className = 'file-preview-item';

            // Get file icon
            let icon = 'fa-file';
            if (file.type.startsWith('image/')) {
                icon = 'fa-image';
            } else if (file.type === 'application/pdf') {
                icon = 'fa-file-pdf';
            } else if (file.type.includes('word')) {
                icon = 'fa-file-word';
            } else if (file.type.includes('excel') || file.type.includes('spreadsheet')) {
                icon = 'fa-file-excel';
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

    // Handle file input change
    input.addEventListener('change', function(e) {
        const files = e.target.files;
        const validFiles = [];
        const errors = [];

        // Validate each file
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

        // Show errors
        if (errors.length > 0) {
            alert('Error:\n' + errors.join('\n'));
            input.value = '';
            preview.innerHTML = '';
            return;
        }

        // Update preview
        const dataTransfer = new DataTransfer();
        validFiles.forEach(file => dataTransfer.items.add(file));
        input.files = dataTransfer.files;
        updatePreview();
    });

    // Drag and drop functionality
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

    // Click dropzone to open file dialog
    dropzone.addEventListener('click', () => {
        input.click();
    });
}

// Remove file from input
function removeFile(inputId, index) {
    const input = document.getElementById(inputId);
    const files = Array.from(input.files);
    files.splice(index, 1);

    const dataTransfer = new DataTransfer();
    files.forEach(file => dataTransfer.items.add(file));
    input.files = dataTransfer.files;

    // Trigger change event
    input.dispatchEvent(new Event('change'));
}

// Format file size
function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}
</script>
@endpush
