{{-- resources/views/pages/jenis-penggunaan/edit.blade.php --}}
@extends('layouts.admin.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Edit Jenis Penggunaan: {{ $jenis->nama_penggunaan }}</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('jenis-penggunaan.update', $jenis->jenis_id) }}" method="POST">
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

                        <div class="mb-3">
                            <label for="nama_penggunaan" class="form-label">Nama Penggunaan <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama_penggunaan') is-invalid @enderror"
                                id="nama_penggunaan" name="nama_penggunaan" value="{{ old('nama_penggunaan', $jenis->nama_penggunaan) }}"
                                placeholder="Contoh: Pertanian, Permukiman, Perkebunan, dll" required maxlength="100">
                            @error('nama_penggunaan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <textarea class="form-control @error('keterangan') is-invalid @enderror"
                                id="keterangan" name="keterangan" rows="4"
                                placeholder="Masukkan keterangan tambahan tentang jenis penggunaan ini">{{ old('keterangan', $jenis->keterangan) }}</textarea>
                            @error('keterangan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title">Informasi</h6>
                                        <p class="mb-1"><strong>Jumlah Persil:</strong> {{ $jenis->jumlah_persil }}</p>
                                        <p class="mb-1"><strong>Dibuat:</strong> {{ $jenis->created_at->format('d/m/Y H:i') }}</p>
                                        <p class="mb-0"><strong>Diupdate:</strong> {{ $jenis->updated_at->format('d/m/Y H:i') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('jenis-penggunaan.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                            <div>
                                <a href="{{ route('jenis-penggunaan.show', $jenis->jenis_id) }}" class="btn btn-info">
                                    <i class="fas fa-eye"></i> Lihat Detail
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Update Jenis Penggunaan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
