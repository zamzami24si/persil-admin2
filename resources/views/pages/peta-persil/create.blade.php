@extends('layouts.admin.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Tambah Peta Persil: {{ $persil->kode_persil }}</h4>
                </div>
                <div class="card-body">

                    {{-- Alert Messages --}}
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show"><i class="fas fa-check-circle me-2"></i>{{ session('success') }} <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show"><i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }} <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
                    @endif
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show"><ul class="mb-0">@foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul> <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
                    @endif

                    <form action="{{ route('peta-persil.store', $persil->persil_id) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="panjang_m" class="form-label">Panjang (meter)</label>
                                <input type="number" step="0.01" class="form-control" id="panjang_m" name="panjang_m" value="{{ old('panjang_m') }}" placeholder="0.00" min="0">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="lebar_m" class="form-label">Lebar (meter)</label>
                                <input type="number" step="0.01" class="form-control" id="lebar_m" name="lebar_m" value="{{ old('lebar_m') }}" placeholder="0.00" min="0">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="geojson" class="form-label">Data GeoJSON</label>
                            <textarea class="form-control font-monospace" id="geojson" name="geojson" rows="6" placeholder='{"type": "Feature", ...}'>{{ old('geojson') }}</textarea>
                            <div class="form-text">Masukkan kode GeoJSON yang valid.</div>
                        </div>

                        {{-- Upload File (Desain Standar) --}}
                        <div class="card mb-4 border">
                            <div class="card-header bg-light"><h5 class="mb-0">Upload File Peta</h5></div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Pilih File Peta/Scan</label>
                                    <input type="file" class="form-control" name="peta_files[]" multiple accept=".jpg,.jpeg,.png,.pdf,.svg">
                                    <div class="form-text">Format: JPG, PNG, PDF, SVG. Maks 5MB.</div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('peta-persil.index') }}" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Simpan Peta</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
