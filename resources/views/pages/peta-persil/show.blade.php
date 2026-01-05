@extends('layouts.admin.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Detail Peta Persil: {{ $peta->persil->kode_persil }}</h4>
                    <div class="card-tools">
                        <a href="{{ route('peta-persil.edit', $peta->peta_id) }}" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('peta-persil.index') }}" class="btn btn-sm btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">

                    {{-- Alert Messages --}}
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show"><i class="fas fa-check-circle me-2"></i>{{ session('success') }} <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            <h5>Informasi Peta</h5>
                            <table class="table table-bordered">
                                <tr><th width="40%">Kode Persil</th><td>{{ $peta->persil->kode_persil }}</td></tr>
                                <tr><th>Pemilik</th><td>{{ $peta->persil->pemilik->nama ?? '-' }}</td></tr>
                                <tr><th>Panjang</th><td>{{ $peta->panjang_m ? $peta->panjang_m . ' m' : '-' }}</td></tr>
                                <tr><th>Lebar</th><td>{{ $peta->lebar_m ? $peta->lebar_m . ' m' : '-' }}</td></tr>
                                <tr><th>Luas (Dimensi)</th><td>{{ $peta->luas_dari_dimensi ? $peta->luas_dari_dimensi . ' m²' : '-' }}</td></tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Data GeoJSON</h5>
                            <div class="card bg-light">
                                <div class="card-body p-0">
                                    @if($peta->geojson)
                                        {{-- Tampilan GeoJSON Rapi --}}
                                        <pre class="m-0 p-3" style="max-height: 250px; overflow-y: auto; font-size: 0.8rem; background: #2d2d2d; color: #f8f8f2; border-radius: 4px;"><code>{{ json_encode($peta->geojson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</code></pre>
                                    @else
                                        <div class="p-3 text-center text-muted">
                                            <i class="fas fa-code fa-2x mb-2"></i><br>
                                            Tidak ada data GeoJSON
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- File Media --}}
                  @if($mediaFiles->count() > 0)
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header bg-success text-white">
                                        <h5 class="mb-0">
                                            <i class="fas fa-file-upload me-2"></i>
                                            File Peta/Scan ({{ $mediaFiles->count() }})
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            @foreach($mediaFiles as $media)
                                                <div class="col-md-3 mb-4">
                                                    <div class="card h-100 border shadow-sm">
                                                        {{-- LOGIC TAMPILAN GAMBAR --}}
                                                        <div class="card-body p-0 d-flex align-items-center justify-content-center bg-light" style="height: 200px; overflow: hidden;">
                                                            @if(in_array($media->mime_type, ['image/jpeg', 'image/jpg', 'image/png']))
                                                                {{-- Gunakan route preview agar gambar PASTI muncul --}}
                                                                <img src="{{ route('peta-persil.preview', $media->media_id) }}"
                                                                     class="img-fluid"
                                                                     style="width: 100%; height: 100%; object-fit: cover;"
                                                                     alt="{{ $media->caption }}">
                                                            @elseif($media->mime_type == 'image/svg+xml')
                                                                <img src="{{ route('peta-persil.preview', $media->media_id) }}"
                                                                     class="img-fluid p-3"
                                                                     style="max-height: 100%;"
                                                                     alt="SVG Peta">
                                                            @elseif($media->mime_type == 'application/pdf')
                                                                <div class="text-center">
                                                                    <i class="fas fa-file-pdf fa-4x text-danger mb-2"></i>
                                                                    <p class="mb-0 fw-bold small text-muted">File PDF</p>
                                                                </div>
                                                            @else
                                                                <div class="text-center">
                                                                    <i class="fas fa-file fa-4x text-muted mb-2"></i>
                                                                    <p class="mb-0 fw-bold small text-muted">Dokumen</p>
                                                                </div>
                                                            @endif
                                                        </div>

                                                        {{-- FOOTER KARTU --}}
                                                        <div class="card-footer bg-white p-3">
                                                            <h6 class="card-title text-truncate mb-1" title="{{ $media->caption }}">
                                                                {{ $media->caption }}
                                                            </h6>
                                                            <p class="card-text small text-muted mb-3">
                                                                {{ strtoupper(pathinfo($media->file_url, PATHINFO_EXTENSION)) }} •
                                                                {{ $media->created_at->format('d/m/Y') }}
                                                            </p>

                                                            {{-- Tombol Aksi --}}
                                                            <div class="d-flex gap-2">
                                                                <a href="{{ route('peta-persil.preview', $media->media_id) }}"
                                                                   target="_blank"
                                                                   class="btn btn-sm btn-outline-primary flex-fill">
                                                                    <i class="fas fa-eye me-1"></i> Lihat
                                                                </a>
                                                                <a href="{{ route('peta-persil.download', $media->media_id) }}"
                                                                   class="btn btn-sm btn-outline-success flex-fill">
                                                                    <i class="fas fa-download me-1"></i> Unduh
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-warning mt-4">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Tidak ada file peta/scan terupload untuk persil ini.
                            <a href="{{ route('peta-persil.edit', $peta->peta_id) }}" class="alert-link">
                                Klik di sini untuk menambahkan file
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
