@extends('layouts.admin.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Edit Peta Persil: {{ $peta->persil->kode_persil }}</h4>
                </div>
                <div class="card-body">

                    {{-- Alert Messages --}}
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show"><i class="fas fa-check-circle me-2"></i>{{ session('success') }} <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show"><i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }} <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
                    @endif

                    <form action="{{ route('peta-persil.update', $peta->peta_id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="panjang_m" class="form-label">Panjang (meter)</label>
                                <input type="number" step="0.01" class="form-control" id="panjang_m" name="panjang_m" value="{{ old('panjang_m', $peta->panjang_m) }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="lebar_m" class="form-label">Lebar (meter)</label>
                                <input type="number" step="0.01" class="form-control" id="lebar_m" name="lebar_m" value="{{ old('lebar_m', $peta->lebar_m) }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="geojson" class="form-label">Data GeoJSON</label>
                            <textarea class="form-control font-monospace" id="geojson" name="geojson" rows="8">{{ old('geojson', $peta->geojson ? json_encode($peta->geojson, JSON_PRETTY_PRINT) : '') }}</textarea>
                        </div>

                        {{-- File Management --}}
                        <div class="card mb-4 border">
                            <div class="card-header bg-light d-flex justify-content-between">
                                <h6 class="mb-0">File Peta Terupload</h6>
                                <small>{{ $mediaFiles->count() }} file</small>
                            </div>
                            <div class="card-body">
                                {{-- List Existing --}}
                                @if($mediaFiles->count() > 0)
                                    <div class="row g-3 mb-4">
                                        @foreach($mediaFiles as $media)
                                            <div class="col-md-3 col-6">
                                                <div class="card h-100 border">
                                                    <div class="card-body p-2 text-center bg-light d-flex align-items-center justify-content-center" style="height:120px">
                                                        @if(str_starts_with($media->mime_type, 'image/'))
                                                            <a href="{{ route('peta-persil.preview', $media->media_id) }}" target="_blank">
                                                                <img src="{{ asset('storage/' . $media->file_url) }}" class="img-fluid" style="max-height:100%" alt="Peta">
                                                            </a>
                                                        @else
                                                            <a href="{{ route('peta-persil.preview', $media->media_id) }}" target="_blank">
                                                                <i class="fas fa-file-alt fa-3x text-secondary"></i>
                                                            </a>
                                                        @endif
                                                    </div>
                                                    <div class="card-footer bg-white p-2 border-top-0">
                                                        <div class="text-truncate small mb-2">{{ $media->caption }}</div>
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input" type="checkbox" name="delete_media[]" value="{{ $media->media_id }}" id="del_{{$media->media_id}}">
                                                            <label class="form-check-label small text-danger fw-bold" for="del_{{$media->media_id}}">Hapus</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                                {{-- Upload New --}}
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Upload File Baru</label>
                                    <input type="file" class="form-control" name="peta_files[]" multiple accept=".jpg,.jpeg,.png,.pdf,.svg">
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('peta-persil.index') }}" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
