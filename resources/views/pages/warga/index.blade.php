@extends('layouts.admin.app')

@section('title', 'Bina Desa | Data Warga')
@section('page_title', 'Data Warga')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Warga</h3>
            <div class="card-tools">
                <a href="{{ route('warga.create') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus"></i> Tambah Warga
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

            {{-- FILTER & SEARCH FORM --}}
            <form method="GET" action="{{ route('warga.index') }}" class="mb-4">
                <div class="row g-3 align-items-end">
                    <div class="col-md-2">
                        <label class="form-label">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="form-select" onchange="this.form.submit()">
                            <option value="">Semua</option>
                            @foreach ($jenisKelaminOptions as $key => $value)
                                <option value="{{ $key }}"
                                    {{ request('jenis_kelamin') == $key ? 'selected' : '' }}>
                                    {{ $value }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Agama</label>
                        <select name="agama" class="form-select" onchange="this.form.submit()">
                            <option value="">Semua Agama</option>
                            @foreach ($agamaOptions as $agama)
                                <option value="{{ $agama }}" {{ request('agama') == $agama ? 'selected' : '' }}>
                                    {{ $agama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Pekerjaan</label>
                        <select name="pekerjaan" class="form-select" onchange="this.form.submit()">
                            <option value="">Semua Pekerjaan</option>
                            @foreach ($pekerjaanOptions as $pekerjaan)
                                <option value="{{ $pekerjaan }}"
                                    {{ request('pekerjaan') == $pekerjaan ? 'selected' : '' }}>
                                    {{ $pekerjaan }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Pencarian</label>
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" value="{{ request('search') }}"
                                placeholder="Cari NIK, nama, telepon, atau email...">
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
                        <a href="{{ route('warga.index') }}" class="btn btn-secondary w-100">
                            <i class="fas fa-refresh"></i> Reset
                        </a>
                    </div>

                    @if (request('jenis_kelamin') || request('agama') || request('pekerjaan') || request('search'))
                        <div class="col-12">
                            <div class="alert alert-info py-2">
                                <small>
                                    <i class="fas fa-info-circle me-1"></i>
                                    Filter aktif:
                                    @if (request('jenis_kelamin'))
                                        <span class="badge bg-primary me-2">
                                            {{ $jenisKelaminOptions[request('jenis_kelamin')] ?? request('jenis_kelamin') }}
                                        </span>
                                    @endif
                                    @if (request('agama'))
                                        <span class="badge bg-primary me-2">
                                            {{ request('agama') }}
                                        </span>
                                    @endif
                                    @if (request('pekerjaan'))
                                        <span class="badge bg-primary me-2">
                                            {{ request('pekerjaan') }}
                                        </span>
                                    @endif
                                    @if (request('search'))
                                        <span class="badge bg-primary me-2">
                                            "{{ request('search') }}"
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
                            <th>NIK</th>
                            <th>Nama</th>
                            <th>Jenis Kelamin</th>
                            <th>Agama</th>
                            <th>Pekerjaan</th>
                            <th>Telepon</th>
                            <th width="180">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($warga as $item)
                            <tr>
                                <td>{{ ($warga->currentPage() - 1) * $warga->perPage() + $loop->iteration }}</td>
                                <td>{{ $item->no_ktp }}</td>
                                <td>{{ $item->nama }}</td>
                                <td>
                                    <span class="badge bg-{{ $item->jenis_kelamin == 'L' ? 'primary' : 'success' }}">
                                        {{ $item->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                                    </span>
                                </td>
                                <td>{{ $item->agama }}</td>
                                <td>{{ $item->pekerjaan }}</td>
                                <td>{{ $item->telp ?? '-' }}</td>
                                <td>
                                    <!-- SOLUSI SIMPLE: Tampilkan tombol dalam satu baris tanpa kompleksitas -->
                                    <a href="{{ route('warga.show', $item->warga_id) }}"
                                       class="btn btn-info btn-xs mb-1"
                                       title="Lihat">
                                        <i class="fas fa-eye fa-xs"></i> Lihat
                                    </a>
                                    <a href="{{ route('warga.edit', $item->warga_id) }}"
                                       class="btn btn-warning btn-xs mb-1"
                                       title="Edit">
                                        <i class="fas fa-edit fa-xs"></i> Edit
                                    </a>
                                    <button type="button"
                                            class="btn btn-danger btn-xs mb-1"
                                            title="Hapus"
                                            onclick="if(confirm('Yakin hapus data ini?')) {
                                                document.getElementById('delete-form-{{ $item->warga_id }}').submit();
                                            }">
                                        <i class="fas fa-trash fa-xs"></i> Hapus
                                    </button>
                                    <form id="delete-form-{{ $item->warga_id }}"
                                          action="{{ route('warga.destroy', $item->warga_id) }}"
                                          method="POST"
                                          style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    <i class="fas fa-user-slash me-2"></i>
                                    @if (request('jenis_kelamin') || request('agama') || request('pekerjaan') || request('search'))
                                        Tidak ada data warga yang sesuai dengan filter
                                    @else
                                        Tidak ada data warga
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $warga->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>

    <style>
        /* CSS SEDERHANA untuk memastikan tombol terlihat */
        .btn-xs {
            padding: 0.15rem 0.4rem !important;
            font-size: 0.75rem !important;
            border-radius: 0.2rem !important;
            margin-right: 3px !important;
            margin-bottom: 2px !important;
            display: inline-block !important;
            white-space: nowrap !important;
        }

        .fa-xs {
            font-size: 0.75rem !important;
        }

        td:last-child {
            min-width: 200px !important;
            white-space: nowrap !important;
        }

        .table-responsive {
            overflow-x: auto !important;
            -webkit-overflow-scrolling: touch !important;
        }

        /* Pastikan tabel tidak memaksa kolom terlalu sempit */
        table {
            table-layout: auto !important;
            width: 100% !important;
        }

        th:last-child, td:last-child {
            width: 200px !important;
            min-width: 200px !important;
            max-width: 250px !important;
        }
    </style>
@endsection
