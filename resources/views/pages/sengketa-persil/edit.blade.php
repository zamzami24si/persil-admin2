{{-- resources/views/pages/sengketa-persil/edit.blade.php --}}
@extends('layouts.admin.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Edit Sengketa Persil: {{ $sengketa->persil->kode_persil }}</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('sengketa-persil.update', $sengketa->sengketa_id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

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
                                    <label for="pihak_1" class="form-label">Pihak 1 <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('pihak_1') is-invalid @enderror"
                                        id="pihak_1" name="pihak_1" value="{{ old('pihak_1', $sengketa->pihak_1) }}"
                                        placeholder="Masukkan nama pihak pertama" required maxlength="200">
                                    @error('pihak_1')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="pihak_2" class="form-label">Pihak 2 <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('pihak_2') is-invalid @enderror"
                                        id="pihak_2" name="pihak_2" value="{{ old('pihak_2', $sengketa->pihak_2) }}"
                                        placeholder="Masukkan nama pihak kedua" required maxlength="200">
                                    @error('pihak_2')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="kronologi" class="form-label">Kronologi Sengketa <span
                                    class="text-danger">*</span></label>
                            <textarea class="form-control @error('kronologi') is-invalid @enderror"
                                id="kronologi" name="kronologi" rows="5"
                                placeholder="Jelaskan kronologi dan detail sengketa yang terjadi" required>{{ old('kronologi', $sengketa->kronologi) }}</textarea>
                            @error('kronologi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status Sengketa <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select @error('status') is-invalid @enderror"
                                        id="status" name="status" required>
                                        <option value="">Pilih Status</option>
                                        <option value="proses" {{ old('status', $sengketa->status) == 'proses' ? 'selected' : '' }}>Proses</option>
                                        <option value="selesai" {{ old('status', $sengketa->status) == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                        <option value="dibatalkan" {{ old('status', $sengketa->status) == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="penyelesaian" class="form-label">Penyelesaian</label>
                            <textarea class="form-control @error('penyelesaian') is-invalid @enderror"
                                id="penyelesaian" name="penyelesaian" rows="4"
                                placeholder="Jelaskan proses penyelesaian sengketa (jika sudah selesai)">{{ old('penyelesaian', $sengketa->penyelesaian) }}</textarea>
                            @error('penyelesaian')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- ===== FILE UPLOAD SECTION ===== -->
                        <div class="card mb-4">
                            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Upload Bukti Sengketa</h5>
                                <small class="text-muted">{{ $mediaFiles->count() }} file terupload</small>
                            </div>
                            <div class="card-body">
                                <!-- Tampilkan file yang sudah ada -->
                                @if($mediaFiles->count() > 0)
                                    <div class="mb-4">
                                        <h6>File Bukti Terupload:</h6>
                                        <div class="row">
                                            @foreach($mediaFiles as $media)
                                                <div class="col-md-4 mb-3">
                                                    <div class="card file-card">
                                                        <div class="card-body">
                                                            @if(in_array($media->mime_type, ['image/jpeg', 'image/jpg', 'image/png']))
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
                                                                <a href="{{ route('sengketa-persil.download', $media->media_id) }}"
                                                                   target="_blank"
                                                                   class="btn btn-sm btn-outline-primary">
                                                                    <i class="fas fa-eye"></i>
                                                                </a>
                                                                <a href="{{ route('sengketa-persil.download', $media->media_id) }}"
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

                                <!-- Form untuk upload file baru -->
                                <div class="mb-3">
                                    <label for="bukti_files" class="form-label">Upload File Bukti Baru</label>
                                    <div class="file-upload-area">
                                        <div class="dropzone border rounded p-5 text-center mb-3" id="buktiFilesDropzone">
                                            <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                                            <h5>Drop file bukti baru di sini</h5>
                                            <p class="text-muted">atau klik untuk memilih file</p>
                                            <button type="button" class="btn btn-outline-primary mt-2" onclick="document.getElementById('bukti_files').click()">
                                                <i class="fas fa-folder-open me-2"></i> Pilih File
                                            </button>
                                        </div>
                                        <input type="file" class="d-none @error('bukti_files.*') is-invalid @enderror"
                                            id="bukti_files" name="bukti_files[]" multiple
                                            accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                                        <div class="form-text">
                                            Upload file bukti sengketa baru (PDF, JPG, PNG, DOC). Maksimal 2MB per file.
                                        </div>
                                        <div id="buktiFilesPreview" class="mt-3"></div>
                                        @error('bukti_files.*')
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
                                        <p class="mb-1"><strong>Kode:</strong> {{ $sengketa->persil->kode_persil }}</p>
                                        <p class="mb-1"><strong>Pemilik:</strong> {{ $sengketa->persil->pemilik->nama }}</p>
                                        <p class="mb-1"><strong>Luas:</strong> {{ number_format($sengketa->persil->luas_m2, 2) }} m²</p>
                                        <p class="mb-0"><strong>Alamat:</strong> {{ Str::limit($sengketa->persil->alamat_lahan, 50) }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card bg-info text-white">
                                    <div class="card-body">
                                        <h6 class="card-title">Informasi Sengketa</h6>
                                        <p class="mb-1"><strong>Status Saat Ini:</strong>
                                            <span class="badge {{ $sengketa->status_badge_class }}">
                                                {{ $sengketa->status_label }}
                                            </span>
                                        </p>
                                        <p class="mb-1"><strong>Jumlah File Bukti:</strong> {{ $mediaFiles->count() }} file</p>
                                        <p class="mb-1"><strong>Dibuat:</strong> {{ $sengketa->created_at->format('d/m/Y H:i') }}</p>
                                        <p class="mb-0"><strong>Diupdate:</strong> {{ $sengketa->updated_at->format('d/m/Y H:i') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('sengketa-persil.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali ke Daftar Sengketa
                            </a>
                            <div>
                                <a href="{{ route('sengketa-persil.show', $sengketa->sengketa_id) }}" class="btn btn-info">
                                    <i class="fas fa-eye"></i> Lihat Detail
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Update Sengketa
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            setupFilePreview('bukti_files', 'buktiFilesPreview', 2 * 1024 * 1024, [
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
                    fileCount.innerHTML = `<i class="fas fa-file me-2"></i>${input.files.length} file baru dipilih`;
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
