{{-- resources/views/pages/persil/index.blade.php --}}
@extends('layouts.admin.app')

@section('title', 'Bina Desa | Data Persil')
@section('page_title', 'Data Persil')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom py-3">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="card-title mb-0 fw-bold">
                    <i class="fas fa-map-marked-alt text-primary me-2"></i>Data Persil
                </h4>
                <p class="text-muted small mb-0">Kelola data persil tanah</p>
            </div>
            <div>
                <a href="{{ route('persil.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus me-1"></i> Tambah Persil
                </a>
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
        <form method="GET" action="{{ route('persil.index') }}" class="mb-3">
            <div class="row g-2 align-items-end">
                <div class="col-md-2">
                    <label class="form-label small fw-bold mb-1">RT</label>
                    <select name="rt" class="form-select form-select-sm">
                        <option value="">Semua RT</option>
                        @foreach ($rtOptions as $rt)
                            <option value="{{ $rt }}" {{ request('rt') == $rt ? 'selected' : '' }}>
                                RT {{ $rt }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label small fw-bold mb-1">RW</label>
                    <select name="rw" class="form-select form-select-sm">
                        <option value="">Semua RW</option>
                        @foreach ($rwOptions as $rw)
                            <option value="{{ $rw }}" {{ request('rw') == $rw ? 'selected' : '' }}>
                                RW {{ $rw }}
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
                            placeholder="Cari kode persil atau alamat...">
                        @if (request('search'))
                            <a href="{{ route('persil.index', request()->except('search', 'page')) }}"
                                class="input-group-text bg-light border-start-0" style="cursor: pointer;">
                                <i class="fas fa-times text-danger"></i>
                            </a>
                        @endif
                    </div>
                </div>

                <div class="col-md-3">
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

            @if (request('rt') || request('rw') || request('search'))
                <div class="mt-2">
                    <div class="alert alert-info py-2 mb-0">
                        <small>
                            <i class="fas fa-filter me-1"></i>
                            Filter aktif:
                            @if (request('rt'))
                                <span class="badge bg-primary me-2">
                                    RT: {{ request('rt') }}
                                </span>
                            @endif
                            @if (request('rw'))
                                <span class="badge bg-primary me-2">
                                    RW: {{ request('rw') }}
                                </span>
                            @endif
                            @if (request('search'))
                                <span class="badge bg-primary me-2">
                                    Pencarian: "{{ request('search') }}"
                                </span>
                            @endif
                            <a href="{{ route('persil.index') }}" class="text-decoration-none ms-2">
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
                        <th width="100" class="text-center">Luas (m²)</th>
                        <th width="120">Penggunaan</th>
                        <th width="80" class="text-center">File</th>
                        <th>Alamat</th>
                        <th width="100" class="text-center">RT/RW</th>
                        <th width="180" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($persil as $item)
                        @php
                            $fileCount = \App\Models\Media::where('ref_table', 'persil')
                                ->where('ref_id', $item->persil_id)
                                ->count();
                        @endphp
                        <tr>
                            <td class="text-center align-middle">
                                {{ ($persil->currentPage() - 1) * $persil->perPage() + $loop->iteration }}
                            </td>
                            <td class="align-middle">
                                <strong class="text-primary">{{ $item->kode_persil }}</strong>
                            </td>
                            <td class="align-middle">
                                <div>
                                    <strong>{{ $item->pemilik->nama ?? 'N/A' }}</strong>
                                    @if($item->pemilik->no_ktp ?? false)
                                        <div class="text-muted small">{{ $item->pemilik->no_ktp }}</div>
                                    @endif
                                </div>
                            </td>
                            <td class="text-center align-middle">
                                <span class="badge bg-info px-2 py-1">
                                    {{ number_format($item->luas_m2, 0) }}
                                </span>
                            </td>
                            <td class="align-middle">
                                <span class="badge bg-secondary px-2 py-1">{{ $item->penggunaan }}</span>
                            </td>
                            <td class="text-center align-middle">
                                @if($fileCount > 0)
                                    <a href="{{ route('persil.show', $item->persil_id) }}"
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
                            <td class="align-middle">
                                <div style="max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    {{ $item->alamat_lahan }}
                                </div>
                            </td>
                            <td class="text-center align-middle">
                                <span class="badge bg-primary px-3 py-1">RT {{ $item->rt }}/RW {{ $item->rw }}</span>
                            </td>
                            <td class="text-center align-middle">
                                <div class="d-flex gap-1 justify-content-center">
                                    <a href="{{ route('persil.show', $item->persil_id) }}"
                                        class="btn btn-sm btn-info d-flex align-items-center gap-1 px-3"
                                        title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                        <span>Detail</span>
                                    </a>
                                    <a href="{{ route('persil.edit', $item->persil_id) }}"
                                        class="btn btn-sm btn-warning d-flex align-items-center gap-1 px-3"
                                        title="Edit Data">
                                        <i class="fas fa-edit"></i>
                                        <span>Edit</span>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger d-flex align-items-center gap-1 px-3 delete-btn"
                                        data-id="{{ $item->persil_id }}"
                                        data-kode="{{ $item->kode_persil }}"
                                        title="Hapus Data">
                                        <i class="fas fa-trash"></i>
                                        <span>Hapus</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <div class="py-5">
                                    <i class="fas fa-map-marked-alt fa-3x text-muted mb-3"></i>
                                    <h6 class="mb-2">
                                        @if (request('rt') || request('rw') || request('search'))
                                            Tidak ada data persil yang sesuai dengan filter
                                        @else
                                            Belum ada data persil
                                        @endif
                                    </h6>
                                    <p class="text-muted small mb-0">
                                        Klik tombol "Tambah Persil" untuk menambahkan data baru
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        @if($persil->hasPages())
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div class="text-muted small">
                <i class="fas fa-info-circle me-1"></i>
                Menampilkan {{ $persil->firstItem() ?? 0 }} - {{ $persil->lastItem() ?? 0 }} dari {{ $persil->total() }} data
            </div>
            <div>
                {{ $persil->withQueryString()->links('pagination::bootstrap-5') }}
            </div>
        </div>
        @endif
    </div>
    <div class="card-footer bg-light border-top py-2">
        <div class="row">
            <div class="col-md-6">
                <small class="text-muted">
                    <i class="fas fa-database me-1"></i>
                    Total persil: {{ $persil->total() }}
                </small>
            </div>
            <div class="col-md-6 text-end">
                <small class="text-muted">
                    <i class="fas fa-file-alt me-1"></i>
                    Total file: {{ \App\Models\Media::where('ref_table', 'persil')->count() }}
                </small>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom">
                <h5 class="modal-title text-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>Konfirmasi Hapus
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body py-4">
                <div class="text-center mb-3">
                    <i class="fas fa-trash-alt fa-3x text-danger mb-3"></i>
                    <h5 class="mb-2">Hapus Data Persil</h5>
                    <p class="mb-1">Yakin ingin menghapus persil:</p>
                    <h6 class="text-danger fw-bold" id="deleteKode"></h6>
                    <p class="text-danger small mt-2">
                        <i class="fas fa-exclamation-circle me-1"></i>
                        Data yang dihapus tidak dapat dikembalikan!
                    </p>
                </div>
            </div>
            <div class="modal-footer border-top">
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i> Hapus
                    </button>
                </form>
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

/* Input group styling */
.input-group-text {
    background-color: #f8f9fa;
    border-color: #dee2e6;
}

/* Aksi column styling */
.d-flex.gap-1 {
    gap: 6px !important;
}

/* Alamat text */
td:nth-child(7) {
    max-width: 200px;
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
        padding: 4px 8px;
        font-size: 0.8rem;
        gap: 4px;
    }

    .btn-sm span {
        display: none;
    }

    .btn-sm i {
        margin: 0;
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

    // Delete confirmation modal
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const kode = this.getAttribute('data-kode');

            document.getElementById('deleteKode').textContent = kode;
            document.getElementById('deleteForm').action = `/persil/${id}`;

            const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
            modal.show();
        });
    });
});
</script>
@endpush
