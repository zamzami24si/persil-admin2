{{-- resources/views/pages/peta-persil/index.blade.php --}}
@extends('layouts.admin.app')

@section('title', 'Bina Desa | Data Peta Persil')
@section('page_title', 'Data Peta Persil')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">Daftar Peta Persil</h3>
        <div class="dropdown">
            <button class="btn btn-sm btn-success dropdown-toggle" type="button" id="addPetaDropdown"
                    data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-plus me-1"></i> Tambah Peta
            </button>
            <ul class="dropdown-menu" aria-labelledby="addPetaDropdown" style="min-width: 300px;">
                <li><h6 class="dropdown-header">Pilih Persil</h6></li>
                @php
                    // Ambil persil yang belum memiliki peta
                    $persilOptions = \App\Models\Persil::with('pemilik')
                        ->whereDoesntHave('peta')
                        ->orderBy('kode_persil')
                        ->limit(8)
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
                            <div class="d-flex flex-column">
                                <span>Semua persil sudah memiliki peta</span>
                                <small>Edit peta yang sudah ada</small>
                            </div>
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                @endif
                <li>
                    <a class="dropdown-item text-primary" href="{{ route('persil.index') }}">
                        <i class="fas fa-search me-2"></i> Cari Persil Lainnya...
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <div class="card-body">
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
                            placeholder="Cari berdasarkan panjang atau lebar...">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Search
                        </button>
                        @if (request('search'))
                            <a href="{{ request()->fullUrlWithQuery(['search' => null]) }}"
                                class="btn btn-outline-secondary">
                                Clear
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
                                <small>{{ $item->created_at->format('d/m/Y') }}</small>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('peta-persil.show', $item->peta_id) }}"
                                       class="btn btn-sm btn-info"
                                       title="Lihat Detail Peta">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('persil.show', $item->persil_id) }}"
                                       class="btn btn-sm btn-secondary"
                                       title="Lihat Data Persil">
                                        <i class="fas fa-info-circle"></i>
                                    </a>
                                    <a href="{{ route('peta-persil.edit', $item->peta_id) }}"
                                       class="btn btn-sm btn-warning"
                                       title="Edit Peta">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('peta-persil.destroy', $item->peta_id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Yakin ingin menghapus data peta untuk persil {{ $item->persil->kode_persil }}?')"
                                            title="Hapus Peta">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
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
</div>
@endsection
