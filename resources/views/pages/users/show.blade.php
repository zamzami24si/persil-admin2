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
                        <div class="col-md-4">
                            <!-- Foto Profil -->
                            <div class="text-center mb-4">
                                <div class="avatar-display mb-3">
                                    <img src="{{ $user->foto_profil_url }}"
                                         class="rounded-circle img-thumbnail"
                                         style="width: 200px; height: 200px; object-fit: cover;"
                                         alt="Foto Profil {{ $user->name }}">
                                </div>
                                <h5 class="mb-0">{{ $user->name }}</h5>
                                <p class="text-muted mb-2">{{ $user->email }}</p>
                                <span class="badge {{ $user->role_badge_class }}">
                                    {{ $user->role }}
                                </span>
                            </div>

                            <!-- Statistik Singkat -->
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">Statistik</h6>
                                    <div class="row text-center">
                                        <div class="col-6">
                                            <h4 class="mb-0">3</h4>
                                            <small class="text-muted">Login</small>
                                        </div>
                                        <div class="col-6">
                                            <h4 class="mb-0">12</h4>
                                            <small class="text-muted">Aktivitas</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <h5>Informasi User</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th width="40%">Nama Lengkap</th>
                                    <td>{{ $user->name }}</td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>{{ $user->email }}</td>
                                </tr>
                                <tr>
                                    <th>Role</th>
                                    <td>
                                        <span class="badge {{ $user->role_badge_class }}">
                                            {{ $user->role }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Status Email</th>
                                    <td>
                                        <span class="badge {{ $user->status_badge_class }}">
                                            <i class="fas {{ $user->email_verified_at ? 'fa-check-circle' : 'fa-clock' }} me-1"></i>
                                            {{ $user->status_label }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Tanggal Dibuat</th>
                                    <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Terakhir Diupdate</th>
                                    <td>{{ $user->updated_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Terakhir Login</th>
                                    <td>
                                        @if($user->last_login_at)
                                            {{ \Carbon\Carbon::parse($user->last_login_at)->format('d/m/Y H:i') }}
                                        @else
                                            <span class="text-muted">Belum pernah login</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>

                            <h5 class="mt-4">Aktivitas Terbaru</h5>
                            <div class="list-group">
                                @php
                                    $recentActivities = [
                                        ['activity' => 'Login ke sistem', 'time' => now()->subMinutes(30)],
                                        ['activity' => 'Mengedit data persil', 'time' => now()->subHours(2)],
                                        ['activity' => 'Mengupload file dokumen', 'time' => now()->subHours(5)],
                                    ];
                                @endphp

                                @foreach($recentActivities as $activity)
                                    <div class="list-group-item">
                                        <div class="d-flex w-100 justify-content-between">
                                            <p class="mb-1">{{ $activity['activity'] }}</p>
                                            <small class="text-muted">{{ $activity['time']->diffForHumans() }}</small>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-between border-top pt-4">
                                <div>
                                    <button class="btn btn-info" onclick="showUserHistory()">
                                        <i class="fas fa-history me-1"></i> Riwayat Aktivitas
                                    </button>
                                    @if(!$user->email_verified_at)
                                        <form action="{{ route('users.verify', $user->id) }}" method="POST" class="d-inline ms-2">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-success"
                                                    onclick="return confirm('Yakin ingin memverifikasi email user ini?')">
                                                <i class="fas fa-check-circle me-1"></i> Verifikasi Email
                                            </button>
                                        </form>
                                    @endif
                                </div>
                                <div>
                                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning">
                                        <i class="fas fa-edit me-1"></i> Edit User
                                    </a>
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger"
                                            onclick="return confirm('Yakin ingin menghapus user {{ $user->name }}?')">
                                            <i class="fas fa-trash me-1"></i> Hapus User
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

    <!-- Modal untuk riwayat aktivitas -->
    <div class="modal fade" id="historyModal" tabindex="-1" aria-labelledby="historyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="historyModalLabel">Riwayat Aktivitas User: {{ $user->name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Aktivitas</th>
                                    <th>Waktu</th>
                                    <th>IP Address</th>
                                    <th>Browser</th>
                                </tr>
                            </thead>
                            <tbody>
                                @for($i = 1; $i <= 10; $i++)
                                    <tr>
                                        <td>{{ $i }}</td>
                                        <td>Login ke sistem</td>
                                        <td>{{ now()->subDays($i)->format('d/m/Y H:i') }}</td>
                                        <td>192.168.1.{{ $i }}</td>
                                        <td>Chrome {{ 90 + $i }}</td>
                                    </tr>
                                @endfor
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function showUserHistory() {
        var historyModal = new bootstrap.Modal(document.getElementById('historyModal'));
        historyModal.show();
    }
</script>
@endpush

@push('styles')
<style>
    .avatar-display {
        width: 200px;
        height: 200px;
        border-radius: 50%;
        overflow: hidden;
        margin: 0 auto;
        border: 5px solid #e9ecef;
    }
    .avatar-display img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .list-group-item {
        border-left: none;
        border-right: none;
        border-color: #f8f9fa;
    }
    .list-group-item:first-child {
        border-top: none;
    }
</style>
@endpush
