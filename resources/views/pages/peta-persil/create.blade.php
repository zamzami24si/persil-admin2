@extends('layouts.admin.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Tambah Peta Persil: {{ $persil->kode_persil }}</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('peta-persil.store', $persil->persil_id) }}" method="POST" enctype="multipart/form-data">
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
                                    <label for="panjang_m" class="form-label">Panjang (meter)</label>
                                    <input type="number" step="0.01" class="form-control @error('panjang_m') is-invalid @enderror"
                                        id="panjang_m" name="panjang_m" value="{{ old('panjang_m') }}"
                                        placeholder="Masukkan panjang dalam meter" min="0">
                                    @error('panjang_m')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="lebar_m" class="form-label">Lebar (meter)</label>
                                    <input type="number" step="0.01" class="form-control @error('lebar_m') is-invalid @enderror"
                                        id="lebar_m" name="lebar_m" value="{{ old('lebar_m') }}"
                                        placeholder="Masukkan lebar dalam meter" min="0">
                                    @error('lebar_m')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="geojson" class="form-label">Data GeoJSON</label>
                            <textarea class="form-control @error('geojson') is-invalid @enderror"
                                id="geojson" name="geojson" rows="6"
                                placeholder='Masukkan data GeoJSON (contoh: {"type": "Feature", "geometry": {...}})'>{{ old('geojson') }}</textarea>
                            <div class="form-text">
                                Format JSON untuk data geospasial. Kosongkan jika tidak ada data GeoJSON.
                            </div>
                            @error('geojson')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- ===== FILE UPLOAD SECTION ===== -->
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Upload File Peta/Scan</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="peta_files" class="form-label">File Peta/Scan</label>
                                    <div class="file-upload-area">
                                        <div class="dropzone border rounded p-5 text-center mb-3" id="petaFilesDropzone">
                                            <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                                            <h5>Drop file peta di sini</h5>
                                            <p class="text-muted">atau klik untuk memilih file</p>
                                            <button type="button" class="btn btn-outline-primary mt-2" onclick="document.getElementById('peta_files').click()">
                                                <i class="fas fa-folder-open me-2"></i> Pilih File
                                            </button>
                                        </div>
                                        <input type="file" class="d-none @error('peta_files.*') is-invalid @enderror"
                                            id="peta_files" name="peta_files[]" multiple
                                            accept=".jpg,.jpeg,.png,.pdf,.svg">
                                        <div class="form-text">
                                            Upload file peta atau scan (JPG, PNG, PDF, SVG). Maksimal 2MB per file.
                                        </div>
                                        <div id="petaFilesPreview" class="mt-3"></div>
                                        @error('peta_files.*')
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
                            <div class="col-md-6">
                                <div class="card bg-info text-white">
                                    <div class="card-body">
                                        <h6 class="card-title">Informasi Luas</h6>
                                        <p class="mb-1">Luas dari data persil: <strong>{{ number_format($persil->luas_m2, 2) }} m²</strong></p>
                                        <p class="mb-0" id="luas-dimensi-info">
                                            Luas dari dimensi: <strong>-</strong>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('persil.show', $persil->persil_id) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali ke Detail Persil
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan Peta
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Hitung luas otomatis dari panjang dan lebar
        document.addEventListener('DOMContentLoaded', function() {
            const panjangInput = document.getElementById('panjang_m');
            const lebarInput = document.getElementById('lebar_m');
            const luasInfo = document.getElementById('luas-dimensi-info');

            function hitungLuas() {
                const panjang = parseFloat(panjangInput.value) || 0;
                const lebar = parseFloat(lebarInput.value) || 0;
                const luas = panjang * lebar;

                if (panjang > 0 && lebar > 0) {
                    luasInfo.innerHTML = `Luas dari dimensi: <strong>${luas.toFixed(2)} m²</strong>`;
                } else {
                    luasInfo.innerHTML = `Luas dari dimensi: <strong>-</strong>`;
                }
            }

            panjangInput.addEventListener('input', hitungLuas);
            lebarInput.addEventListener('input', hitungLuas);

            // Setup file preview
            setupFilePreview('peta_files', 'petaFilesPreview', 2 * 1024 * 1024, [
                'image/jpeg', 'image/jpg', 'image/png',
                'application/pdf',
                'image/svg+xml'
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
                    } else if (file.type === 'image/svg+xml') {
                        icon = 'fa-file-image';
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
