{{-- resources/views/pages/users/show.blade.php --}}
@extends('layouts.admin.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0 fw-bold">
                                <i class="fas fa-user text-primary me-2"></i>Detail User: {{ $user->name }}
                            </h4>
                            <small class="text-muted">Informasi lengkap user</small>
                        </div>
                        <div class="btn-group">
                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit me-1"></i> Edit
                            </a>
                            <a href="{{ route('users.index') }}" class="btn btn-sm btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Kembali
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        {{-- Kolom Kiri: Foto & Statistik --}}
                        <div class="col-md-4">
                            <div class="text-center mb-4">
                                <div class="avatar-display mb-3">
                                    {{-- PERBAIKAN LOGIC FOTO --}}
                                    @if($user->foto_profil)
                                        <img src="{{ asset('storage/' . $user->foto_profil) }}"
                                             class="rounded-circle img-thumbnail"
                                             style="width: 200px; height: 200px; object-fit: cover;"
                                             alt="Foto Profil {{ $user->name }}">
                                    @else
                                        {{-- Fallback jika tidak ada foto (Pakai UI Avatars) --}}
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=random&size=200"
                                             class="rounded-circle img-thumbnail"
                                             style="width: 200px; height: 200px; object-fit: cover;"
                                             alt="Foto Default">
                                    @endif
                                </div>
                                <h5 class="mb-0 fw-bold">{{ $user->name }}</h5>
                                <p class="text-muted mb-2">{{ $user->email }}</p>

                                {{-- Badge Role --}}
                                @php
                                    $badgeClass = match($user->role) {
                                        'admin' => 'bg-danger',
                                        'user' => 'bg-primary',
                                        default => 'bg-secondary'
                                    };
                                @endphp
                                <span class="badge {{ $badgeClass }} px-3 py-2 rounded-pill">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </div>

                            <div class="card bg-light border-0">
                                <div class="card-body">
                                    <h6 class="card-title text-center text-muted mb-3">Statistik Aktivitas</h6>
                                    <div class="row text-center">
                                        <div class="col-6 border-end">
                                            <h4 class="mb-0 fw-bold">3</h4>
                                            <small class="text-muted">Login</small>
                                        </div>
                                        <div class="col-6">
                                            <h4 class="mb-0 fw-bold">12</h4>
                                            <small class="text-muted">Aksi</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Kolom Kanan: Detail Informasi --}}
                        <div class="col-md-8">
                            <h5 class="mb-3 border-bottom pb-2"><i class="fas fa-info-circle me-2"></i>Informasi Akun</h5>
                            <table class="table table-hover">
                                <tr>
                                    <th width="30%" class="bg-light">Nama Lengkap</th>
                                    <td>{{ $user->name }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Email</th>
                                    <td>
                                        {{ $user->email }}
                                        @if($user->email_verified_at)
                                            <i class="fas fa-check-circle text-success ms-1" title="Terverifikasi"></i>
                                        @else
                                            <i class="fas fa-exclamation-circle text-warning ms-1" title="Belum Verifikasi"></i>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Role / Peran</th>
                                    <td>{{ ucfirst($user->role) }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Status Akun</th>
                                    <td>
                                        <span class="badge bg-success">Aktif</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Bergabung Sejak</th>
                                    <td>{{ $user->created_at->translatedFormat('d F Y') }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Update Terakhir</th>
                                    <td>{{ $user->updated_at->diffForHumans() }}</td>
                                </tr>
                            </table>

                            <h5 class="mt-5 mb-3 border-bottom pb-2"><i class="fas fa-history me-2"></i>Riwayat Login Terakhir</h5>
                            <div class="table-responsive">
                                <table class="table table-sm table-striped text-sm">
                                    <thead>
                                        <tr>
                                            <th>Waktu</th>
                                            <th>IP Address</th>
                                            <th>Browser</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- Data Dummy Statis --}}
                                        <tr>
                                            <td>{{ now()->format('d/m/Y H:i') }}</td>
                                            <td>192.168.1.1</td>
                                            <td>Chrome on Windows</td>
                                        </tr>
                                        <tr>
                                            <td>{{ now()->subDays(1)->format('d/m/Y H:i') }}</td>
                                            <td>192.168.1.1</td>
                                            <td>Chrome on Windows</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .avatar-display {
        width: 200px;
        height: 200px;
        border-radius: 50%;
        overflow: hidden;
        margin: 0 auto;
        border: 5px solid #fff;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }
    .avatar-display img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .table th {
        font-weight: 600;
        color: #495057;
    }
</style>
@endpush
