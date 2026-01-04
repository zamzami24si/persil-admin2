{{-- resources/views/pages/sengketa-persil/show.blade.php --}}
@extends('layouts.admin.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Detail Sengketa Persil: {{ $sengketa->persil->kode_persil }}</h4>
                    <div class="card-tools">
                        <a href="{{ route('sengketa-persil.edit', $sengketa->sengketa_id) }}" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('sengketa-persil.index') }}" class="btn btn-sm btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Informasi Sengketa</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th width="40%">Kode Persil</th>
                                    <td>{{ $sengketa->persil->kode_persil }}</td>
                                </tr>
                                <tr>
                                    <th>Pemilik</th>
                                    <td>{{ $sengketa->persil->pemilik->nama }} ({{ $sengketa->persil->pemilik->no_ktp }})</td>
                                </tr>
                                <tr>
                                    <th>Pihak 1</th>
                                    <td>{{ $sengketa->pihak_1 }}</td>
                                </tr>
                                <tr>
                                    <th>Pihak 2</th>
                                    <td>{{ $sengketa->pihak_2 }}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        <span class="badge {{ $sengketa->status_badge_class }}">
                                            {{ $sengketa->status_label }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Jumlah File Bukti</th>
                                    <td>
                                        @if($mediaFiles->count() > 0)
                                            <span class="badge bg-primary">{{ $mediaFiles->count() }} file</span>
                                        @else
                                            <span class="badge bg-secondary">Tidak ada file</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Tanggal Dibuat</th>
                                    <td>{{ $sengketa->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Terakhir Diupdate</th>
                                    <td>{{ $sengketa->updated_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Detail Sengketa</h5>
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h6 class="card-title mb-0">Kronologi</h6>
                                </div>
                                <div class="card-body">
                                    <p class="mb-0">{{ $sengketa->kronologi }}</p>
                                </div>
                            </div>

                            @if($sengketa->penyelesaian)
                                <div class="card mt-3">
                                    <div class="card-header bg-success text-white">
                                        <h6 class="card-title mb-0">Penyelesaian</h6>
                                    </div>
                                    <div class="card-body">
                                        <p class="mb-0">{{ $sengketa->penyelesaian }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- ===== FILE BUKTI SECTION ===== -->
                    @if($mediaFiles->count() > 0)
                        <div class="row mt-4">
                            <div class="col-12">
                                <h5>File Bukti Sengketa ({{ $mediaFiles->count() }})</h5>
                                <div class="row">
                                    @foreach($mediaFiles as $media)
                                        <div class="col-md-3 mb-3">
                                            <div class="card h-100">
                                                @if(in_array($media->mime_type, ['image/jpeg', 'image/jpg', 'image/png']))
                                                    <img src="{{ asset('storage/' . $media->file_url) }}"
                                                         class="card-img-top"
                                                         style="height: 150px; object-fit: cover;"
                                                         alt="{{ $media->caption }}">
                                                @else
                                                    <div class="card-body text-center py-4">
                                                        @if($media->mime_type == 'application/pdf')
                                                            <i class="fas fa-file-pdf fa-3x text-danger"></i>
                                                        @elseif(in_array($media->mime_type, ['application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document']))
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
                                                        <a href="{{ route('sengketa-persil.download', $media->media_id) }}"
                                                           target="_blank"
                                                           class="btn btn-sm btn-outline-primary">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="{{ route('sengketa-persil.download', $media->media_id) }}"
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
                            <i class="fas fa-info-circle"></i> Tidak ada file bukti terupload untuk sengketa ini.
                        </div>
                    @endif
                    <!-- ===== END FILE BUKTI SECTION ===== -->

                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('persil.show', $sengketa->persil_id) }}" class="btn btn-info">
                                    <i class="fas fa-map"></i> Lihat Detail Persil
                                </a>
                                <div>
                                    <a href="{{ route('sengketa-persil.edit', $sengketa->sengketa_id) }}" class="btn btn-warning">
                                        <i class="fas fa-edit"></i> Edit Sengketa
                                    </a>
                                    <form action="{{ route('sengketa-persil.destroy', $sengketa->sengketa_id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger"
                                            onclick="return confirm('Yakin ingin menghapus data sengketa ini?')">
                                            <i class="fas fa-trash"></i> Hapus Sengketa
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
