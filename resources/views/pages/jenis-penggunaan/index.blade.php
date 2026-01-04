{{-- resources/views/pages/jenis-penggunaan/index.blade.php --}}
@extends('layouts.admin.app')

@section('title', 'Bina Desa | Data Jenis Penggunaan')
@section('page_title', 'Data Jenis Penggunaan')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Jenis Penggunaan Persil</h3>
        <div class="card-tools">
            <a href="{{ route('jenis-penggunaan.create') }}" class="btn btn-sm btn-primary">
                <i class="fas fa-plus"></i> Tambah Jenis Penggunaan
            </a>
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
        <form method="GET" action="{{ route('jenis-penggunaan.index') }}" class="mb-4">
            <div class="row g-3 align-items-end">
                <div class="col-md-8">
                    <label class="form-label">Pencarian</label>
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" value="{{ request('search') }}"
                            placeholder="Cari nama penggunaan atau keterangan...">
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
                    <a href="{{ route('jenis-penggunaan.index') }}" class="btn btn-secondary w-100">
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
                        <th>Nama Penggunaan</th>
                        <th>Keterangan</th>
                        <th>Jumlah Persil</th>
                        <th>Tanggal Dibuat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jenisPenggunaan as $item)
                        <tr>
                            <td>{{ ($jenisPenggunaan->currentPage() - 1) * $jenisPenggunaan->perPage() + $loop->iteration }}</td>
                            <td>
                                <strong>{{ $item->nama_penggunaan }}</strong>
                            </td>
                            <td>
                                @if($item->keterangan)
                                    {{ Str::limit($item->keterangan, 80) }}
                                    @if(strlen($item->keterangan) > 80)
                                        <a href="#" data-bs-toggle="tooltip" title="{{ $item->keterangan }}">
                                            <i class="fas fa-info-circle"></i>
                                        </a>
                                    @endif
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $item->persils_count }}</span>
                            </td>
                            <td>
                                <small>{{ $item->created_at->format('d/m/Y') }}</small>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('jenis-penggunaan.show', $item->jenis_id) }}"
                                        class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                    <a href="{{ route('jenis-penggunaan.edit', $item->jenis_id) }}"
                                        class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('jenis-penggunaan.destroy', $item->jenis_id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"
onclick="return confirm('Yakin ingin menghapus jenis penggunaan {{ $item->nama_penggunaan }}?')">                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="fas fa-list me-2"></i>
                                @if (request('search'))
                                    Tidak ada data jenis penggunaan yang sesuai dengan pencarian
                                @else
                                    Tidak ada data jenis penggunaan
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $jenisPenggunaan->links('pagination::bootstrap-5') }}
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
                        <h4 class="mb-0">{{ $jenisPenggunaan->total() }}</h4>
                        <small>Total Jenis Penggunaan</small>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-list fa-2x"></i>
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
                        <h4 class="mb-0">{{ $jenisPenggunaan->sum('persils_count') }}</h4>
                        <small>Total Persil</small>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-map fa-2x"></i>
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
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    });
</script>
@endsection
