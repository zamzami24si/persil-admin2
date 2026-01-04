@extends('layouts.admin.app')

@section('title', 'Bina Desa | Detail Warga')
@section('page_title', 'Detail Warga')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Detail Data Warga</h3>
            <div class="card-tools">
                <a href="{{ route('warga.index') }}" class="btn btn-sm btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
                <a href="{{ route('warga.edit', $warga->warga_id) }}" class="btn btn-sm btn-warning ms-1">
                    <i class="fas fa-edit"></i> Edit
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 text-center mb-4">
                    <div class="mb-3">
                        @if($warga->foto)
                            <img src="{{ asset('storage/' . $warga->foto) }}"
                                 alt="Foto {{ $warga->nama }}"
                                 class="img-fluid rounded"
                                 style="max-height: 250px;">
                        @else
                            <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                 style="height: 150px; width: 150px; margin: 0 auto;">
                                <i class="fas fa-user fa-4x text-muted"></i>
                            </div>
                        @endif
                    </div>
                    <h4>{{ $warga->nama }}</h4>
                    <p class="text-muted">{{ $warga->no_ktp }}</p>
                </div>

                <div class="col-md-9">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="border-bottom pb-2 mb-3">Data Pribadi</h5>

                            <div class="mb-3">
                                <label class="form-label fw-bold">NIK</label>
                                <p class="fs-5">{{ $warga->no_ktp ?? '-' }}</p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Nama Lengkap</label>
                                <p class="fs-5">{{ $warga->nama }}</p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Jenis Kelamin</label>
                                <p>
                                    <span class="badge bg-{{ $warga->jenis_kelamin == 'L' ? 'primary' : 'success' }} fs-6">
                                        {{ $warga->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                                    </span>
                                </p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Agama</label>
                                <p>{{ $warga->agama ?? '-' }}</p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Pekerjaan</label>
                                <p>{{ $warga->pekerjaan ?? '-' }}</p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <h5 class="border-bottom pb-2 mb-3">Kontak & Status</h5>

                            <div class="mb-3">
                                <label class="form-label fw-bold">No. Telepon</label>
                                <p>{{ $warga->telp ?? '-' }}</p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Email</label>
                                <p>{{ $warga->email ?? '-' }}</p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Status Warga</label>
                                <p>
                                    @if($warga->status_warga == 'aktif')
                                        <span class="badge bg-success">Aktif</span>
                                    @elseif($warga->status_warga == 'pindah')
                                        <span class="badge bg-warning">Pindah</span>
                                    @elseif($warga->status_warga == 'meninggal')
                                        <span class="badge bg-danger">Meninggal</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $warga->status_warga ?? 'Aktif' }}</span>
                                    @endif
                                </p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Tanggal Daftar</label>
                                <p>
                                    {{ $warga->created_at ? \Carbon\Carbon::parse($warga->created_at)->format('d-m-Y H:i') : '-' }}
                                </p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Terakhir Diupdate</label>
                                <p>
                                    {{ $warga->updated_at ? \Carbon\Carbon::parse($warga->updated_at)->format('d-m-Y H:i') : '-' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer text-end">
            <form action="{{ route('warga.destroy', $warga->warga_id) }}" method="POST" class="d-inline"
                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus data warga ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-trash"></i> Hapus Data
                </button>
            </form>
        </div>
    </div>

    <style>
        .form-label.fw-bold {
            color: #495057;
            margin-bottom: 0.25rem;
        }
        .card-header .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
        .badge {
            font-size: 0.85em;
            padding: 0.35em 0.65em;
        }
    </style>
@endsection
