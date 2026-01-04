{{-- resources/views/pages/peta-persil/show.blade.php --}}
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
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Informasi Peta</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th width="40%">Kode Persil</th>
                                    <td>{{ $peta->persil->kode_persil }}</td>
                                </tr>
                                <tr>
                                    <th>Penilik</th>
                                    <td>{{ $peta->persil->penilik->nama }} ({{ $peta->persil->penilik->no_ktp }})</td>
                                </tr>
                                <tr>
                                    <th>Panjang</th>
                                    <td>
                                        @if($peta->panjang_m)
                                            {{ number_format($peta->panjang_m, 2) }} m
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Lebar</th>
                                    <td>
                                        @if($peta->lebar_m)
                                            {{ number_format($peta->lebar_m, 2) }} m
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Luas dari Dimensi</th>
                                    <td>
                                        @if($peta->luas_dari_dimensi)
                                            <strong>{{ number_format($peta->luas_dari_dimensi, 2) }} m²</strong>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Luas dari Data Persil</th>
                                    <td>{{ number_format($peta->persil->luas_m2, 2) }} m²</td>
                                </tr>
                                <tr>
                                    <th>Jumlah File</th>
                                    <td>
                                        @if($mediaFiles->count() > 0)
                                            <span class="badge bg-primary">{{ $mediaFiles->count() }} file</span>
                                        @else
                                            <span class="badge bg-secondary">Tidak ada file</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Data GeoJSON</th>
                                    <td>
                                        @if($peta->geojson)
                                            <span class="badge bg-success">Tersedia</span>
                                        @else
                                            <span class="badge bg-secondary">Tidak ada</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Data GeoJSON</h5>
                            @if($peta->geojson)
                                <div class="card">
                                    <div class="card-body">
                                        <pre class="mb-0" style="max-height: 300px; overflow-y: auto;"><code>{{ json_encode($peta->geojson, JSON_PRETTY_PRINT) }}</code></pre>
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i> Tidak ada data GeoJSON untuk peta ini.
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- ===== FILE MEDIA SECTION ===== -->
                    @if($mediaFiles->count() > 0)
                        <div class="row mt-4">
                            <div class="col-12">
                                <h5>File Peta/Scan ({{ $mediaFiles->count() }})</h5>
                                <div class="row">
                                    @foreach($mediaFiles as $media)
                                        <div class="col-md-3 mb-3">
                                            <div class="card h-100">
                                                @if(in_array($media->mime_type, ['image/jpeg', 'image/jpg', 'image/png']))
                                                    <img src="{{ asset('storage/' . $media->file_url) }}"
                                                         class="card-img-top"
                                                         style="height: 150px; object-fit: cover;"
                                                         alt="{{ $media->caption }}">
                                                @else
                                                    <div class="card-body text-center py-4">
                                                        @if($media->mime_type == 'application/pdf')
                                                            <i class="fas fa-file-pdf fa-3x text-danger"></i>
                                                        @elseif($media->mime_type == 'image/svg+xml')
                                                            <i class="fas fa-file-image fa-3x text-success"></i>
                                                        @else
                                                            <i class="fas fa-file fa-3x text-muted"></i>
                                                        @endif
                                                    </div>
                                                @endif
                                                <div class="card-body">
                                                    <h6 class="card-title text-truncate" title="{{ $media->caption }}">
                                                        {{ $media->caption }}
                                                    </h6>
                                                    <p class="card-text small text-muted mb-2">
                                                        {{ strtoupper(pathinfo($media->file_url, PATHINFO_EXTENSION)) }}
                                                        <br>
                                                        {{ $media->created_at->format('d/m/Y H:i') }}
                                                    </p>
                                                    <div class="d-flex justify-content-between">
                                                        <a href="{{ route('peta-persil.download', $media->media_id) }}"
                                                           target="_blank"
                                                           class="btn btn-sm btn-outline-primary">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="{{ route('peta-persil.download', $media->media_id) }}"
                                                           class="btn btn-sm btn-outline-success">
                                                            <i class="fas fa-download"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-info mt-4">
                            <i class="fas fa-info-circle"></i> Tidak ada file peta/scan terupload.
                        </div>
                    @endif
                    <!-- ===== END FILE MEDIA SECTION ===== -->

                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('persil.show', $peta->persil_id) }}" class="btn btn-info">
                                    <i class="fas fa-map"></i> Lihat Detail Persil
                                </a>
                                <div>
                                    <a href="{{ route('peta-persil.edit', $peta->peta_id) }}" class="btn btn-warning">
                                        <i class="fas fa-edit"></i> Edit Peta
                                    </a>
                                    <form action="{{ route('peta-persil.destroy', $peta->peta_id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger"
                                            onclick="return confirm('Yakin ingin menghapus data peta ini?')">
                                            <i class="fas fa-trash"></i> Hapus Peta
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
