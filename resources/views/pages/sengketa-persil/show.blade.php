@extends('layouts.admin.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Detail Sengketa Persil</h4>
                    <div class="card-tools">
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
                                <tr><th width="30%">Kode Persil</th><td>{{ $sengketa->persil->kode_persil }}</td></tr>
                                <tr><th>Pihak 1</th><td>{{ $sengketa->pihak_1 }}</td></tr>
                                <tr><th>Pihak 2</th><td>{{ $sengketa->pihak_2 }}</td></tr>
                                <tr><th>Status</th><td><span class="badge {{ $sengketa->status_badge_class }}">{{ ucfirst($sengketa->status) }}</span></td></tr>
                                <tr><th>Kronologi</th><td>{{ $sengketa->kronologi }}</td></tr>
                                <tr><th>Penyelesaian</th><td>{{ $sengketa->penyelesaian ?? '-' }}</td></tr>
                            </table>
                        </div>
                    </div>

                    {{-- Bukti Sengketa --}}
                    @if($mediaFiles->count() > 0)
                        <div class="mt-4">
                            <h5>Bukti Sengketa ({{ $mediaFiles->count() }})</h5>
                            <div class="row">
                                @foreach($mediaFiles as $media)
                                    <div class="col-md-3 mb-4">
                                        <div class="card h-100 shadow-sm">
                                            <div class="card-body p-0 d-flex align-items-center justify-content-center bg-light" style="height: 150px;">
                                                @if(str_starts_with($media->mime_type, 'image/'))
                                                    <a href="{{ route('sengketa-persil.preview', $media->media_id) }}" target="_blank">
                                                        <img src="{{ asset('storage/' . $media->file_url) }}" class="img-fluid" style="max-height: 150px;" alt="Bukti">
                                                    </a>
                                                @else
                                                    <a href="{{ route('sengketa-persil.preview', $media->media_id) }}" target="_blank">
                                                        <i class="fas fa-file-alt fa-4x text-secondary"></i>
                                                    </a>
                                                @endif
                                            </div>
                                            <div class="card-footer bg-white p-2">
                                                <div class="text-truncate fw-bold mb-2">{{ $media->caption }}</div>
                                                <div class="d-flex gap-2">
                                                    <a href="{{ route('sengketa-persil.preview', $media->media_id) }}" target="_blank" class="btn btn-sm btn-outline-primary flex-fill">
                                                        <i class="fas fa-eye me-1"></i> Lihat
                                                    </a>
                                                    <a href="{{ route('sengketa-persil.download', $media->media_id) }}" class="btn btn-sm btn-outline-success flex-fill">
                                                        <i class="fas fa-download me-1"></i> Unduh
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="alert alert-info mt-4">Belum ada bukti yang diupload.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
