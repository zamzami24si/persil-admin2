<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom fixed-top shadow-sm">
    <div class="container-fluid">

        {{-- 1. HAMBURGER MENU (Hanya tampil di Mobile/Tablet) --}}
        <button class="navbar-toggler border-0 me-2 d-lg-none" type="button"
                data-bs-toggle="offcanvas"
                data-bs-target="#sidebarMenu"
                aria-controls="sidebarMenu"
                aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        {{-- 2. BRAND / LOGO (Kiri) --}}
        <a class="navbar-brand d-flex align-items-center" href="{{ route('dashboard') }}">
            <i class="fas fa-home me-2 text-primary"></i>
            <span class="brand-text fw-bold text-primary d-none d-sm-block">Persil Admin</span>
            <span class="brand-text fw-bold text-primary d-block d-sm-none">Persil</span>
        </a>

        {{-- 3. USER MENU (Kanan) --}}
        <ul class="navbar-nav ms-auto align-items-center">
            <li class="nav-item dropdown">

                {{-- Tombol Profil --}}
                <a class="nav-link dropdown-toggle d-flex align-items-center gap-2 p-1 pe-3 rounded-pill hover-bg-light"
                   href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">

                    {{-- Avatar --}}
                    <div class="position-relative">
                        {{-- PERBAIKAN: Gunakan Auth::user() dan sesuaikan ukuran untuk header --}}
                        @if(Auth::check() && Auth::user()->avatar)
                            <img src="{{ Auth::user()->avatar_url }}"
                                 class="rounded-circle border border-2 border-white shadow-sm"
                                 width="38" height="38"
                                 style="object-fit: cover;"
                                 alt="Avatar"
                                 onerror="this.src='{{ asset('assets/img/default-avatar.png') }}'">
                        @else
                            <div class="avatar-placeholder rounded-circle bg-primary text-white d-flex align-items-center justify-content-center shadow-sm"
                                 style="width: 38px; height: 38px; font-size: 16px; font-weight: bold;">
                                {{ Auth::check() ? strtoupper(substr(Auth::user()->name, 0, 1)) : 'U' }}
                            </div>
                        @endif
                    </div>

                    {{-- Nama User & Role (Tampil di Desktop) --}}
                    <div class="d-none d-md-block text-start lh-1">
                        <div class="fw-bold text-dark" style="font-size: 0.9rem;">
                            {{ Auth::check() ? Auth::user()->name : 'Guest' }}
                        </div>
                        <small class="text-muted" style="font-size: 0.75rem;">
                            {{ Auth::check() ? ucfirst(str_replace('_', ' ', Auth::user()->role)) : '' }}
                        </small>
                    </div>
                </a>

                {{-- Dropdown Content --}}
                <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 animate slideIn" aria-labelledby="userDropdown">

                    {{-- Info User (Khusus Mobile di dalam dropdown) --}}
                    <li>
                        <div class="px-3 py-2 text-center d-md-none border-bottom mb-2">
                            <div class="fw-bold">{{ Auth::check() ? Auth::user()->name : 'Guest' }}</div>
                            <small class="text-muted">{{ Auth::check() ? Auth::user()->email : '' }}</small>
                        </div>
                    </li>

                    {{-- Menu User Management (Admin Only) --}}
                    @if(Auth::check() && (Auth::user()->role === 'admin' || Auth::user()->role === 'super_admin'))
                    <li>
                        <a class="dropdown-item py-2" href="{{ route('users.index') }}">
                            <i class="fas fa-users-cog me-2 text-secondary w-25 text-center"></i> User Management
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    @endif

                    {{-- Menu Logout --}}
                    <li>
                        <form action="{{ route('auth.logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item py-2 text-danger">
                                <i class="fas fa-sign-out-alt me-2 text-danger w-25 text-center"></i> Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>

{{-- STYLE Header --}}
<style>
    /* Mengatur padding body agar konten tidak tertutup header */
    body {
        padding-top: 70px;
    }
    .navbar {
        height: 64px;
        z-index: 1030; /* Header di bawah Sidebar Mobile */
    }
    .hover-bg-light:hover {
        background-color: #f8f9fa;
        transition: background-color 0.2s;
    }
    /* Animasi Dropdown */
    @keyframes slideIn {
        from { transform: translateY(10px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
    .animate.slideIn {
        animation: slideIn 0.2s ease-out forwards;
    }
</style>
