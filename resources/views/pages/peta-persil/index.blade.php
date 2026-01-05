{{-- resources/views/pages/peta-persil/index.blade.php --}}
@extends('layouts.admin.app')

@section('title', 'Bina Desa | Data Peta Persil')
@section('page_title', 'Data Peta Persil')

@section('content')
<div class="card">
<div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
        <h4 class="card-title mb-0 fw-bold">Daftar Peta Persil</h4>

        {{-- Dropdown Tambah Peta (Posisi Kanan & Warna Biru) --}}
        <div class="dropdown">
            <button class="btn btn-primary dropdown-toggle shadow-sm" type="button" id="addPetaDropdown"
                    data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-plus-circle me-2"></i>Tambah Peta
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="addPetaDropdown" style="min-width: 300px; max-height: 300px; overflow-y: auto;">
                <li><h6 class="dropdown-header text-uppercase small fw-bold">Pilih Persil</h6></li>
                @php
                    // Ambil persil yang belum memiliki peta
                    $persilOptions = \App\Models\Persil::with('pemilik')
                        ->whereDoesntHave('peta')
                        ->orderBy('kode_persil')
                        ->limit(10)
                        ->get();
                @endphp
                @if($persilOptions->count() > 0)
                    @foreach($persilOptions as $persil)
                        <li>
                            <a class="dropdown-item" href="{{ route('peta-persil.create', $persil->persil_id) }}">
                                <div class="d-flex flex-column">
                                    <span class="fw-bold text-primary">{{ $persil->kode_persil }}</span>
                                    <small class="text-muted">
                                        <i class="fas fa-user me-1"></i>{{ $persil->pemilik->nama }}
                                    </small>
                                    <small class="text-muted">
                                        <i class="fas fa-map-marker-alt me-1"></i>RT {{ $persil->rt }}/RW {{ $persil->rw }}
                                    </small>
                                </div>
                            </a>
                        </li>
                        @if(!$loop->last)
                            <li><hr class="dropdown-divider"></li>
                        @endif
                    @endforeach
                    <li><hr class="dropdown-divider"></li>
                @else
                    <li>
                        <a class="dropdown-item text-muted" href="#">
                            <div class="d-flex flex-column text-center py-2">
                                <i class="fas fa-check-circle text-success mb-1"></i>
                                <span>Semua persil sudah memiliki peta</span>
                            </div>
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                @endif
                <li>
                    <a class="dropdown-item text-primary fw-bold text-center" href="{{ route('persil.index') }}">
                        <i class="fas fa-search me-1"></i> Cari Persil Lainnya
                    </a>
                </li>
            </ul>
        </div>
    </div>    <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- SEARCH FORM --}}
        <form method="GET" action="{{ route('peta-persil.index') }}" class="mb-4">
            <div class="row g-3 align-items-end">
                <div class="col-md-6">
                    <label class="form-label">Pencarian</label>
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" value="{{ request('search') }}"
                            placeholder="Cari berdasarkan kode persil, pemilik, atau dimensi...">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Cari
                        </button>
                        @if (request('search'))
                            <a href="{{ route('peta-persil.index') }}"
                                class="btn btn-outline-secondary">
                                Reset
                            </a>
                        @endif
                    </div>
                </div>

                <div class="col-md-2">
                    <a href="{{ route('peta-persil.index') }}" class="btn btn-secondary w-100">
                        <i class="fas fa-refresh"></i> Reset
                    </a>
                </div>

                @if (request('search'))
                    <div class="col-12">
                        <div class="alert alert-info py-2">
                            <small>
                                <i class="fas fa-info-circle me-1"></i>
                                Filter aktif:
                                <span class="badge bg-primary me-2">
                                    Pencarian: "{{ request('search') }}"
                                </span>
                            </small>
                        </div>
                    </div>
                @endif
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Persil</th>
                        <th>Pemilik</th>
                        <th>Dimensi</th>
                        <th>Luas dari Dimensi</th>
                        <th>GeoJSON</th>
                        <th>Jumlah File</th>
                        <th>Tanggal Dibuat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($peta as $item)
                        <tr>
                            <td>{{ ($peta->currentPage() - 1) * $peta->perPage() + $loop->iteration }}</td>
                            <td>
                                <strong>{{ $item->persil->kode_persil }}</strong>
                                <br>
                                <small class="text-muted">RT {{ $item->persil->rt }}/RW {{ $item->persil->rw }}</small>
                            </td>
                            <td>
                                {{ $item->persil->pemilik->nama }}
                                <br>
                                <small class="text-muted">{{ $item->persil->pemilik->no_ktp }}</small>
                            </td>
                            <td>
                                @if($item->panjang_m && $item->lebar_m)
                                    {{ number_format($item->panjang_m, 2) }} m × {{ number_format($item->lebar_m, 2) }} m
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($item->luas_dari_dimensi)
                                    {{ number_format($item->luas_dari_dimensi, 2) }} m²
                                    <br>
                                    <small class="text-muted">
                                        (vs {{ number_format($item->persil->luas_m2, 2) }} m² di data persil)
                                    </small>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($item->geojson)
                                    <span class="badge bg-success">Tersedia</span>
                                @else
                                    <span class="badge bg-secondary">Tidak ada</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $fileCount = $item->media ? $item->media->count() : 0;
                                @endphp
                                @if($fileCount > 0)
                                    <span class="badge bg-info">{{ $fileCount }} file</span>
                                @else
                                    <span class="text-muted">0</span>
                                @endif
                            </td>
                            <td>
                                <small>{{ $item->created_at->format('d/m/Y') }}</small>
                            </td>
                           <td>
    <div class="btn-group" role="group">
        <a href="{{ route('peta-persil.show', $item->peta_id) }}"
           class="btn btn-sm btn-info"
           title="Lihat Detail Peta">
            <i class="fas fa-eye me-1"></i> Detail
        </a>
        {{-- <a href="{{ route('persil.show', $item->persil_id) }}"
           class="btn btn-sm btn-secondary"
           title="Lihat Data Persil">
            <i class="fas fa-info-circle me-1"></i> Persil
        </a> --}}
        <a href="{{ route('peta-persil.edit', $item->peta_id) }}"
           class="btn btn-sm btn-warning"
           title="Edit Peta">
            <i class="fas fa-edit me-1"></i> Edit
        </a>
        <button type="button"
                onclick="confirmDelete('{{ $item->peta_id }}', '{{ $item->persil->kode_persil }}')"
                class="btn btn-sm btn-danger"
                title="Hapus Peta">
            <i class="fas fa-trash me-1"></i> Hapus
        </button>
    </div>

    <form id="delete-form-{{ $item->peta_id }}"
          action="{{ route('peta-persil.destroy', $item->peta_id) }}"
          method="POST"
          class="d-none">
        @csrf
        @method('DELETE')
    </form>
</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">
                                <i class="fas fa-map me-2"></i>
                                @if (request('search'))
                                    Tidak ada data peta yang sesuai dengan filter
                                @else
                                    Tidak ada data peta persil
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $peta->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

{{-- Info Statistik --}}
<div class="row mt-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ $peta->total() }}</h4>
                        <small>Total Peta</small>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-map fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ $peta->whereNotNull('geojson')->count() }}</h4>
                        <small>Peta dengan GeoJSON</small>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-code fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ $peta->whereNotNull('panjang_m')->whereNotNull('lebar_m')->count() }}</h4>
                        <small>Peta dengan Dimensi</small>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-ruler-combined fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        @php
                            $totalFiles = 0;
                            foreach ($peta as $item) {
                                $totalFiles += $item->media ? $item->media->count() : 0;
                            }
                        @endphp
                        <h4 class="mb-0">{{ $totalFiles }}</h4>
                        <small>Total File Upload</small>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-file-upload fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
function confirmDelete(petaId, kodePersil) {
    if (confirm(`Yakin ingin menghapus data peta untuk persil ${kodePersil}?`)) {
        document.getElementById(`delete-form-${petaId}`).submit();
    }
}
</script>
<style>
.btn-group .btn {
    border-radius: 0.25rem !important;
    margin: 0 2px;
    min-width: 70px;
}
.btn-group .btn:first-child {
    border-top-left-radius: 0.25rem !important;
    border-bottom-left-radius: 0.25rem !important;
}
.btn-group .btn:last-child {
    border-top-right-radius: 0.25rem !important;
    border-bottom-right-radius: 0.25rem !important;
}
.btn i {
    font-size: 0.9em;
}
</style>
@endsection
