{{-- resources/views/pages/persil/create.blade.php --}}
@extends('layouts.admin.app')

@section('title', 'Tambah Data Persil')
@section('page_title', 'Tambah Data Persil')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0 fw-bold">
                            <i class="fas fa-map text-primary me-2"></i>Tambah Data Persil
                        </h4>
                        <small class="text-muted">Lengkapi form di bawah untuk menambahkan data persil baru</small>
                    </div>
                    <a href="{{ route('persil.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('persil.store') }}" method="POST" enctype="multipart/form-data" id="persilForm">
                    @csrf

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            @foreach($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
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
                                <div class="col-md-6 mb-3">
                                    <label for="kode_persil" class="form-label">Kode Persil <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('kode_persil') is-invalid @enderror"
                                           id="kode_persil" name="kode_persil" value="{{ old('kode_persil') }}"
                                           placeholder="Contoh: PSL-001" required>
                                    @error('kode_persil')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
    <label for="pemilik_warga_id" class="form-label">Pemilik <span class="text-danger">*</span></label>
    <select class="form-select @error('pemilik_warga_id') is-invalid @enderror"
            id="pemilik_warga_id" name="pemilik_warga_id" required>
        <option value="">Pilih pemilik</option>
        @foreach ($wargaOptions as $warga)
            @php
                $selected = old('pemilik_warga_id') == $warga->warga_id ? 'selected' : '';
            @endphp
            <option value="{{ $warga->warga_id }}" {{ $selected }}>
                {{ $warga->nama }} - {{ $warga->no_ktp }}
            </option>
        @endforeach
    </select>
    @error('pemilik_warga_id')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
    <small class="text-muted">ID yang dipilih: <span id="selectedWargaId"></span></small>
</div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="luas_m2" class="form-label">Luas (m²) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" class="form-control @error('luas_m2') is-invalid @enderror"
                                           id="luas_m2" name="luas_m2" value="{{ old('luas_m2') }}"
                                           placeholder="Masukkan luas" required>
                                    @error('luas_m2')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="penggunaan" class="form-label">Penggunaan <span class="text-danger">*</span></label>
                                    <select class="form-select @error('penggunaan') is-invalid @enderror"
                                            id="penggunaan" name="penggunaan" required>
                                        <option value="">Pilih penggunaan</option>
                                        @foreach($jenisPenggunaanOptions as $jenis)
                                            @php
                                                $selected = old('penggunaan') == $jenis->nama_penggunaan ? 'selected' : '';
                                            @endphp
                                            <option value="{{ $jenis->nama_penggunaan }}" {{ $selected }}>
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
                    </div>

                    <!-- Lokasi Persil -->
                    <div class="card mb-4 border">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">
                                <i class="fas fa-map-marker-alt me-2"></i>Lokasi Persil
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="alamat_lahan" class="form-label">Alamat Lahan <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('alamat_lahan') is-invalid @enderror"
                                          id="alamat_lahan" name="alamat_lahan" rows="2"
                                          placeholder="Masukkan alamat lengkap" required>{{ old('alamat_lahan') }}</textarea>
                                @error('alamat_lahan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="rt" class="form-label">RT <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('rt') is-invalid @enderror"
                                           id="rt" name="rt" value="{{ old('rt') }}"
                                           placeholder="Contoh: 001" required>
                                    @error('rt')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="rw" class="form-label">RW <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('rw') is-invalid @enderror"
                                           id="rw" name="rw" value="{{ old('rw') }}"
                                           placeholder="Contoh: 002" required>
                                    @error('rw')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="koordinat" class="form-label">Koordinat (JSON)</label>
                                    <input type="text" class="form-control @error('koordinat') is-invalid @enderror"
                                           id="koordinat" name="koordinat" value="{{ old('koordinat') }}"
                                           placeholder='{"lat": -7.123456, "lng": 110.123456}'>
                                    @error('koordinat')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Upload File -->
                    <div class="card mb-4 border">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">
                                <i class="fas fa-file-upload me-2"></i>Upload File Pendukung
                            </h5>
                        </div>
                        <div class="card-body">
                            <!-- Foto Bidang -->
                            <div class="mb-4">
                                <label for="foto_bidang" class="form-label">Foto Bidang Tanah</label>
                                <input type="file" class="form-control @error('foto_bidang.*') is-invalid @enderror"
                                       id="foto_bidang" name="foto_bidang[]" multiple
                                       accept=".jpg,.jpeg,.png,.gif">
                                <small class="text-muted">Format: JPG, PNG, GIF | Maksimal 2MB per file</small>
                                <div id="fotoBidangList" class="mt-2 small"></div>
                                @error('foto_bidang.*')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- File Koordinat -->
                            <div class="mb-4">
                                <label for="koordinat_files" class="form-label">File Pendukung Koordinat</label>
                                <input type="file" class="form-control @error('koordinat_files.*') is-invalid @enderror"
                                       id="koordinat_files" name="koordinat_files[]" multiple
                                       accept=".jpg,.jpeg,.png,.pdf,.doc,.docx,.xls,.xlsx">
                                <small class="text-muted">Format: JPG, PNG, PDF, DOC, XLS | Maksimal 2MB per file</small>
                                <div id="koordinatFilesList" class="mt-2 small"></div>
                                @error('koordinat_files.*')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="alert alert-info py-2">
                                <small>
                                    <i class="fas fa-info-circle me-1"></i>
                                    Maksimal 2MB per file, total maksimal 10 file semua jenis
                                </small>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="d-flex justify-content-between border-top pt-4">
                        <a href="{{ route('persil.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-1"></i> Batal
                        </a>
                        <div>
                            <button type="reset" class="btn btn-outline-warning me-2">
                                <i class="fas fa-redo me-1"></i> Reset
                            </button>
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="fas fa-save me-1"></i> Simpan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection


