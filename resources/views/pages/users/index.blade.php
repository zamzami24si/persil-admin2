{{-- resources/views/pages/users/index.blade.php --}}
@extends('layouts.admin.app')

@section('title', 'Bina Desa | Manajemen User')
@section('page_title', 'Manajemen User')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom py-3">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-0 fw-bold">
                    <i class="fas fa-users text-primary me-2"></i>Manajemen User
                </h4>
                <small class="text-muted">Kelola data user dan hak akses</small>
            </div>
            <div class="card-tools">
                <a href="{{ route('users.create') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus me-1"></i> Tambah User
                </a>
            </div>
        </div>
    </div>
    <div class="card-body p-4">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <div class="d-flex align-items-center">
                    <i class="fas fa-check-circle me-2 fs-4"></i>
                    <div>{{ session('success') }}</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <div class="d-flex align-items-center">
                    <i class="fas fa-exclamation-circle me-2 fs-4"></i>
                    <div>{{ session('error') }}</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- FILTER & SEARCH FORM --}}
        <form method="GET" action="{{ route('users.index') }}" class="mb-4">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Role</label>
                    <select name="role" class="form-select" onchange="this.form.submit()">
                        <option value="">Semua Role</option>
                        <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="super_admin" {{ request('role') == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                    </select>
                </div>

                <div class="col-md-5">
                    <label class="form-label fw-semibold">Pencarian</label>
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" value="{{ request('search') }}"
                            placeholder="Cari nama atau email...">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Search
                        </button>
                        @if (request('search'))
                            <a href="{{ request()->fullUrlWithQuery(['search' => null]) }}"
                                class="btn btn-outline-secondary">
                                Clear
                            </a>
                        @endif
                    </div>
                </div>

                <div class="col-md-2">
                    <a href="{{ route('users.index') }}" class="btn btn-secondary w-100">
                        <i class="fas fa-redo me-1"></i> Reset
                    </a>
                </div>

                @if (request('role') || request('search'))
                    <div class="col-12">
                        <div class="alert alert-info py-2">
                            <small>
                                <i class="fas fa-info-circle me-1"></i>
                                Filter aktif:
                                @if (request('role'))
                                    <span class="badge bg-primary me-2">
                                        Role: {{ request('role') }}
                                    </span>
                                @endif
                                @if (request('search'))
                                    <span class="badge bg-primary me-2">
                                        Pencarian: "{{ request('search') }}"
                                    </span>
                                @endif
                            </small>
                        </div>
                    </div>
                @endif
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Foto</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Terakhir Login</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td class="align-middle">{{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}</td>
                            <td class="align-middle">
                                <div class="avatar-table">
                                    <img src="{{ $user->foto_profil_url }}"
                                         class="rounded-circle"
                                         style="width: 40px; height: 40px; object-fit: cover;"
                                         alt="{{ $user->name }}"
                                         title="{{ $user->name }}">
                                </div>
                            </td>
                            <td class="align-middle">
                                <strong>{{ $user->name }}</strong>
                            </td>
                            <td class="align-middle">{{ $user->email }}</td>
                            <td class="align-middle">
                                <span class="badge {{ $user->role_badge_class }}">
                                    {{ $user->role }}
                                </span>
                            </td>
                            <td class="align-middle">
                                @if($user->email_verified_at)
                                    <span class="badge bg-success">
                                        <i class="fas fa-check-circle me-1"></i> Terverifikasi
                                    </span>
                                @else
                                    <span class="badge bg-warning">
                                        <i class="fas fa-clock me-1"></i> Belum Verifikasi
                                    </span>
                                @endif
                            </td>
                            <td class="align-middle">
                                @if($user->last_login_at)
                                    <small>{{ \Carbon\Carbon::parse($user->last_login_at)->format('d/m/Y H:i') }}</small>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="align-middle">
                                <div class="btn-group">
                                    <a href="{{ route('users.show', $user->id) }}"
                                        class="btn btn-sm btn-info"
                                        title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('users.edit', $user->id) }}"
                                        class="btn btn-sm btn-warning"
                                        title="Edit User">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Yakin ingin menghapus user {{ $user->name }}?')"
                                            title="Hapus User">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <div class="py-3">
                                    <i class="fas fa-user-slash fa-3x text-muted mb-3"></i>
                                    <h6 class="mb-1">
                                        @if (request('role') || request('search'))
                                            Tidak ada data user yang sesuai dengan filter
                                        @else
                                            Tidak ada data user
                                        @endif
                                    </h6>
                                    <p class="small mb-0">Klik tombol "Tambah User" untuk menambahkan data baru</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $users->links('pagination::bootstrap-5') }}
        </div>
    </div>
    <div class="card-footer bg-white border-top py-3">
        <div class="row">
            <div class="col-md-6">
                <small class="text-muted">
                    <i class="fas fa-info-circle me-1"></i>
                    Menampilkan {{ $users->firstItem() ?? 0 }} - {{ $users->lastItem() ?? 0 }} dari {{ $users->total() }} data
                </small>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .avatar-table {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        overflow: hidden;
    }
    .avatar-table img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
</style>
@endpush
