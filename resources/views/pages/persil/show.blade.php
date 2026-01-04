{{-- resources/views/pages/persil/show.blade.php --}}
@extends('layouts.admin.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Detail Persil: {{ $persil->kode_persil }}</h4>
                    <div class="card-tools">
                        <a href="{{ route('persil.edit', $persil->persil_id) }}" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('persil.index') }}" class="btn btn-sm btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Informasi Persil (bagian sebelumnya) -->
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Informasi Persil</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th width="40%">Kode Persil</th>
                                    <td>{{ $persil->kode_persil }}</td>
                                </tr>
                               <tr>
    <th>Pemilik</th>
    <td>
        @if($persil->pemilik)
            {{ $persil->pemilik->nama }}
            <br>
            <small class="text-muted">{{ $persil->pemilik->no_ktp }}</small>
        @else
            <span class="text-danger">Data pemilik tidak ditemukan</span>
            <br>
            <small class="text-muted">ID: {{ $persil->pemilik_warga_id }}</small>
        @endif
    </td>
</tr>
                                <tr>
                                    <th>Luas</th>
                                    <td>{{ $persil->formatted_luas }}</td>
                                </tr>
                                <tr>
                                    <th>Penggunaan</th>
                                    <td>{{ $persil->penggunaan }}</td>
                                </tr>
                                <tr>
                                    <th>RT/RW</th>
                                    <td>{{ $persil->rtrw }}</td>
                                </tr>
                                <tr>
                                    <th>Jumlah File</th>
                                    <td>
                                        @if($persil->has_files)
                                            <span class="badge bg-primary">{{ $totalFiles }} file</span>
                                        @else
                                            <span class="badge bg-secondary">Tidak ada file</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Alamat Lahan</h5>
                            <div class="card bg-light">
                                <div class="card-body">
                                    <p class="mb-0">{{ $persil->alamat_lahan }}</p>
                                </div>
                            </div>

                            @if($persil->koordinat)
                                <h5 class="mt-4">Koordinat</h5>
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <pre class="mb-0" style="font-size: 0.875rem; max-height: 150px; overflow-y: auto;">
                                            {{ json_encode($persil->koordinat, JSON_PRETTY_PRINT) }}
                                        </pre>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Statistik File (bagian sebelumnya) -->
                    @if($persil->has_files)
                        <div class="row mt-4">
                            <div class="col-12">
                                <h5>Statistik File ({{ $totalFiles }})</h5>
                                <div class="row">
                                    <div class="col-md-3 col-6 mb-3">
                                        <div class="card bg-primary text-white">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <h4 class="mb-0">{{ $imageFiles }}</h4>
                                                        <small>Gambar</small>
                                                    </div>
                                                    <i class="fas fa-image fa-2x"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-6 mb-3">
                                        <div class="card bg-danger text-white">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <h4 class="mb-0">{{ $pdfFiles }}</h4>
                                                        <small>PDF</small>
                                                    </div>
                                                    <i class="fas fa-file-pdf fa-2x"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-6 mb-3">
                                        <div class="card bg-success text-white">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <h4 class="mb-0">{{ $docFiles }}</h4>
                                                        <small>Dokumen</small>
                                                    </div>
                                                    <i class="fas fa-file-word fa-2x"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-6 mb-3">
                                        <div class="card bg-info text-white">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <h4 class="mb-0">{{ $excelFiles }}</h4>
                                                        <small>Excel</small>
                                                    </div>
                                                    <i class="fas fa-file-excel fa-2x"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- File Media Section (bagian sebelumnya) -->
                    @if($persil->has_files)
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5>File Terupload ({{ $totalFiles }})</h5>
                                    <a href="{{ route('persil.edit', $persil->persil_id) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-upload"></i> Upload File Baru
                                    </a>
                                </div>

                                <div class="row">
                                    @foreach($mediaFiles as $media)
                                        <div class="col-md-3 mb-3">
                                            <div class="card h-100">
                                                @if(in_array($media->mime_type, ['image/jpeg', 'image/jpg', 'image/png', 'image/gif']))
                                                    <img src="{{ asset('storage/' . $media->file_url) }}"
                                                         class="card-img-top"
                                                         style="height: 150px; object-fit: cover;"
                                                         alt="{{ $media->caption }}">
                                                @else
                                                    <div class="card-body text-center py-4">
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
                                                <div class="card-body">
                                                    <h6 class="card-title text-truncate" title="{{ $media->caption }}">
                                                        {{ $media->caption }}
                                                    </h6>
                                                    <p class="card-text small text-muted mb-2">
                                                        {{ strtoupper(pathinfo($media->file_url, PATHINFO_EXTENSION)) }}
                                                        <br>
                                                        {{ \Carbon\Carbon::parse($media->created_at)->format('d/m/Y H:i') }}
                                                    </p>
                                                    <div class="d-flex justify-content-between">
                                                        <a href="{{ route('persil.preview', $media->media_id) }}"
                                                           target="_blank"
                                                           class="btn btn-sm btn-outline-primary">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="{{ route('persil.download', $media->media_id) }}"
                                                           class="btn btn-sm btn-outline-success">
                                                            <i class="fas fa-download"></i>
                                                        </a>
                                                        <span class="badge bg-secondary">
                                                            {{ $this->formatFileSize($media->file_size) }}
                                                        </span>
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
                            <i class="fas fa-info-circle"></i> Tidak ada file terupload untuk persil ini.
                            <a href="{{ route('persil.edit', $persil->persil_id) }}" class="alert-link">
                                Klik di sini untuk upload file.
                            </a>
                        </div>
                    @endif

                    <!-- ===== PETA PERSIL SECTION ===== -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5>Peta Persil</h5>
                                @if(!$persil->peta || $persil->peta->count() == 0)
                                    <a href="{{ route('peta-persil.create', $persil->persil_id) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-plus"></i> Tambah Peta
                                    </a>
                                @else
                                    @php $peta = $persil->peta->first(); @endphp
                                    <div>
                                        <a href="{{ route('peta-persil.show', $peta->peta_id) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i> Lihat Detail Peta
                                        </a>
                                        <a href="{{ route('peta-persil.edit', $peta->peta_id) }}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i> Edit Peta
                                        </a>
                                    </div>
                                @endif
                            </div>

                            @if($persil->peta && $persil->peta->count() > 0)
                                @php
                                    $peta = $persil->peta->first();
                                    $petaMediaCount = \App\Models\Media::where('ref_table', 'peta_persil')
                                        ->where('ref_id', $peta->peta_id)
                                        ->count();
                                @endphp
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h6>Informasi Peta</h6>
                                                <p class="mb-1"><strong>Dimensi:</strong>
                                                    @if($peta->panjang_m && $peta->lebar_m)
                                                        {{ number_format($peta->panjang_m, 2) }} m × {{ number_format($peta->lebar_m, 2) }} m
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </p>
                                                <p class="mb-1"><strong>Luas dari Dimensi:</strong>
                                                    @if($peta->luas_dari_dimensi)
                                                        {{ number_format($peta->luas_dari_dimensi, 2) }} m²
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </p>
                                                <p class="mb-1"><strong>Jumlah File:</strong>
                                                    @if($petaMediaCount > 0)
                                                        <span class="badge bg-primary">{{ $petaMediaCount }} file</span>
                                                    @else
                                                        <span class="badge bg-secondary">Tidak ada file</span>
                                                    @endif
                                                </p>
                                                <p class="mb-0"><strong>GeoJSON:</strong>
                                                    @if($peta->geojson)
                                                        <span class="badge bg-success">Tersedia</span>
                                                    @else
                                                        <span class="badge bg-secondary">Tidak ada</span>
                                                    @endif
                                                </p>
                                            </div>
                                            <div class="col-md-6">
                                                @if($petaMediaCount > 0)
                                                    <h6>File Peta Terbaru</h6>
                                                    @php
                                                        $latestMedia = \App\Models\Media::where('ref_table', 'peta_persil')
                                                            ->where('ref_id', $peta->peta_id)
                                                            ->latest()
                                                            ->first();
                                                    @endphp
                                                    @if($latestMedia)
                                                        <div class="d-flex align-items-center p-2 bg-light rounded">
                                                            <div class="me-3">
                                                                @if(in_array($latestMedia->mime_type, ['image/jpeg', 'image/jpg', 'image/png']))
                                                                    <i class="fas fa-image fa-2x text-primary"></i>
                                                                @elseif($latestMedia->mime_type == 'application/pdf')
                                                                    <i class="fas fa-file-pdf fa-2x text-danger"></i>
                                                                @elseif($latestMedia->mime_type == 'image/svg+xml')
                                                                    <i class="fas fa-file-image fa-2x text-success"></i>
                                                                @else
                                                                    <i class="fas fa-file fa-2x text-muted"></i>
                                                                @endif
                                                            </div>
                                                            <div class="flex-grow-1">
                                                                <p class="mb-0 small"><strong>{{ $latestMedia->caption }}</strong></p>
                                                                <p class="mb-0 text-muted small">
                                                                    {{ strtoupper(pathinfo($latestMedia->file_url, PATHINFO_EXTENSION)) }} •
                                                                    {{ $latestMedia->created_at->format('d/m/Y') }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i> Belum ada data peta untuk persil ini.
                                    <a href="{{ route('peta-persil.create', $persil->persil_id) }}" class="alert-link">
                                        Klik di sini untuk menambahkan peta.
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                    <!-- ===== END PETA PERSIL SECTION ===== -->

                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
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
