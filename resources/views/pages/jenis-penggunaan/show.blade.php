{{-- resources/views/pages/jenis-penggunaan/show.blade.php --}}
@extends('layouts.admin.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Detail Jenis Penggunaan: {{ $jenis->nama_penggunaan }}</h4>
                    <div class="card-tools">
                        <a href="{{ route('jenis-penggunaan.edit', $jenis->jenis_id) }}" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('jenis-penggunaan.index') }}" class="btn btn-sm btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Informasi Jenis Penggunaan</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th width="40%">Nama Penggunaan</th>
                                    <td>{{ $jenis->nama_penggunaan }}</td>
                                </tr>
                                <tr>
                                    <th>Jumlah Persil</th>
                                    <td>
                                        <span class="badge bg-info">{{ $jenis->persils_count }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Tanggal Dibuat</th>
                                    <td>{{ $jenis->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Terakhir Diupdate</th>
                                    <td>{{ $jenis->updated_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Keterangan</h5>
                            @if ($jenis->keterangan)
                                <div class="card">
                                    <div class="card-body">
                                        <p class="mb-0">{{ $jenis->keterangan }}</p>
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i> Tidak ada keterangan untuk jenis penggunaan ini.
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Daftar Persil dengan Jenis Penggunaan Ini --}}
                    @if ($jenis->persils_count > 0)
                        <div class="row mt-4">
                            <div class="col-12">
                                <h5>Daftar Persil dengan Jenis Penggunaan "{{ $jenis->nama_penggunaan }}"</h5>
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Kode Persil</th>
                                                <th>Penilik</th>
                                                <th>Luas (m²)</th>
                                                <th>Alamat</th>
                                                <th>RT/RW</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($jenis->persils as $persil)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $persil->kode_persil }}</td>
                                                    <td>{{ $persil->penilik->nama }}</td>
                                                    <td>{{ number_format($persil->luas_m2, 2) }}</td>
                                                    <td>{{ Str::limit($persil->alamat_lahan, 50) }}</td>
                                                    <td>{{ $persil->rt }}/{{ $persil->rw }}</td>
                                                    <td>
                                                        <a href="{{ route('persil.show', $persil->persil_id) }}"
                                                            class="btn btn-sm btn-info" title="Lihat Detail Persil">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    Belum ada persil yang menggunakan jenis penggunaan "{{ $jenis->nama_penggunaan }}".
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('jenis-penggunaan.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Kembali ke Daftar
                                </a>
                                <div>
                                    <a href="{{ route('jenis-penggunaan.edit', $jenis->jenis_id) }}"
                                        class="btn btn-warning">
                                        <i class="fas fa-edit"></i> Edit Jenis Penggunaan
                                    </a>
                                    <form action="{{ route('jenis-penggunaan.destroy', $jenis->jenis_id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger"
                                            onclick="return confirm('Yakin ingin menghapus jenis penggunaan {{ $jenis->nama_penggunaan }}?')">
                                            <i class="fas fa-trash"></i> Hapus Jenis Penggunaan
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
