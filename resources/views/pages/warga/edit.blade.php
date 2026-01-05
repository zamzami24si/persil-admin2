{{-- resources/views/pages/warga/edit.blade.php --}}
@extends('layouts.admin.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Edit Data Warga</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('warga.update', $warga->warga_id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="row mb-4">
                            <div class="col-md-3">
                                <label class="form-label d-block">Foto Saat Ini</label>
                                <img src="{{ $warga->foto_url }}" alt="Preview" class="img-thumbnail rounded" style="max-height: 150px">
                            </div>
                            <div class="col-md-9">
                                <label for="foto" class="form-label">Ganti Foto</label>
                                <input type="file" class="form-control @error('foto') is-invalid @enderror"
                                       id="foto" name="foto" accept="image/*">
                                <div class="form-text text-muted">Biarkan kosong jika tidak ingin mengganti foto.</div>
                                @error('foto')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="no_ktp" class="form-label">No KTP <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="no_ktp" name="no_ktp" value="{{ old('no_ktp', $warga->no_ktp) }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nama" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nama" name="nama" value="{{ old('nama', $warga->nama) }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="jenis_kelamin" class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                                    <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" required>
                                        <option value="L" {{ old('jenis_kelamin', $warga->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="P" {{ old('jenis_kelamin', $warga->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="agama" class="form-label">Agama <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="agama" name="agama" value="{{ old('agama', $warga->agama) }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="pekerjaan" class="form-label">Pekerjaan <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="pekerjaan" name="pekerjaan" value="{{ old('pekerjaan', $warga->pekerjaan) }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="telp" class="form-label">Telepon</label>
                                    <input type="text" class="form-control" id="telp" name="telp" value="{{ old('telp', $warga->telp) }}">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $warga->email) }}">
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('warga.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
