{{-- resources/views/pages/sengketa-persil/index.blade.php --}}
@extends('layouts.admin.app')

@section('title', 'Bina Desa | Data Sengketa Persil')
@section('page_title', 'Data Sengketa Persil')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">Daftar Sengketa Persil</h3>
        <div class="dropdown">
            <button class="btn btn-sm btn-success dropdown-toggle" type="button" id="addSengketaDropdown"
                    data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-plus me-1"></i> Tambah Sengketa
            </button>
            <ul class="dropdown-menu" aria-labelledby="addSengketaDropdown" style="min-width: 300px;">
                <li><h6 class="dropdown-header">Pilih Persil</h6></li>
                @php
                    // Ambil persil yang belum memiliki sengketa aktif
                    $persilOptions = \App\Models\Persil::with('pemilik')
                        ->whereDoesntHave('sengketa', function($query) {
                            $query->whereIn('status', ['proses', 'selesai']);
                        })
                        ->orderBy('kode_persil')
                        ->limit(8)
                        ->get();
                @endphp
                @if($persilOptions->count() > 0)
                    @foreach($persilOptions as $persil)
                        <li>
                            <a class="dropdown-item" href="{{ route('sengketa-persil.create', $persil->persil_id) }}">
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
                                <span>Semua persil sudah memiliki sengketa aktif</span>
                                <small>Edit sengketa yang sudah ada</small>
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

        {{-- FILTER & SEARCH FORM --}}
        <form method="GET" action="{{ route('sengketa-persil.index') }}" class="mb-4">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Status Sengketa</label>
                    <select name="status" class="form-select" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        @foreach ($statusOptions as $value => $label)
                            <option value="{{ $value }}" {{ request('status') == $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-5">
                    <label class="form-label">Pencarian</label>
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" value="{{ request('search') }}"
                            placeholder="Cari pihak 1, pihak 2, kronologi, atau penyelesaian...">
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
                    <a href="{{ route('sengketa-persil.index') }}" class="btn btn-secondary w-100">
                        <i class="fas fa-refresh"></i> Reset
                    </a>
                </div>

                @if (request('status') || request('search'))
                    <div class="col-12">
                        <div class="alert alert-info py-2">
                            <small>
                                <i class="fas fa-info-circle me-1"></i>
                                Filter aktif:
                                @if (request('status'))
                                    <span class="badge bg-primary me-2">
                                        Status: {{ $statusOptions[request('status')] ?? request('status') }}
                                    </span>
                                @endif
                                @if (request('search'))
                                    <span class="badge bg-primary me-2">
                                        Pencarian: "{{ request('search') }}"
                                    </span>
                                @endif
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
                        <th>Pihak yang Bersengketa</th>
                        <th>Kronologi</th>
                        <th>Status</th>
                        <th>Bukti</th>
                        <th>Tanggal Dibuat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sengketa as $item)
                        <tr>
                            <td>{{ ($sengketa->currentPage() - 1) * $sengketa->perPage() + $loop->iteration }}</td>
                            <td>
                                <strong>{{ $item->persil->kode_persil }}</strong>
                                <br>
                                <small class="text-muted">Pemilik: {{ $item->persil->pemilik->nama }}</small>
                            </td>
                            <td>
                                <strong>Pihak 1:</strong> {{ $item->pihak_1 }}
                                <br>
                                <strong>Pihak 2:</strong> {{ $item->pihak_2 }}
                            </td>
                            <td>
                                {{ Str::limit($item->kronologi, 80) }}
                                @if (strlen($item->kronologi) > 80)
                                    <a href="#" data-bs-toggle="tooltip" title="{{ $item->kronologi }}">
                                        <i class="fas fa-info-circle"></i>
                                    </a>
                                @endif
                            </td>
                            <td>
                                @php
                                    $badgeClasses = [
                                        'proses' => 'bg-warning',
                                        'selesai' => 'bg-success',
                                        'dibatalkan' => 'bg-danger',
                                    ];
                                    $statusLabels = [
                                        'proses' => 'Dalam Proses',
                                        'selesai' => 'Selesai',
                                        'dibatalkan' => 'Dibatalkan',
                                    ];
                                @endphp
                                <span class="badge {{ $badgeClasses[$item->status] ?? 'bg-secondary' }}">
                                    {{ $statusLabels[$item->status] ?? $item->status }}
                                </span>
                            </td>
                    <td>
    @php
        $firstMedia = $item->media()->first();
    @endphp
    @if ($firstMedia && $firstMedia->id)
        <a href="{{ route('sengketa-persil.download', $firstMedia->id) }}"
            class="btn btn-sm btn-outline-primary" title="Download Bukti">
            <i class="fas fa-download"></i> File
        </a>
    @else
        <span class="text-muted">-</span>
    @endif
</td>
                            <td>
                                <small>{{ $item->created_at->format('d/m/Y') }}</small>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('sengketa-persil.show', $item->sengketa_id) }}"
                                        class="btn btn-sm btn-info" title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('persil.show', $item->persil_id) }}"
                                        class="btn btn-sm btn-secondary" title="Lihat Persil">
                                        <i class="fas fa-info-circle"></i>
                                    </a>
                                    <a href="{{ route('sengketa-persil.edit', $item->sengketa_id) }}"
                                        class="btn btn-sm btn-warning" title="Edit Sengketa">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('sengketa-persil.destroy', $item->sengketa_id) }}"
                                        method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Yakin ingin menghapus data sengketa untuk persil {{ $item->persil->kode_persil }}?')"
                                            title="Hapus Sengketa">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="fas fa-gavel me-2"></i>
                                @if (request('status') || request('search'))
                                    Tidak ada data sengketa yang sesuai dengan filter
                                @else
                                    Tidak ada data sengketa persil
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $sengketa->links('pagination::bootstrap-5') }}
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
                        <h4 class="mb-0">{{ $sengketa->total() }}</h4>
                        <small>Total Sengketa</small>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-gavel fa-2x"></i>
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
                        <h4 class="mb-0">{{ $sengketa->where('status', 'proses')->count() }}</h4>
                        <small>Dalam Proses</small>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-clock fa-2x"></i>
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
                        <h4 class="mb-0">{{ $sengketa->where('status', 'selesai')->count() }}</h4>
                        <small>Selesai</small>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-danger text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ $sengketa->where('status', 'dibatalkan')->count() }}</h4>
                        <small>Dibatalkan</small>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-times-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    });
</script>
@endsection
