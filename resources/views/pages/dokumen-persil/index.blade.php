{{-- resources/views/pages/dokumen-persil/index.blade.php --}}
@extends('layouts.admin.app')

@section('title', 'Bina Desa | Data Dokumen Persil')
@section('page_title', 'Data Dokumen Persil')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">Daftar Dokumen Persil</h3>
        <div class="dropdown">
            <button class="btn btn-sm btn-success dropdown-toggle" type="button" id="addDocumentDropdown"
                    data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-plus-circle me-1"></i> Tambah Dokumen
            </button>
            <ul class="dropdown-menu" aria-labelledby="addDocumentDropdown" style="min-width: 300px;">
                <li><h6 class="dropdown-header">Pilih Persil</h6></li>
                @php
                    $persilOptions = \App\Models\Persil::with('pemilik')
                        ->orderBy('kode_persil')
                        ->limit(8)
                        ->get();
                @endphp
                @if($persilOptions->count() > 0)
                    @foreach($persilOptions as $persil)
                        <li>
                            <a class="dropdown-item" href="{{ route('dokumen-persil.create', $persil->persil_id) }}">
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
                                <span>Belum ada data persil</span>
                                <small>Tambahkan persil terlebih dahulu</small>
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
        <form method="GET" action="{{ route('dokumen-persil.index') }}" class="mb-4">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label fw-bold">Jenis Dokumen</label>
                    <select name="jenis_dokumen" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">Semua Jenis</option>
                        @foreach ($jenisDokumenOptions as $jenis)
                            <option value="{{ $jenis }}" {{ request('jenis_dokumen') == $jenis ? 'selected' : '' }}>
                                {{ $jenis }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-5">
                    <label class="form-label fw-bold">Pencarian</label>
                    <div class="input-group input-group-sm">
                        <input type="text" name="search" class="form-control" value="{{ request('search') }}"
                            placeholder="Cari nomor dokumen atau keterangan...">
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
                    <a href="{{ route('dokumen-persil.index') }}" class="btn btn-secondary btn-sm w-100">
                        <i class="fas fa-refresh"></i> Reset
                    </a>
                </div>

                @if (request('jenis_dokumen') || request('search'))
                    <div class="col-12">
                        <div class="alert alert-info py-2">
                            <small>
                                <i class="fas fa-info-circle me-1"></i>
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
                            </small>
                        </div>
                    </div>
                @endif
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th class="text-center">No</th>
                        <th>Kode Persil</th>
                        <th>Pemilik</th>
                        <th>Jenis Dokumen</th>
                        <th>Nomor Dokumen</th>
                        <th>Keterangan</th>
                        <th class="text-center">File</th>
                        <th class="text-center">Tanggal</th>
                        <th class="text-center">Aksi</th>
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
                            <td class="text-center fw-bold">{{ ($dokumen->currentPage() - 1) * $dokumen->perPage() + $loop->iteration }}</td>
                            <td>
                                <span class="badge bg-primary">{{ $item->persil->kode_persil }}</span>
                            </td>
                            <td>
                                <div class="d-flex flex-column">
                                    <strong class="text-dark">{{ $item->persil->pemilik->nama }}</strong>
                                    <small class="text-muted">{{ $item->persil->pemilik->no_ktp }}</small>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $item->jenis_dokumen }}</span>
                            </td>
                            <td>
                                <strong class="text-primary">{{ $item->nomor }}</strong>
                            </td>
                            <td>
                                @if($item->keterangan)
                                    {{ Str::limit($item->keterangan, 50) }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($fileCount > 0)
                                    <span class="badge bg-success">
                                        <i class="fas fa-file me-1"></i> {{ $fileCount }}
                                    </span>
                                @else
                                    <span class="badge bg-secondary">
                                        <i class="fas fa-times me-1"></i> 0
                                    </span>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="text-muted">{{ $item->created_at->format('d/m/Y') }}</span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('dokumen-persil.show', $item->dokumen_id) }}"
                                       class="btn btn-info"
                                       title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('dokumen-persil.edit', $item->dokumen_id) }}"
                                       class="btn btn-warning"
                                       title="Edit Dokumen">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('dokumen-persil.destroy', $item->dokumen_id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger"
                                            onclick="return confirm('Yakin ingin menghapus dokumen {{ $item->nomor }}?')"
                                            title="Hapus Dokumen">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">
                                <div class="py-3">
                                    <i class="fas fa-file me-2 fa-lg"></i>
                                    @if (request('jenis_dokumen') || request('search'))
                                        <span class="fw-bold">Tidak ada data dokumen yang sesuai dengan filter</span>
                                    @else
                                        <span class="fw-bold">Tidak ada data dokumen persil</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3 d-flex justify-content-between align-items-center">
            <div class="text-muted">
                Menampilkan {{ $dokumen->firstItem() ?? 0 }} - {{ $dokumen->lastItem() ?? 0 }} dari {{ $dokumen->total() }} data
            </div>
            <div>
                {{ $dokumen->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection
