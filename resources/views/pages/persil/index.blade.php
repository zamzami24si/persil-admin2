{{-- resources/views/pages/persil/index.blade.php --}}
@extends('layouts.admin.app')

@section('title', 'Bina Desa | Data Persil')
@section('page_title', 'Data Persil')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h3 class="card-title">Daftar Persil</h3>
            <div class="card-tools">
                <a href="{{ route('persil.create') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus"></i> Tambah Persil
                </a>
            </div>
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
        <form method="GET" action="{{ route('persil.index') }}" class="mb-4" id="filterForm">
            <div class="row g-3 align-items-end">
                <div class="col-md-2">
                    <label class="form-label">Penggunaan</label>
                    <select name="penggunaan" class="form-select">
                        <option value="">Semua</option>
                        @foreach ($penggunaanOptions as $penggunaan)
                            <option value="{{ $penggunaan }}"
                                {{ request('penggunaan') == $penggunaan ? 'selected' : '' }}>
                                {{ $penggunaan }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">RT</label>
                    <select name="rt" class="form-select">
                        <option value="">Semua RT</option>
                        @foreach ($rtOptions as $rt)
                            <option value="{{ $rt }}" {{ request('rt') == $rt ? 'selected' : '' }}>
                                RT {{ $rt }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">RW</label>
                    <select name="rw" class="form-select">
                        <option value="">Semua RW</option>
                        @foreach ($rwOptions as $rw)
                            <option value="{{ $rw }}" {{ request('rw') == $rw ? 'selected' : '' }}>
                                RW {{ $rw }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Pencarian</label>
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" value="{{ request('search') }}"
                            placeholder="Cari kode persil atau alamat...">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i>
                        </button>
                        @if (request('search'))
                            <a href="{{ route('persil.index', request()->except('search', 'page')) }}"
                                class="btn btn-outline-secondary">
                                <i class="fas fa-times"></i>
                            </a>
                        @endif
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-50">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                        <a href="{{ route('persil.index') }}" class="btn btn-secondary w-50">
                            <i class="fas fa-refresh"></i> Reset
                        </a>
                    </div>
                </div>

                @if (request('penggunaan') || request('rt') || request('rw') || request('search'))
                    <div class="col-12">
                        <div class="alert alert-info py-2 mb-0">
                            <small>
                                <i class="fas fa-info-circle me-1"></i>
                                Filter aktif:
                                @if (request('penggunaan'))
                                    <span class="badge bg-primary me-2">
                                        Penggunaan: {{ request('penggunaan') }}
                                    </span>
                                @endif
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
                                <a href="{{ route('persil.index') }}" class="text-white ms-2">
                                    <small><i class="fas fa-times"></i> Hapus semua filter</small>
                                </a>
                            </small>
                        </div>
                    </div>
                @endif
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th width="50">No</th>
                        <th>Kode Persil</th>
                        <th>Pemilik</th>
                        <th width="100">Luas (m²)</th>
                        <th width="120">Penggunaan</th>
                        <th width="80">File</th>
                        <th>Alamat</th>
                        <th width="80">RT/RW</th>
                        <th width="150" class="text-center">Aksi</th>
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
                            <td class="text-center">
                                {{ ($persil->currentPage() - 1) * $persil->perPage() + $loop->iteration }}
                            </td>
                            <td>
                                <strong>{{ $item->kode_persil }}</strong>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div>
                                        <strong>{{ $item->pemilik->nama ?? 'N/A' }}</strong>
                                        @if($item->pemilik->no_ktp ?? false)
                                        <br>
                                        <small class="text-muted">{{ $item->pemilik->no_ktp }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="text-end">
                                <span class="badge bg-info">{{ number_format($item->luas_m2, 2) }}</span>
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ $item->penggunaan }}</span>
                            </td>
                            <td class="text-center">
                                @if($fileCount > 0)
                                    <a href="{{ route('persil.show', $item->persil_id) }}"
                                       class="btn btn-sm btn-success position-relative"
                                       data-bs-toggle="tooltip"
                                       title="Klik untuk melihat file ({{ $fileCount }} file)">
                                        <i class="fas fa-file"></i>
                                        <span class="badge bg-danger position-absolute top-0 start-100 translate-middle">
                                            {{ $fileCount }}
                                        </span>
                                    </a>
                                @else
                                    <span class="badge bg-light text-dark" data-bs-toggle="tooltip"
                                          title="Belum ada file">
                                        <i class="fas fa-times"></i> 0
                                    </span>
                                @endif
                            </td>
                            <td>
                                <small class="text-truncate d-inline-block" style="max-width: 250px;"
                                       data-bs-toggle="tooltip" title="{{ $item->alamat_lahan }}">
                                    {{ $item->alamat_lahan }}
                                </small>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-primary">{{ $item->rt }}/{{ $item->rw }}</span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('persil.show', $item->persil_id) }}"
                                        class="btn btn-info"
                                        title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('persil.edit', $item->persil_id) }}"
                                        class="btn btn-warning"
                                        title="Edit Persil">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-danger delete-btn"
                                        data-id="{{ $item->persil_id }}"
                                        data-kode="{{ $item->kode_persil }}"
                                        title="Hapus Persil">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">
                                <i class="fas fa-map me-2"></i>
                                @if (request('penggunaan') || request('rt') || request('rw') || request('search'))
                                    Tidak ada data persil yang sesuai dengan filter
                                @else
                                    Belum ada data persil
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $persil->withQueryString()->links('pagination::bootstrap-5') }}
        </div>
    </div>
    <div class="card-footer">
        <div class="row">
            <div class="col-md-6">
                <small class="text-muted">
                    <i class="fas fa-info-circle me-1"></i>
                    Menampilkan {{ $persil->firstItem() ?? 0 }} - {{ $persil->lastItem() ?? 0 }} dari {{ $persil->total() }} data
                </small>
            </div>
            <div class="col-md-6 text-end">
                <small class="text-muted">
                    <i class="fas fa-database me-1"></i>
                    Total file: {{ \App\Models\Media::where('ref_table', 'persil')->count() }}
                </small>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Yakin ingin menghapus persil <strong id="deleteKode"></strong>?</p>
                <p class="text-danger"><small>Data yang dihapus tidak dapat dikembalikan!</small></p>
            </div>
            <div class="modal-footer">
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
    });

    // Delete confirmation modal
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const kode = this.getAttribute('data-kode');

            document.getElementById('deleteKode').textContent = kode;
            document.getElementById('deleteForm').action = `{{ url('persil') }}/${id}`;

            const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
            modal.show();
        });
    });

    // Auto-submit filter when select changes (optional)
    document.querySelectorAll('select[name="penggunaan"], select[name="rt"], select[name="rw"]').forEach(select => {
        select.addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });
    });
</script>
@endpush
