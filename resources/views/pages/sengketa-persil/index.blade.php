{{-- resources/views/pages/sengketa-persil/index.blade.php --}}
@extends('layouts.admin.app')

@section('title', 'Data Sengketa Persil')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                {{-- Header: Judul di Kiri, Tombol Tambah di Kanan --}}
                <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0 fw-bold">Daftar Sengketa Persil</h4>

                    {{-- Dropdown Tambah Sengketa --}}
                    <div class="dropdown">
                        <button class="btn btn-primary dropdown-toggle shadow-sm" type="button" id="addSengketaDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-plus-circle me-2"></i>Tambah Sengketa
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="addSengketaDropdown" style="max-height: 300px; overflow-y: auto;">
                            <li><h6 class="dropdown-header text-uppercase small fw-bold">Pilih Persil</h6></li>
                            @php
                                $persilList = \App\Models\Persil::orderBy('kode_persil')->get();
                            @endphp
                            @forelse($persilList as $p)
                                <li>
                                    <a class="dropdown-item" href="{{ route('sengketa-persil.create', $p->persil_id) }}">
                                        <strong>{{ $p->kode_persil }}</strong>
                                        <span class="text-muted small">- {{ $p->pemilik->nama ?? 'Tanpa Pemilik' }}</span>
                                    </a>
                                </li>
                            @empty
                                <li><span class="dropdown-item text-muted">Belum ada data persil</span></li>
                            @endforelse
                        </ul>
                    </div>
                </div>

                <div class="card-body">

                    {{-- ===== ALERT SECTION ===== --}}
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    {{-- ======================== --}}

                   {{-- ===== FORM PENCARIAN & FILTER ===== --}}
                    <form action="{{ route('sengketa-persil.index') }}" method="GET" class="mb-4">
                        <div class="row g-2 align-items-end"> {{-- align-items-end agar sejajar bawah --}}
                            {{-- Input Pencarian --}}
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Pencarian</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                                    <input type="text" name="search" class="form-control border-start-0 ps-0"
                                           placeholder="Cari pihak, kronologi, atau kode persil..."
                                           value="{{ request('search') }}">
                                </div>
                            </div>

                            {{-- Filter Status --}}
                            <div class="col-md-3">
                                <label class="form-label small fw-bold">Status</label>
                                <select name="status" class="form-select">
                                    <option value="">Semua Status</option>
                                    @foreach($statusOptions as $key => $label)
                                        <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Tombol Cari & Reset (DIPERBAIKI) --}}
                            <div class="col-md-3">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary flex-grow-1">
                                        <i class="fas fa-search me-1"></i> Cari
                                    </button>
                                    @if(request()->hasAny(['search', 'status']))
                                        <a href="{{ route('sengketa-persil.index') }}" class="btn btn-secondary flex-grow-1" title="Reset Filter">
                                            <i class="fas fa-undo me-1"></i> Reset
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </form>
                    {{-- =================================== --}}

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center" width="5%">No</th>
                                    <th width="15%">Kode Persil</th>
                                    <th width="20%">Pihak Bersengketa</th>
                                    <th width="20%">Kronologi</th>
                                    <th width="10%">Status</th>
                                    <th width="10%">Bukti</th>
                                    <th width="20%" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sengketa as $index => $item)
                                    <tr>
                                        <td class="text-center">{{ $sengketa->firstItem() + $index }}</td>
                                        <td>
                                            <a href="{{ route('persil.show', $item->persil_id) }}" class="fw-bold text-decoration-none text-primary">
                                                {{ $item->persil->kode_persil ?? '-' }}
                                            </a>
                                        </td>
                                        <td>
                                            <div class="small mb-1"><span class="badge bg-danger me-1" style="width: 20px;">1</span> {{ $item->pihak_1 }}</div>
                                            <div class="small"><span class="badge bg-warning text-dark me-1" style="width: 20px;">2</span> {{ $item->pihak_2 }}</div>
                                        </td>
                                        <td>
                                            <span class="d-inline-block text-truncate" style="max-width: 200px;" title="{{ $item->kronologi }}">
                                                {{ $item->kronologi }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge {{ $item->status_badge_class }} rounded-pill px-3">
                                                {{ $item->status_label }}
                                            </span>
                                        </td>

                                        {{-- Kolom Bukti --}}
                                        <td>
                                            @if($item->media->count() > 0)
                                                @php $firstMedia = $item->media->first(); @endphp
                                                <a href="{{ route('sengketa-persil.preview', $firstMedia->media_id) }}"
                                                   target="_blank"
                                                   class="btn btn-sm btn-outline-info w-100 d-flex align-items-center justify-content-center"
                                                   title="Lihat Bukti">
                                                    <i class="fas fa-paperclip me-1"></i> {{ $item->media->count() }} File
                                                </a>
                                            @else
                                                <span class="text-muted small fst-italic d-block text-center">
                                                    - Kosong -
                                                </span>
                                            @endif
                                        </td>

                                        {{-- Kolom Aksi --}}
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-1">
                                                <a href="{{ route('sengketa-persil.show', $item->sengketa_id) }}"
                                                   class="btn btn-sm btn-info text-white shadow-sm"
                                                   title="Detail">
                                                    <i class="fas fa-eye me-1"></i>Detail
                                                </a>

                                                <a href="{{ route('sengketa-persil.edit', $item->sengketa_id) }}"
                                                   class="btn btn-sm btn-warning text-dark shadow-sm"
                                                   title="Edit">
                                                    <i class="fas fa-pencil-alt me-1"></i>Edit
                                                </a>

                                                <form action="{{ route('sengketa-persil.destroy', $item->sengketa_id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data sengketa ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger shadow-sm" title="Hapus">
                                                        <i class="fas fa-trash me-1"></i>Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5 text-muted">
                                            <div class="mb-3">
                                                <i class="fas fa-folder-open fa-3x text-secondary opacity-50"></i>
                                            </div>
                                            <h6 class="fw-bold">
                                                @if(request('search') || request('status'))
                                                    Data tidak ditemukan.
                                                @else
                                                    Belum ada data sengketa persil.
                                                @endif
                                            </h6>
                                            @if(!request('search') && !request('status'))
                                                <p class="small">Silakan tambahkan data sengketa baru melalui tombol di kanan atas.</p>
                                            @endif
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination Links --}}
                    <div class="mt-4">
                        {{ $sengketa->links('pagination::bootstrap-5') }}
                    </div>
                </div>

                {{-- Card Footer dengan Info Jumlah Data --}}
                <div class="card-footer bg-white border-top py-3">
                    <div class="row">
                        <div class="col-md-6">
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Menampilkan {{ $sengketa->firstItem() ?? 0 }} - {{ $sengketa->lastItem() ?? 0 }} dari {{ $sengketa->total() }} data
                            </small>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <small class="text-muted">
                                <i class="fas fa-list-ul me-1"></i>
                                Total: {{ $sengketa->total() }} sengketa
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
