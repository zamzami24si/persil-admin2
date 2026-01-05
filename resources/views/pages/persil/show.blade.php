@extends('layouts.admin.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Detail Persil: {{ $persil->kode_persil }}</h4>
                    <div class="card-tools">
                        <a href="{{ route('persil.edit', $persil->persil_id) }}" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('persil.index') }}" class="btn btn-sm btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Informasi Persil</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th width="40%">Kode Persil</th>
                                    <td>{{ $persil->kode_persil }}</td>
                                </tr>
                                <tr>
                                    <th>Pemilik</th>
                                    <td>
                                        @if($persil->pemilik)
                                            {{ $persil->pemilik->nama }}<br>
                                            <small class="text-muted">NIK: {{ $persil->pemilik->no_ktp }}</small>
                                        @else
                                            <span class="text-danger">Tidak ditemukan</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Luas</th>
                                    <td>{{ number_format($persil->luas_m2 ?? 0, 0, ',', '.') }} m²</td>
                                </tr>
                                <tr>
                                    <th>Penggunaan</th>
                                    <td>{{ $persil->penggunaan }}</td>
                                </tr>
                                <tr>
                                    <th>RT/RW</th>
                                    <td>{{ $persil->rt }} / {{ $persil->rw }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Alamat & Lokasi</h5>
                            <div class="card bg-light mb-3">
                                <div class="card-body py-2">
                                    <p class="mb-0">{{ $persil->alamat_lahan }}</p>
                                </div>
                            </div>
                            </div>
                    </div>


                    @if(isset($mediaFiles) && $mediaFiles->count() > 0)
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5>File Terupload</h5>
                            <div class="row">
                                @foreach($mediaFiles as $media)
                                <div class="col-md-3 mb-4">
                                    <div class="card h-100 shadow-sm">
                                        <div class="card-body p-0 d-flex align-items-center justify-content-center bg-light" style="height: 160px; overflow: hidden;">
                                            @if(str_starts_with($media->mime_type, 'image/'))
                                                <a href="{{ route('persil.preview', $media->media_id) }}" target="_blank">
                                                    <img src="{{ asset('storage/' . $media->file_url) }}" class="img-fluid" style="max-height: 160px;" alt="Preview">
                                                </a>
                                            @else
                                                <div class="text-center">
                                                    <i class="fas fa-file-alt fa-3x text-secondary"></i>
                                                    <div class="mt-2 small text-muted">{{ strtoupper(pathinfo($media->file_url, PATHINFO_EXTENSION)) }}</div>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="card-footer bg-white p-2">
                                            <div class="text-truncate fw-bold mb-1" title="{{ $media->caption }}">{{ $media->caption }}</div>
                                            <div class="d-flex justify-content-between mt-2">
                                                <a href="{{ route('persil.preview', $media->media_id) }}" target="_blank" class="btn btn-sm btn-outline-primary w-50 me-1">
                                                    <i class="fas fa-eye"></i> Lihat
                                                </a>
                                                <a href="{{ route('persil.download', $media->media_id) }}" class="btn btn-sm btn-outline-success w-50">
                                                    <i class="fas fa-download"></i> Unduh
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
                    <div class="alert alert-warning mt-4">Belum ada file yang diupload.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
