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
                            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
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
                        <th class="text-center">No</th>
                        <th class="text-center">Foto</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th class="text-center">Role</th>
                        <th class="text-center" style="min-width: 160px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td class="text-center align-middle" data-label="No">
                                {{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}
                            </td>
                            <td class="text-center align-middle" data-label="Foto">
                                <div class="d-flex justify-content-center">
                                    <div class="avatar-table">
                                        @php
                                            // DEBUGBAR: Cek apakah avatar_url ada
                                            // Hapus komentar ini untuk debug:
                                            // {{-- DEBUG: {{ $user->avatar_url }} --}}
                                        @endphp

                                        <img src="{{ $user->avatar_url }}"
                                             class="rounded-circle shadow-sm user-avatar"
                                             alt="{{ $user->name }}"
                                             title="{{ $user->name }}"
                                             onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&color=7F9CF5&background=EBF4FF'">
                                    </div>
                                </div>
                            </td>
                            <td class="align-middle" data-label="Nama">
                                <div class="d-flex flex-column">
                                    <strong class="text-dark">{{ $user->name }}</strong>
                                    <small class="text-muted">ID: {{ $user->id }}</small>
                                </div>
                            </td>
                            <td class="align-middle" data-label="Email">
                                <a href="mailto:{{ $user->email }}" class="text-decoration-none">
                                    <i class="fas fa-envelope me-1 text-primary"></i>
                                    {{ $user->email }}
                                </a>
                            </td>
                            <td class="text-center align-middle" data-label="Role">
                                @php
                                    // Warna badge berdasarkan role
                                    $roleClass = match($user->role) {
                                        'super_admin' => 'bg-danger',
                                        'admin' => 'bg-warning text-dark',
                                        'user' => 'bg-info',
                                        default => 'bg-secondary'
                                    };

                                    // Label role
                                    $roleLabel = match($user->role) {
                                        'super_admin' => 'Super Admin',
                                        'admin' => 'Admin',
                                        'user' => 'User',
                                        default => $user->role
                                    };
                                @endphp
                                <span class="badge {{ $roleClass }} py-2 px-3">
                                    <i class="fas fa-user-tag me-1"></i>
                                    {{ $roleLabel }}
                                </span>
                            </td>

                            <td class="text-center align-middle" data-label="Aksi">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('users.show', $user->id) }}"
                                        class="btn btn-sm btn-info text-white d-flex align-items-center gap-1"
                                        title="Lihat Detail" data-bs-toggle="tooltip">
                                        <i class="fas fa-eye fa-sm"></i>
                                        <span class="d-none d-md-inline">Detail</span>
                                    </a>
                                    <a href="{{ route('users.edit', $user->id) }}"
                                        class="btn btn-sm btn-warning text-white d-flex align-items-center gap-1"
                                        title="Edit User" data-bs-toggle="tooltip">
                                        <i class="fas fa-edit fa-sm"></i>
                                        <span class="d-none d-md-inline">Edit</span>
                                    </a>
                                    @if(auth()->id() != $user->id)
                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger d-flex align-items-center gap-1"
                                                onclick="return confirm('Yakin ingin menghapus user {{ $user->name }}?')"
                                                title="Hapus User" data-bs-toggle="tooltip">
                                                <i class="fas fa-trash fa-sm"></i>
                                                <span class="d-none d-md-inline">Hapus</span>
                                            </button>
                                        </form>
                                    @else
                                        <button class="btn btn-sm btn-secondary d-flex align-items-center gap-1"
                                                title="Tidak dapat menghapus diri sendiri" data-bs-toggle="tooltip"
                                                disabled>
                                            <i class="fas fa-trash fa-sm"></i>
                                            <span class="d-none d-md-inline">Hapus</span>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                <div class="py-5">
                                    <i class="fas fa-user-slash fa-4x text-muted mb-3"></i>
                                    <h5 class="mb-2">
                                        @if (request('role') || request('search'))
                                            Tidak ada data user yang sesuai dengan filter
                                        @else
                                            Tidak ada data user
                                        @endif
                                    </h5>
                                    <p class="small mb-0">Klik tombol "Tambah User" untuk menambahkan data baru</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
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
            <div class="col-md-6 text-md-end">
                <small class="text-muted">
                    <i class="fas fa-users me-1"></i>
                    Total: {{ $users->total() }} user
                </small>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Initialize Bootstrap tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Debug: Cek jika ada error pada image loading
        document.querySelectorAll('.user-avatar').forEach(img => {
            img.addEventListener('error', function(e) {
                console.error('Gagal memuat foto:', this.src);

                // Fallback ke avatar berdasarkan inisial nama
                const userName = this.alt || 'User';
                const encodedName = encodeURIComponent(userName);
                this.src = `https://ui-avatars.com/api/?name=${encodedName}&color=7F9CF5&background=EBF4FF`;
            });

            // Debug: Tampilkan URL di console
            console.log('Avatar URL:', img.src, 'untuk user:', img.alt);
        });
    });

    // Prevent infinite refresh/loop
    let lastRequestTime = 0;
    const minRequestInterval = 2000; // 2 seconds minimum between requests

    document.addEventListener('click', function(e) {
        if (e.target.tagName === 'A' || e.target.tagName === 'BUTTON') {
            const currentTime = Date.now();
            if (currentTime - lastRequestTime < minRequestInterval) {
                e.preventDefault();
                console.log('Too many requests. Please wait...');
            }
            lastRequestTime = currentTime;
        }
    });
</script>
@endpush

@push('styles')
<style>
    .avatar-table {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        overflow: hidden;
        border: 2px solid #dee2e6;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f8f9fa;
    }
    .avatar-table:hover {
        border-color: #0d6efd;
        transform: scale(1.05);
    }
    .avatar-table img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }
    .user-avatar {
        width: 48px;
        height: 48px;
        object-fit: cover;
    }
    .table thead th {
        background-color: #f8f9fa;
        font-weight: 600;
        border-bottom: 2px solid #dee2e6;
        color: #495057;
    }
    .table tbody tr {
        transition: background-color 0.2s ease;
    }
    .table tbody tr:hover {
        background-color: rgba(13, 110, 253, 0.05);
    }
    .btn-sm {
        padding: 0.25rem 0.75rem;
        font-size: 0.875rem;
        border-radius: 0.375rem;
        min-width: 70px;
    }
    .badge {
        font-size: 0.75rem;
        font-weight: 500;
        letter-spacing: 0.5px;
    }
    .text-muted {
        font-size: 0.85rem;
    }

    /* Fix for action column */
    .table td:nth-child(6),
    .table th:nth-child(6) {
        min-width: 160px;
    }

    /* Fix for role column */
    .table td:nth-child(5),
    .table th:nth-child(5) {
        min-width: 110px;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .table-responsive {
            border: 0;
        }
        .table thead {
            display: none;
        }
        .table tbody tr {
            display: block;
            margin-bottom: 1rem;
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
        .table tbody td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 1rem;
            border-bottom: 1px solid #dee2e6;
            text-align: left;
        }
        .table tbody td:last-child {
            border-bottom: 0;
        }
        .table tbody td::before {
            content: attr(data-label);
            font-weight: 600;
            color: #6c757d;
            margin-right: 1rem;
            flex: 1;
            min-width: 80px;
        }
        .table tbody td:not(:first-child) {
            border-top: 1px solid #dee2e6;
        }

        /* Remove old data-label references for non-existent columns */
        .avatar-table {
            width: 40px;
            height: 40px;
        }
        .btn-sm span {
            display: none !important;
        }
        .btn-sm {
            min-width: 40px;
            justify-content: center;
        }
        .btn-sm i {
            margin-right: 0 !important;
        }

        /* Center avatar on mobile */
        .table tbody td[data-label="Foto"] {
            justify-content: center;
        }
        .table tbody td[data-label="Foto"]::before {
            display: none;
        }
    }
</style>
@endpush
