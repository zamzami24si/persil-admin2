@extends('layouts.admin.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom py-3">
                <h4 class="mb-0 fw-bold"><i class="fas fa-map text-primary me-2"></i>Tambah Data Persil</h4>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('persil.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Error:</strong>
                            <ul class="mb-0">@foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="card mb-4 border">
                        <div class="card-header bg-light"><h5 class="mb-0">Informasi Dasar</h5></div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Kode Persil <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="kode_persil" value="{{ old('kode_persil') }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Pemilik <span class="text-danger">*</span></label>
                                    <select class="form-select select2" name="pemilik_warga_id" required>
                                        <option value="">Pilih pemilik</option>
                                        @foreach ($wargaOptions as $warga)
                                            <option value="{{ $warga->warga_id }}" {{ old('pemilik_warga_id') == $warga->warga_id ? 'selected' : '' }}>{{ $warga->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Luas (m²) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" class="form-control" name="luas_m2" value="{{ old('luas_m2') }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Penggunaan</label>
                                    <select class="form-select select2" name="penggunaan" required>
                                        <option value="">Pilih penggunaan</option>
                                        @foreach($jenisPenggunaanOptions as $jenis)
                                            <option value="{{ $jenis->nama_penggunaan }}" {{ old('penggunaan') == $jenis->nama_penggunaan ? 'selected' : '' }}>{{ $jenis->nama_penggunaan }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4 border">
                        <div class="card-header bg-light"><h5 class="mb-0">Lokasi Persil</h5></div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Alamat Lahan <span class="text-danger">*</span></label>
                                <textarea class="form-control" name="alamat_lahan" rows="2" required>{{ old('alamat_lahan') }}</textarea>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">RT <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="rt" value="{{ old('rt') }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">RW <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="rw" value="{{ old('rw') }}" required>
                                </div>
                                </div>
                        </div>
                    </div>

                    <div class="card mb-4 border">
                        <div class="card-header bg-light"><h5 class="mb-0">Upload File Pendukung</h5></div>
                        <div class="card-body">
                            <div class="mb-4">
                                <label class="form-label fw-bold">Foto Bidang Tanah</label>
                                <input type="file" class="form-control" name="foto_bidang[]" multiple accept="image/*">
                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-bold">File Pendukung Koordinat / Dokumen</label>
                                <input type="file" class="form-control" name="koordinat_files[]" multiple>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between border-top pt-4">
                        <a href="{{ route('persil.index') }}" class="btn btn-outline-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('.select2').select2({ theme: 'bootstrap-5', width: '100%' });
    });
</script>
@endpush
