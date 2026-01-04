{{-- resources/views/pages/dokumen-persil/show.blade.php --}}
@extends('layouts.admin.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Detail Dokumen Persil</h4>
                    <div class="card-tools">
                        <a href="{{ route('dokumen-persil.edit', $dokumen->dokumen_id) }}" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('persil.show', $dokumen->persil_id) }}" class="btn btn-sm btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali ke Persil
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
                                        <small class="text-muted">{{ $dokumen->persil->penilik->nama }}</small>
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

                    <!-- ===== FILE DOKUMEN SECTION ===== -->
                    @if ($mediaFiles->count() > 0)
                        <div class="row mt-4">
                            <div class="col-12">
                                <h5>File Dokumen ({{ $mediaFiles->count() }})</h5>
                                <div class="row">
                                    @foreach ($mediaFiles as $media)
                                        <div class="col-md-3 mb-3">
                                            <div class="card h-100">
                                                @if (in_array($media->mime_type, ['image/jpeg', 'image/jpg', 'image/png']))
                                                    <img src="{{ asset('storage/' . $media->file_url) }}"
                                                        class="card-img-top" style="height: 150px; object-fit: cover;"
                                                        alt="{{ $media->caption }}">
                                                @else
                                                    <div class="card-body text-center py-4">
                                                        @if ($media->mime_type == 'application/pdf')
                                                            <i class="fas fa-file-pdf fa-3x text-danger"></i>
                                                        @elseif(in_array($media->mime_type, [
                                                                'application/msword',
                                                                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                                                            ]))
                                                            <i class="fas fa-file-word fa-3x text-primary"></i>
                                                        @else
                                                            <i class="fas fa-file fa-3x text-muted"></i>
                                                        @endif
                                                    </div>
                                                @endif
                                                <div class="card-body">
                                                    <h6 class="card-title text-truncate" title="{{ $media->caption }}">
                                                        {{ $media->caption }}
                                                    </h6>
                                                    <p class="card-text small text-muted mb-2">
                                                        {{ strtoupper(pathinfo($media->file_url, PATHINFO_EXTENSION)) }}
                                                        <br>
                                                        {{ $media->created_at->format('d/m/Y H:i') }}
                                                    </p>
                                                    <div class="d-flex justify-content-between">
                                                        <a href="{{ route('dokumen-persil.download', $media->media_id) }}"
                                                            target="_blank" class="btn btn-sm btn-outline-primary">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="{{ route('dokumen-persil.download', $media->media_id) }}"
                                                            class="btn btn-sm btn-outline-success">
                                                            <i class="fas fa-download"></i>
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
                            <i class="fas fa-info-circle"></i> Tidak ada file dokumen terupload.
                        </div>
                    @endif
                    <!-- ===== END FILE DOKUMEN SECTION ===== -->
                </div>
            </div>
        </div>
    </div>
@endsection
