{{-- resources/views/pages/users/create.blade.php --}}
@extends('layouts.admin.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0 fw-bold">
                                <i class="fas fa-user-plus text-primary me-2"></i>Tambah User Baru
                            </h4>
                            <small class="text-muted">Lengkapi form di bawah untuk menambahkan user baru</small>
                        </div>
                        <a href="{{ route('users.index') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data" id="userForm">
                        @csrf

                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-exclamation-circle me-2 fs-4"></i>
                                    <div>
                                        <strong class="mb-1">Terjadi kesalahan:</strong>
                                        <ul class="mb-0 mt-1 ps-3">
                                            @foreach($errors->all() as $error)
                                                <li class="small">{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <!-- Foto Profil -->
                        <div class="card mb-4 border">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">
                                    <i class="fas fa-camera me-2"></i>Foto Profil
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="text-center mb-3">
                                            <div class="avatar-preview mb-3" id="avatarPreview">
                                                <img src="{{ asset('assets/img/default-avatar.png') }}"
                                                     class="rounded-circle img-thumbnail"
                                                     style="width: 150px; height: 150px; object-fit: cover;"
                                                     alt="Preview Foto Profil">
                                            </div>
                                            <button type="button" class="btn btn-outline-primary" onclick="document.getElementById('foto_profil').click()">
                                                <i class="fas fa-upload me-1"></i> Pilih Foto
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="mb-3">
                                            <label for="foto_profil" class="form-label fw-semibold">
                                                Upload Foto Profil
                                            </label>
                                            <input type="file" class="form-control @error('foto_profil') is-invalid @enderror"
                                                   id="foto_profil" name="avatar"
                                                   accept=".jpg,.jpeg,.png,.gif"
                                                   onchange="previewImage(this)">
                                            <div class="form-text small">
                                                <i class="fas fa-info-circle me-1"></i>
                                                Ukuran maksimal 2MB. Format: JPG, PNG, GIF
                                            </div>
                                            @error('foto_profil')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label fw-semibold">
                                        <i class="fas fa-user me-1 text-primary"></i>
                                        Nama <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                           id="name" name="name" value="{{ old('name') }}"
                                           placeholder="Masukkan nama lengkap" required>
                                    <div class="form-text small">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Nama lengkap user
                                    </div>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label fw-semibold">
                                        <i class="fas fa-envelope me-1 text-primary"></i>
                                        Email <span class="text-danger">*</span>
                                    </label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                           id="email" name="email" value="{{ old('email') }}"
                                           placeholder="Masukkan email" required>
                                    <div class="form-text small">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Email yang valid dan belum terdaftar
                                    </div>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password" class="form-label fw-semibold">
                                        <i class="fas fa-lock me-1 text-primary"></i>
                                        Password <span class="text-danger">*</span>
                                    </label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                           id="password" name="password"
                                           placeholder="Masukkan password" required>
                                    <div class="form-text small">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Minimal 8 karakter
                                    </div>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label fw-semibold">
                                        <i class="fas fa-lock me-1 text-primary"></i>
                                        Konfirmasi Password <span class="text-danger">*</span>
                                    </label>
                                    <input type="password" class="form-control"
                                           id="password_confirmation" name="password_confirmation"
                                           placeholder="Konfirmasi password" required>
                                    <div class="form-text small">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Harus sama dengan password
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="role" class="form-label fw-semibold">
                                <i class="fas fa-user-tag me-1 text-primary"></i>
                                Role <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('role') is-invalid @enderror"
                                    id="role" name="role" required>
                                <option value="">Pilih Role</option>
                                <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="super_admin" {{ old('role') == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                            </select>
                            <div class="form-text small">
                                <i class="fas fa-info-circle me-1"></i>
                                Tentukan hak akses user
                            </div>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between border-top pt-4 mt-3">
                            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-1"></i> Batal
                            </a>
                            <div>
                                <button type="reset" class="btn btn-outline-warning me-2">
                                    <i class="fas fa-redo me-1"></i> Reset Form
                                </button>
                                <button type="submit" class="btn btn-primary" id="submitBtn">
                                    <i class="fas fa-save me-1"></i> Simpan User
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Preview image
    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            const preview = document.getElementById('avatarPreview').querySelector('img');

            reader.onload = function(e) {
                preview.src = e.target.result;
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    // Validasi form
    document.getElementById('userForm').addEventListener('submit', function(e) {
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('password_confirmation').value;
        const submitBtn = document.getElementById('submitBtn');

        if (password !== confirmPassword) {
            e.preventDefault();
            alert('Password dan Konfirmasi Password tidak sama!');
            return false;
        }

        // Disable button untuk mencegah double submit
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Menyimpan...';
    });
</script>
@endpush

@push('styles')
<style>
    .avatar-preview {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        overflow: hidden;
        margin: 0 auto;
        border: 3px solid #dee2e6;
    }
    .avatar-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
</style>
@endpush
