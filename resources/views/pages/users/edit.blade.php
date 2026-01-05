{{-- resources/views/pages/users/edit.blade.php --}}
@extends('layouts.admin.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0 fw-bold">
                                <i class="fas fa-edit text-warning me-2"></i>Edit User: {{ $user->name }}
                            </h4>
                            <small class="text-muted">Perbarui informasi user</small>
                        </div>
                        <a href="{{ route('users.show', $user->id) }}" class="btn btn-sm btn-info">
                            <i class="fas fa-eye me-1"></i> Lihat Detail
                        </a>
                    </div>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data" id="userForm">
                        @csrf
                        @method('PUT')

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
                                                <img src="{{ $user->avatar_url }}"
                                                     class="rounded-circle img-thumbnail"
                                                     style="width: 150px; height: 150px; object-fit: cover;"
                                                     alt="Foto Profil {{ $user->name }}"
                                                     onerror="this.src='{{ asset('assets/img/default-avatar.png') }}'">
                                            </div>
                                            <button type="button" class="btn btn-outline-primary" onclick="document.getElementById('avatar').click()">
                                                <i class="fas fa-upload me-1"></i> Ganti Foto
                                            </button>
                                            @if($user->avatar)
                                                <button type="button" class="btn btn-outline-danger mt-2" onclick="hapusFotoProfil()">
                                                    <i class="fas fa-trash me-1"></i> Hapus Foto
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="mb-3">
                                            <label for="avatar" class="form-label fw-semibold">
                                                Upload Foto Profil Baru
                                            </label>
                                            <input type="file" class="form-control @error('avatar') is-invalid @enderror"
                                                   id="avatar" name="avatar"
                                                   accept="image/jpeg,image/png,image/gif"
                                                   onchange="previewImage(this)">
                                            <div class="form-text small">
                                                <i class="fas fa-info-circle me-1"></i>
                                                Biarkan kosong jika tidak ingin mengubah. Ukuran maksimal 2MB.
                                                Format: JPG, PNG, GIF
                                            </div>
                                            @error('avatar')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        @if($user->avatar)
                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="delete_avatar" id="delete_avatar" value="1">
                                                <label class="form-check-label text-danger fw-medium" for="delete_avatar">
                                                    <i class="fas fa-trash-alt me-1"></i> Hapus foto profil saat menyimpan
                                                </label>
                                            </div>
                                        </div>
                                        @endif
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
                                           id="name" name="name" value="{{ old('name', $user->name) }}"
                                           placeholder="Masukkan nama lengkap" required>
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
                                           id="email" name="email" value="{{ old('email', $user->email) }}"
                                           placeholder="Masukkan email" required>
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
                                        Password
                                    </label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                           id="password" name="password"
                                           placeholder="Kosongkan jika tidak ingin mengubah">
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
                                        Konfirmasi Password
                                    </label>
                                    <input type="password" class="form-control"
                                           id="password_confirmation" name="password_confirmation"
                                           placeholder="Konfirmasi password">
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
                                <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>User</option>
                                <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="super_admin" {{ old('role', $user->role) == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                            </select>
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
                                    <i class="fas fa-save me-1"></i> Update User
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

    // Hapus foto profil dengan checkbox
    function hapusFotoProfil() {
        if (confirm('Yakin ingin menghapus foto profil?')) {
            document.getElementById('delete_avatar').checked = true;
            document.getElementById('avatarPreview').querySelector('img').src = '{{ asset("assets/img/default-avatar.png") }}';

            // Reset file input
            document.getElementById('avatar').value = '';

            // Show alert
            const alertDiv = document.createElement('div');
            alertDiv.className = 'alert alert-info alert-dismissible fade show mt-3';
            alertDiv.innerHTML = `
                <div class="d-flex align-items-center">
                    <i class="fas fa-info-circle me-2"></i>
                    <div>Foto profil akan dihapus saat Anda menyimpan perubahan.</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            `;

            document.querySelector('.card-body').insertBefore(alertDiv, document.querySelector('.card-body').firstChild);
        }
    }

    // Validasi form
    document.getElementById('userForm').addEventListener('submit', function(e) {
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('password_confirmation').value;
        const submitBtn = document.getElementById('submitBtn');

        if (password && password !== confirmPassword) {
            e.preventDefault();
            alert('Password dan Konfirmasi Password tidak sama!');
            return false;
        }

        // Validasi file size
        const avatarInput = document.getElementById('avatar');
        if (avatarInput.files.length > 0) {
            const fileSize = avatarInput.files[0].size;
            const maxSize = 2 * 1024 * 1024; // 2MB
            if (fileSize > maxSize) {
                e.preventDefault();
                alert('Ukuran file terlalu besar. Maksimal 2MB.');
                return false;
            }
        }

        // Disable button untuk mencegah double submit
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Menyimpan...';

        // Juga disable semua form inputs
        const formInputs = this.querySelectorAll('input, select, button, textarea');
        formInputs.forEach(input => {
            if (input !== submitBtn) {
                input.disabled = true;
            }
        });
    });

    // Reset form handler
    document.querySelector('button[type="reset"]').addEventListener('click', function() {
        document.getElementById('avatarPreview').querySelector('img').src = '{{ $user->avatar_url }}';
        if (document.getElementById('delete_avatar')) {
            document.getElementById('delete_avatar').checked = false;
        }
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
        transition: border-color 0.3s ease;
    }
    .avatar-preview:hover {
        border-color: #0d6efd;
    }
    .avatar-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }
    .avatar-preview:hover img {
        transform: scale(1.05);
    }
    .form-text {
        font-size: 0.85rem;
    }
    .badge {
        font-size: 0.875rem;
        padding: 0.5rem 0.75rem;
    }
</style>
@endpush
