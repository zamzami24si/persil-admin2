@extends('layouts.admin.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3">
                    <h4 class="mb-0 fw-bold"><i class="fas fa-edit text-warning me-2"></i>Edit Persil: {{ $persil->kode_persil }}</h4>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('persil.update', $persil->persil_id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Kode Persil <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="kode_persil" value="{{ old('kode_persil', $persil->kode_persil) }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Pemilik</label>
                                <select class="form-select select2" name="pemilik_warga_id" required>
                                    @foreach($wargaOptions as $warga)
                                        <option value="{{ $warga->warga_id }}" {{ $persil->pemilik_warga_id == $warga->warga_id ? 'selected' : '' }}>
                                            {{ $warga->nama }} - {{ $warga->no_ktp }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Luas (m²)</label>
                                <input type="number" step="0.01" class="form-control" name="luas_m2" value="{{ old('luas_m2', $persil->luas_m2) }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Penggunaan</label>
                                <select class="form-select select2" name="penggunaan">
                                    @foreach($jenisPenggunaanOptions as $jenis)
                                        <option value="{{ $jenis->nama_penggunaan }}" {{ $persil->penggunaan == $jenis->nama_penggunaan ? 'selected' : '' }}>
                                            {{ $jenis->nama_penggunaan }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Alamat</label>
                                <textarea class="form-control" name="alamat_lahan" rows="2">{{ old('alamat_lahan', $persil->alamat_lahan) }}</textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">RT</label>
                                <input type="text" class="form-control" name="rt" value="{{ old('rt', $persil->rt) }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">RW</label>
                                <input type="text" class="form-control" name="rw" value="{{ old('rw', $persil->rw) }}">
                            </div>
                            </div>

                        <hr>

                        <h5 class="mb-3"><i class="fas fa-images me-2"></i>File Pendukung</h5>

                        @if($mediaFiles->count() > 0)
                            <div class="row g-3 mb-4">
                                @foreach($mediaFiles as $media)
                                <div class="col-md-3 col-6">
                                    <div class="card h-100 border">
                                        <div class="card-body p-2 text-center bg-light" style="height: 120px; overflow: hidden;">
                                            @if(str_starts_with($media->mime_type, 'image/'))
                                                <a href="{{ route('persil.preview', $media->media_id) }}" target="_blank">
                                                    <img src="{{ asset('storage/' . $media->file_url) }}" class="img-fluid" style="max-height: 100%;" alt="file">
                                                </a>
                                            @else
                                                <i class="fas fa-file-pdf fa-3x text-danger mt-3"></i>
                                            @endif
                                        </div>
                                        <div class="card-footer bg-white p-2 border-top-0">
                                            <div class="small text-truncate mb-2">{{ $media->caption }}</div>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" name="delete_media[]" value="{{ $media->media_id }}" id="del_{{ $media->media_id }}">
                                                <label class="form-check-label text-danger small fw-bold" for="del_{{ $media->media_id }}">Hapus File Ini</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @endif

                        <div class="mb-3">
                            <label class="form-label fw-bold">Tambah Foto Bidang</label>
                            <input type="file" class="form-control" name="foto_bidang[]" multiple accept="image/*">
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold">Tambah Dokumen/Koordinat</label>
                            <input type="file" class="form-control" name="koordinat_files[]" multiple>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('persil.index') }}" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Simpan Perubahan</button>
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
        $('.select2').select2({ theme: 'bootstrap-5' });
    });
</script>
@endpush
