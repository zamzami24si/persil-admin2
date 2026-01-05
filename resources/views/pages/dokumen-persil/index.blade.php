{{-- resources/views/pages/dokumen-persil/index.blade.php --}}
@extends('layouts.admin.app')

@section('title', 'Bina Desa | Data Dokumen Persil')
@section('page_title', 'Data Dokumen Persil')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom py-3">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="card-title mb-0 fw-bold">
                    <i class="fas fa-file-alt text-primary me-2"></i>Data Dokumen Persil
                </h4>
                <p class="text-muted small mb-0">Kelola dokumen terkait persil tanah</p>
            </div>
            <div>
                <div class="dropdown">
                    <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="addDocumentDropdown"
                            data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-plus me-1"></i> Tambah Dokumen
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow" style="min-width: 300px;">
                        <li><h6 class="dropdown-header">Pilih Persil</h6></li>
                        @php
                            $persilOptions = \App\Models\Persil::with('pemilik')
                                ->orderBy('kode_persil')
                                ->limit(6)
                                ->get();
                        @endphp
                        @if($persilOptions->count() > 0)
                            @foreach($persilOptions as $persil)
                                <li>
                                    <a class="dropdown-item py-2" href="{{ route('dokumen-persil.create', $persil->persil_id) }}">
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
                                    <li><hr class="dropdown-divider my-1"></li>
                                @endif
                            @endforeach
                            <li><hr class="dropdown-divider my-1"></li>
                        @else
                            <li>
                                <a class="dropdown-item py-2 text-muted" href="#">
                                    <div class="d-flex flex-column">
                                        <span>Belum ada data persil</span>
                                        <small>Tambahkan persil terlebih dahulu</small>
                                    </div>
                                </a>
                            </li>
                            <li><hr class="dropdown-divider my-1"></li>
                        @endif
                        <li>
                            <a class="dropdown-item py-2 text-primary fw-bold" href="{{ route('persil.index') }}">
                                <i class="fas fa-search me-2"></i> Cari Persil Lainnya...
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body p-3">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                <div class="d-flex align-items-center">
                    <i class="fas fa-check-circle me-2"></i>
                    <div>{{ session('success') }}</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                <div class="d-flex align-items-center">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <div>{{ session('error') }}</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- FILTER & SEARCH FORM --}}
        <form method="GET" action="{{ route('dokumen-persil.index') }}" class="mb-3">
            <div class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small fw-bold mb-1">Jenis Dokumen</label>
                    <select name="jenis_dokumen" class="form-select form-select-sm">
                        <option value="">Semua Jenis</option>
                        @foreach ($jenisDokumenOptions as $jenis)
                            <option value="{{ $jenis }}" {{ request('jenis_dokumen') == $jenis ? 'selected' : '' }}>
                                {{ $jenis }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-5">
                    <label class="form-label small fw-bold mb-1">Pencarian</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" name="search" class="form-control border-start-0" value="{{ request('search') }}"
                            placeholder="Cari nomor dokumen atau keterangan...">
                        @if (request('search'))
                            <a href="{{ route('dokumen-persil.index', request()->except('search', 'page')) }}"
                                class="input-group-text bg-light border-start-0" style="cursor: pointer;">
                                <i class="fas fa-times text-danger"></i>
                            </a>
                        @endif
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="d-flex gap-2 pt-1">
                        <button type="submit" class="btn btn-primary btn-sm flex-grow-1">
                            <i class="fas fa-filter me-1"></i> Filter
                        </button>
                         <a href="{{ route('persil.index') }}" class="btn btn-secondary w-20">
                            <i class="fas fa-refresh"></i> Reset
                        </a>
                    </div>
                </div>
            </div>

            @if (request('jenis_dokumen') || request('search'))
                <div class="mt-2">
                    <div class="alert alert-info py-2 mb-0">
                        <small>
                            <i class="fas fa-filter me-1"></i>
                            Filter aktif:
                            @if (request('jenis_dokumen'))
                                <span class="badge bg-primary me-2">
                                    Jenis: {{ request('jenis_dokumen') }}
                                </span>
                            @endif
                            @if (request('search'))
                                <span class="badge bg-primary me-2">
                                    Pencarian: "{{ request('search') }}"
                                </span>
                            @endif
                            <a href="{{ route('dokumen-persil.index') }}" class="text-decoration-none ms-2">
                                <small><i class="fas fa-times"></i> Hapus semua</small>
                            </a>
                        </small>
                    </div>
                </div>
            @endif
        </form>

        {{-- TABLE --}}
        <div class="table-responsive">
            <table class="table table-hover table-bordered mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="50" class="text-center">No</th>
                        <th>Kode Persil</th>
                        <th>Pemilik</th>
                        <th>Jenis Dokumen</th>
                        <th>Nomor Dokumen</th>
                        <th>Keterangan</th>
                        <th width="80" class="text-center">File</th>
                        <th width="100" class="text-center">Tanggal</th>
                        <th width="180" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dokumen as $item)
                        @php
                            $fileCount = \App\Models\Media::where('ref_table', 'dokumen_persil')
                                ->where('ref_id', $item->dokumen_id)
                                ->count();
                        @endphp
                        <tr>
                            <td class="text-center align-middle">
                                {{ ($dokumen->currentPage() - 1) * $dokumen->perPage() + $loop->iteration }}
                            </td>
                            <td class="align-middle">
                                <strong class="text-primary">{{ $item->persil->kode_persil }}</strong>
                            </td>
                            <td class="align-middle">
                                <div>
                                    <strong>{{ $item->persil->pemilik->nama }}</strong>
                                    @if($item->persil->pemilik->no_ktp ?? false)
                                        <div class="text-muted small">{{ $item->persil->pemilik->no_ktp }}</div>
                                    @endif
                                </div>
                            </td>
                            <td class="align-middle">
                                <span class="badge bg-info px-2 py-1">{{ $item->jenis_dokumen }}</span>
                            </td>
                            <td class="align-middle">
                                <strong>{{ $item->nomor }}</strong>
                            </td>
                            <td class="align-middle">
                                @if($item->keterangan)
                                    <div style="max-width: 150px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                        {{ $item->keterangan }}
                                    </div>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center align-middle">
                                @if($fileCount > 0)
                                    <a href="{{ route('dokumen-persil.show', $item->dokumen_id) }}"
                                       class="btn btn-sm btn-outline-success position-relative"
                                       title="Lihat file ({{ $fileCount }} file)">
                                        <i class="fas fa-file-alt"></i>
                                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                            {{ $fileCount }}
                                        </span>
                                    </a>
                                @else
                                    <span class="badge bg-light text-muted border px-2 py-1">
                                        <i class="fas fa-times me-1"></i> 0
                                    </span>
                                @endif
                            </td>
                            <td class="text-center align-middle">
                                <small class="text-muted">{{ $item->created_at->format('d/m/Y') }}</small>
                            </td>
                            <td class="text-center align-middle">
                                <div class="d-flex gap-1 justify-content-center">
                                    <a href="{{ route('dokumen-persil.show', $item->dokumen_id) }}"
                                        class="btn btn-sm btn-info d-flex align-items-center gap-1 px-3"
                                        title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                        <span>Detail</span>
                                    </a>
                                    <a href="{{ route('dokumen-persil.edit', $item->dokumen_id) }}"
                                        class="btn btn-sm btn-warning d-flex align-items-center gap-1 px-3"
                                        title="Edit Dokumen">
                                        <i class="fas fa-edit"></i>
                                        <span>Edit</span>
                                    </a>
                                    <form action="{{ route('dokumen-persil.destroy', $item->dokumen_id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger d-flex align-items-center gap-1 px-3"
                                            onclick="return confirm('Yakin ingin menghapus dokumen {{ $item->nomor }}?')"
                                            title="Hapus Dokumen">
                                            <i class="fas fa-trash"></i>
                                            <span>Hapus</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <div class="py-5">
                                    <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                                    <h6 class="mb-2">
                                        @if (request('jenis_dokumen') || request('search'))
                                            Tidak ada data dokumen yang sesuai dengan filter
                                        @else
                                            Belum ada data dokumen persil
                                        @endif
                                    </h6>
                                    <p class="text-muted small mb-0">
                                        Klik tombol "Tambah Dokumen" untuk menambahkan data baru
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        @if($dokumen->hasPages())
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div class="text-muted small">
                <i class="fas fa-info-circle me-1"></i>
                Menampilkan {{ $dokumen->firstItem() ?? 0 }} - {{ $dokumen->lastItem() ?? 0 }} dari {{ $dokumen->total() }} data
            </div>
            <div>
                {{ $dokumen->withQueryString()->links('pagination::bootstrap-5') }}
            </div>
        </div>
        @endif
    </div>
    <div class="card-footer bg-light border-top py-2">
        <div class="row">
            <div class="col-md-6">
                <small class="text-muted">
                    <i class="fas fa-database me-1"></i>
                    Total dokumen: {{ $dokumen->total() }}
                </small>
            </div>
            <div class="col-md-6 text-end">
                <small class="text-muted">
                    <i class="fas fa-file-alt me-1"></i>
                    Total file: {{ \App\Models\Media::where('ref_table', 'dokumen_persil')->count() }}
                </small>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Table styling */
.table {
    font-size: 0.9rem;
    margin-bottom: 0;
}

.table th {
    font-weight: 600;
    background-color: #f8f9fa;
    border-bottom: 2px solid #dee2e6;
    padding: 12px 10px;
    text-align: center;
    vertical-align: middle;
}

.table td {
    padding: 12px 10px;
    vertical-align: middle;
    border-color: #e9ecef;
}

/* Badge styling */
.badge {
    font-weight: 500;
    font-size: 0.8rem;
    padding: 4px 8px;
    border-radius: 4px;
}

/* Button styling */
.btn-sm {
    padding: 5px 10px;
    font-size: 0.85rem;
    min-height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 4px;
    gap: 6px;
}

/* Dropdown styling */
.dropdown-toggle::after {
    margin-left: 4px;
}

.dropdown-menu {
    border: 1px solid rgba(0,0,0,.1);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

/* Input group styling */
.input-group-text {
    background-color: #f8f9fa;
    border-color: #dee2e6;
}

/* Aksi column styling */
.d-flex.gap-1 {
    gap: 6px !important;
}

/* Responsive */
@media (max-width: 768px) {
    .table {
        font-size: 0.85rem;
    }

    .table th, .table td {
        padding: 8px 6px;
    }

    .btn-sm {
        padding: 4px 6px;
        font-size: 0.8rem;
        gap: 4px;
    }

    .btn-sm span {
        display: none;
    }

    .btn-sm i {
        margin: 0;
    }

    .dropdown-menu {
        width: 95%;
        left: 50% !important;
        transform: translateX(-50%) !important;
    }
}

@media (min-width: 769px) {
    .btn-sm span {
        display: inline;
    }
}

/* Filter form styling */
.form-select-sm {
    padding: 5px 24px 5px 8px;
    font-size: 0.875rem;
}

.form-label {
    font-size: 0.85rem;
    color: #495057;
}

.input-group-sm > .form-control {
    padding: 5px 10px;
    font-size: 0.875rem;
}

.input-group-sm > .input-group-text {
    padding: 5px 10px;
    font-size: 0.875rem;
}
</style>
@endpush

@push('scripts')
<script>
// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
    // Tooltip initialization
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });

    // Initialize dropdowns
    var dropdownElementList = [].slice.call(document.querySelectorAll('[data-bs-toggle="dropdown"]'));
    var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
        return new bootstrap.Dropdown(dropdownToggleEl);
    });
});
</script>
@endpush
