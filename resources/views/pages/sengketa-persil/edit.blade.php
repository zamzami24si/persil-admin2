@extends('layouts.admin.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Edit Sengketa Persil</h4>
                </div>
                <div class="card-body">

                    {{-- Alert Messages --}}
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show"><i class="fas fa-check-circle me-2"></i>{{ session('success') }} <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show"><i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }} <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
                    @endif
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show"><ul class="mb-0">@foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul> <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
                    @endif

                    <form action="{{ route('sengketa-persil.update', $sengketa->sengketa_id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Pihak 1</label>
                                <input type="text" class="form-control" name="pihak_1" value="{{ old('pihak_1', $sengketa->pihak_1) }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Pihak 2</label>
                                <input type="text" class="form-control" name="pihak_2" value="{{ old('pihak_2', $sengketa->pihak_2) }}" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kronologi</label>
                            <textarea class="form-control" name="kronologi" rows="4" required>{{ old('kronologi', $sengketa->kronologi) }}</textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status</label>
                                <select class="form-select" name="status" required>
                                    @foreach(['proses', 'selesai', 'dibatalkan'] as $st)
                                        <option value="{{ $st }}" {{ old('status', $sengketa->status) == $st ? 'selected' : '' }}>{{ ucfirst($st) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Penyelesaian</label>
                                <textarea class="form-control" name="penyelesaian" rows="2">{{ old('penyelesaian', $sengketa->penyelesaian) }}</textarea>
                            </div>
                        </div>

                        {{-- File Management --}}
                        <div class="card mb-3 border">
                            <div class="card-header bg-light d-flex justify-content-between">
                                <h6 class="mb-0">Bukti Sengketa</h6>
                                <small>{{ $mediaFiles->count() }} file terupload</small>
                            </div>
                            <div class="card-body">
                                {{-- List Existing Files --}}
                                @if($mediaFiles->count() > 0)
                                    <div class="row g-3 mb-4">
                                        @foreach($mediaFiles as $media)
                                            <div class="col-md-3 col-6">
                                                <div class="card h-100 border">
                                                    <div class="card-body p-2 text-center bg-light d-flex align-items-center justify-content-center" style="height:100px">
                                                        @if(str_starts_with($media->mime_type, 'image/'))
                                                            <a href="{{ route('sengketa-persil.preview', $media->media_id) }}" target="_blank">
                                                                <img src="{{ asset('storage/' . $media->file_url) }}" class="img-fluid" style="max-height:100%" alt="bukti">
                                                            </a>
                                                        @else
                                                            <a href="{{ route('sengketa-persil.preview', $media->media_id) }}" target="_blank">
                                                                <i class="fas fa-file-alt fa-3x text-secondary"></i>
                                                            </a>
                                                        @endif
                                                    </div>
                                                    <div class="card-footer bg-white p-2 border-top-0">
                                                        <div class="text-truncate small mb-1">{{ $media->caption }}</div>
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input" type="checkbox" name="delete_media[]" value="{{ $media->media_id }}" id="del_{{$media->media_id}}">
                                                            <label class="form-check-label small text-danger fw-bold" for="del_{{$media->media_id}}">Hapus</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Upload Bukti Baru</label>
                                    <input type="file" class="form-control" name="bukti_files[]" multiple accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('sengketa-persil.index') }}" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
