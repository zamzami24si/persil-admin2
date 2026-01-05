<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom fixed-top">
    <div class="container-fluid">
        <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <a class="navbar-brand d-none d-lg-block" href="{{ route('dashboard') }}">
            <i class="fas fa-home me-2 text-primary"></i>
            <span class="brand-text fw-bold text-primary">Persil</span>
            <small class="text-muted ms-2">Admin Panel</small>
        </a>

        <a class="navbar-brand d-lg-none" href="{{ route('dashboard') }}">
            <i class="fas fa-home me-2 text-primary"></i>
            <span class="fw-bold text-primary">Bina Desa</span>
        </a>

        <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center p-2" href="#"
                       id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">

                        <div class="me-2">
                            @if(Auth::user()->avatar)
                                {{-- PERBAIKAN DI SINI: Tambahkan ->file_url --}}
                                <img src="{{ asset('storage/' . Auth::user()->avatar->file_url) }}"
                                     class="rounded-circle" width="36" height="36"
                                     style="object-fit: cover;" alt="User Avatar">
                            @else
                                <div class="avatar-placeholder rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                                     style="width: 36px; height: 36px; font-size: 16px;">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>

                        <div class="user-info d-none d-lg-block me-1">
                            <div class="user-name fw-bold">{{ Auth::user()->name }}</div>
                            <div class="user-role small text-muted">
                                @php
                                    $roleLabels = [
                                        'super_admin' => 'Super Admin',
                                        'admin' => 'Administrator',
                                        'user' => 'User'
                                    ];
                                @endphp
                                {{ $roleLabels[Auth::user()->role] ?? Auth::user()->role }}
                            </div>
                        </div>

                        <i class="fas fa-chevron-down ms-1"></i>
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0" style="min-width: 280px;">
                        <li class="dropdown-header bg-light py-3">
                            <div class="d-flex align-items-center">

                                <div class="me-3">
                                    @if(Auth::user()->avatar)
                                        {{-- PERBAIKAN DI SINI: Tambahkan ->file_url --}}
                                        <img src="{{ asset('storage/' . Auth::user()->avatar->file_url) }}"
                                             class="rounded-circle" width="60" height="60"
                                             style="object-fit: cover;" alt="User Avatar">
                                    @else
                                        <div class="avatar-placeholder rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                                             style="width: 60px; height: 60px; font-size: 24px;">
                                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                        </div>
                                    @endif
                                </div>

                                <div>
                                    <h6 class="mb-1">{{ Auth::user()->name }}</h6>
                                    <p class="small text-muted mb-1">{{ Auth::user()->email }}</p>
                                    <span class="badge bg-primary">
                                        {{ $roleLabels[Auth::user()->role] ?? Auth::user()->role }}
                                    </span>
                                </div>
                            </div>
                        </li>

                        <li><hr class="dropdown-divider my-2"></li>

                        @if(Auth::check() && in_array(Auth::user()->role, ['admin', 'super_admin']))
                        <li>
                            <a class="dropdown-item py-2" href="{{ route('users.index') }}">
                                <i class="fas fa-users-cog me-2 text-primary"></i>
                                User Management
                            </a>
                        </li>
                        <li><hr class="dropdown-divider my-2"></li>
                        @endif

                        <li>
                            <form action="{{ route('auth.logout') }}" method="POST" class="mb-0">
                                @csrf
                                <button type="submit" class="dropdown-item py-2 border-0 bg-transparent w-100 text-start">
                                    <i class="fas fa-sign-out-alt me-2 text-danger"></i>
                                    <span class="text-danger">Logout</span>
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<style>
.navbar.fixed-top {
    position: fixed;
    top: 0;
    right: 0;
    left: 0;
    z-index: 1030;
    height: 64px;
    padding-top: 8px;
    padding-bottom: 8px;
}

body {
    padding-top: 64px;
}

.user-info .user-name {
    font-size: 0.95rem;
    line-height: 1.2;
}

.user-info .user-role {
    font-size: 0.8rem;
    opacity: 0.8;
}

.avatar-placeholder {
    font-weight: bold;
    display: flex;
    align-items: center;
    justify-content: center;
}

.dropdown-menu {
    margin-top: 8px !important;
    border: 1px solid rgba(0,0,0,0.1);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.dropdown-item:hover {
    background-color: #f8f9fa;
}

/* Responsive fixes */
@media (max-width: 991.98px) {
    .navbar-brand.d-lg-none {
        display: flex !important;
        align-items: center;
    }

    .navbar-brand.d-none.d-lg-block {
        display: none !important;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Bootstrap dropdowns
    var dropdownElementList = [].slice.call(document.querySelectorAll('[data-bs-toggle="dropdown"]'));
    var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
        return new bootstrap.Dropdown(dropdownToggleEl);
    });

    // Fix dropdown closing issue
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.dropdown')) {
            var openDropdowns = document.querySelectorAll('.dropdown.show');
            openDropdowns.forEach(function(dropdown) {
                bootstrap.Dropdown.getInstance(dropdown.querySelector('.dropdown-toggle')).hide();
            });
        }
    });
});
</script>

{{-- tees --}}
