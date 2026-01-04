{{-- resources/views/pages/jenis-penggunaan/create.blade.php --}}
@extends('layouts.admin.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Tambah Jenis Penggunaan Persil</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('jenis-penggunaan.store') }}" method="POST">
                        @csrf

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="mb-3">
                            <label for="nama_penggunaan" class="form-label">Nama Penggunaan <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama_penggunaan') is-invalid @enderror"
                                id="nama_penggunaan" name="nama_penggunaan" value="{{ old('nama_penggunaan') }}"
                                placeholder="Contoh: Pertanian, Permukiman, Perkebunan, dll" required maxlength="100">
                            @error('nama_penggunaan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <textarea class="form-control @error('keterangan') is-invalid @enderror"
                                id="keterangan" name="keterangan" rows="4"
                                placeholder="Masukkan keterangan tambahan tentang jenis penggunaan ini">{{ old('keterangan') }}</textarea>
                            @error('keterangan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('jenis-penggunaan.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan Jenis Penggunaan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
