@extends('layouts.admin.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Tambah Sengketa Persil: {{ $persil->kode_persil }}</h4>
                </div>
                <div class="card-body">

                    {{-- Alert Messages --}}
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('sengketa-persil.store', $persil->persil_id) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="pihak_1" class="form-label">Pihak 1 (Penggugat) <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="pihak_1" value="{{ old('pihak_1') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="pihak_2" class="form-label">Pihak 2 (Tergugat) <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="pihak_2" value="{{ old('pihak_2') }}" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="kronologi" class="form-label">Kronologi <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="kronologi" rows="4" required>{{ old('kronologi') }}</textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-select" name="status" required>
                                    <option value="proses" {{ old('status') == 'proses' ? 'selected' : '' }}>Proses</option>
                                    <option value="selesai" {{ old('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                    <option value="dibatalkan" {{ old('status') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="penyelesaian" class="form-label">Penyelesaian (Jika ada)</label>
                                <textarea class="form-control" name="penyelesaian" rows="2">{{ old('penyelesaian') }}</textarea>
                            </div>
                        </div>

                        {{-- Upload Bukti --}}
                        <div class="card mb-3 border">
                            <div class="card-header bg-light"><h6 class="mb-0">Upload Bukti Sengketa</h6></div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Pilih File Bukti</label>
                                    <input type="file" class="form-control" name="bukti_files[]" multiple accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                                    <div class="form-text">Format: PDF, JPG, PNG, DOC. Maks 5MB.</div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('sengketa-persil.index') }}" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary">Simpan Data</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
