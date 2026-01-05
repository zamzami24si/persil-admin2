{{-- resources/views/pages/dokumen-persil/show.blade.php --}}
@extends('layouts.admin.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Detail Dokumen Persil</h4>
                    <div class="card-tools">
                        <a href="{{ route('dokumen-persil.index') }}" class="btn btn-sm btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali ke Daftar
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Informasi Dokumen</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th width="40%">Jenis Dokumen</th>
                                    <td>
                                        <span class="badge bg-info">{{ $dokumen->jenis_dokumen }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Nomor Dokumen</th>
                                    <td>{{ $dokumen->nomor }}</td>
                                </tr>
                                <tr>
                                    <th>Persil</th>
                                    <td>
                                        <a href="{{ route('persil.show', $dokumen->persil_id) }}">
                                            {{ $dokumen->persil->kode_persil }}
                                        </a>
                                        <br>
                                        <small class="text-muted">Pemilik: {{ $dokumen->persil->pemilik->nama ?? '-' }}</small>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Keterangan</th>
                                    <td>{{ $dokumen->keterangan ?: '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Jumlah File</th>
                                    <td>
                                        <span class="badge bg-primary">{{ $mediaFiles->count() }} file</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Tanggal Dibuat</th>
                                    <td>{{ $dokumen->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if ($mediaFiles->count() > 0)
                        <div class="row mt-4">
                            <div class="col-12">
                                <h5>File Dokumen ({{ $mediaFiles->count() }})</h5>
                                <div class="row">
                                    @foreach ($mediaFiles as $media)
                                        <div class="col-md-3 mb-4">
                                            <div class="card h-100 shadow-sm">
                                                {{-- Preview Area --}}
                                                <div class="card-body p-0 d-flex align-items-center justify-content-center bg-light" style="height: 160px; overflow: hidden;">
                                                    @if (in_array($media->mime_type, ['image/jpeg', 'image/jpg', 'image/png']))
                                                        {{-- Jika Gambar, tampilkan thumbnail --}}
                                                        <a href="{{ route('dokumen-persil.preview', $media->media_id) }}" target="_blank" class="w-100 h-100 d-flex align-items-center justify-content-center">
                                                            <img src="{{ asset('storage/' . $media->file_url) }}"
                                                                 class="img-fluid"
                                                                 style="max-height: 100%; max-width: 100%; object-fit: contain;"
                                                                 alt="{{ $media->caption }}">
                                                        </a>
                                                    @else
                                                        {{-- Jika Dokumen, tampilkan Icon --}}
                                                        <div class="text-center">
                                                            @if ($media->mime_type == 'application/pdf')
                                                                <i class="fas fa-file-pdf fa-4x text-danger"></i>
                                                            @elseif(in_array($media->mime_type, ['application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document']))
                                                                <i class="fas fa-file-word fa-4x text-primary"></i>
                                                            @elseif(in_array($media->mime_type, ['application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']))
                                                                <i class="fas fa-file-excel fa-4x text-success"></i>
                                                            @else
                                                                <i class="fas fa-file fa-4x text-secondary"></i>
                                                            @endif
                                                            <div class="mt-2 small fw-bold text-muted">{{ strtoupper(pathinfo($media->file_url, PATHINFO_EXTENSION)) }}</div>
                                                        </div>
                                                    @endif
                                                </div>

                                                {{-- Footer & Actions --}}
                                                <div class="card-footer bg-white p-3 border-top">
                                                    <div class="mb-3">
                                                        <h6 class="card-title text-truncate mb-0" title="{{ $media->caption }}">
                                                            {{ $media->caption }}
                                                        </h6>
                                                        <small class="text-muted">
                                                            {{ $media->created_at->format('d M Y') }}
                                                        </small>
                                                    </div>

                                                    {{-- TOMBOL AKSI YANG DIPERBAIKI --}}
                                                    <div class="d-flex gap-2">
                                                        <a href="{{ route('dokumen-persil.preview', $media->media_id) }}"
                                                           target="_blank"
                                                           class="btn btn-sm btn-outline-primary flex-fill">
                                                            <i class="fas fa-eye me-1"></i> Lihat
                                                        </a>
                                                        <a href="{{ route('dokumen-persil.download', $media->media_id) }}"
                                                           class="btn btn-sm btn-outline-success flex-fill">
                                                            <i class="fas fa-download me-1"></i> Unduh
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-info mt-4">
                            <i class="fas fa-info-circle me-2"></i> Tidak ada file dokumen yang terupload untuk data ini.
                        </div>
                    @endif
                    </div>
            </div>
        </div>
    </div>
@endsection
