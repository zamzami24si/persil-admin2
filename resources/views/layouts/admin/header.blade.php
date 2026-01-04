<!-- NAVBAR FIXED - DROPDOWN BERFUNGSI -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top">
    <div class="container-fluid">
        <!-- Sidebar Toggle Button (Mobile) -->
        <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Brand Logo -->
        <a class="navbar-brand d-none d-lg-block" href="{{ route('dashboard') }}">
            <span class="brand-text fw-bold text-primary">Bina Desa</span>
            <small class="text-muted">Admin Panel</small>
        </a>

        <!-- Mobile Brand -->
        <a class="navbar-brand d-lg-none" href="{{ route('dashboard') }}">
            <span class="fw-bold text-primary">Bina Desa</span>
        </a>

        <!-- Navbar Content -->
        <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav ms-auto">
                <!-- Notifications -->
                <li class="nav-item dropdown">
                    <a class="nav-link position-relative" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-bell"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            3
                        </span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow">
                        <li><h6 class="dropdown-header">Notifications</h6></li>
                        <li><a class="dropdown-item" href="#"><i class="bi bi-person-plus me-2"></i> New user registered</a></li>
                        <li><a class="dropdown-item" href="#"><i class="bi bi-file-earmark-plus me-2"></i> New persil added</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-center" href="#">View all</a></li>
                    </ul>
                </li>

                <!-- User Menu -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#"
                       id="userDropdown" role="button" data-bs-toggle="dropdown">
                        <!-- User Avatar -->
                        <div class="user-avatar me-2">
                            @if(Auth::user()->avatar)
                                <img src="{{ asset('storage/' . Auth::user()->avatar) }}"
                                     class="rounded-circle" width="32" height="32" alt="User Avatar">
                            @else
                                <div class="avatar-placeholder rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                                     style="width: 32px; height: 32px;">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>

                        <!-- User Info -->
                        <div class="user-info d-none d-lg-block">
                            <div class="user-name">{{ Auth::user()->name }}</div>
                            <div class="user-role text-muted small">
                                {{ ucfirst(str_replace('_', ' ', Auth::user()->role)) }}
                            </div>
                        </div>

                        <i class="bi bi-chevron-down ms-1"></i>
                    </a>

                    <!-- Dropdown Menu -->
                    <ul class="dropdown-menu dropdown-menu-end shadow-lg" style="min-width: 280px;">
                        <!-- User Header -->
                        <li class="dropdown-header bg-primary text-white p-3">
                            <div class="d-flex align-items-center">
                                @if(Auth::user()->avatar)
                                    <img src="{{ asset('storage/' . Auth::user()->avatar) }}"
                                         class="rounded-circle me-3" width="60" height="60" alt="User Avatar">
                                @else
                                    <div class="avatar-placeholder rounded-circle bg-white text-primary d-flex align-items-center justify-content-center me-3"
                                         style="width: 60px; height: 60px; font-size: 1.5rem;">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </div>
                                @endif

                                <div>
                                    <h6 class="mb-0 text-white">{{ Auth::user()->name }}</h6>
                                    <p class="small mb-0 text-white-50">{{ Auth::user()->email }}</p>
                                    <p class="small mb-0 text-white-50">
                                        <i class="bi bi-person-badge me-1"></i>
                                        {{ ucfirst(str_replace('_', ' ', Auth::user()->role)) }}
                                    </p>
                                </div>
                            </div>
                        </li>

                        <li><hr class="dropdown-divider"></li>

                        <!-- Menu Items -->
                        <li>
                            <a class="dropdown-item" href="#">
                                <i class="bi bi-person me-2"></i>
                                Profile
                            </a>
                        </li>

                        <li>
                            <a class="dropdown-item" href="#">
                                <i class="bi bi-gear me-2"></i>
                                Settings
                            </a>
                        </li>

                        <!-- User Management Link (for Admin/Super Admin) -->
                        @if(Auth::check() && in_array(Auth::user()->role, ['admin', 'super_admin']))
                        <li>
                            <a class="dropdown-item" href="{{ route('users.index') }}">
                                <i class="bi bi-people-fill me-2"></i>
                                User Management
                            </a>
                        </li>
                        @endif

                        <!-- Last Login -->
                        <li>
                            <div class="dropdown-item text-muted small">
                                <i class="bi bi-clock-history me-2"></i>
                                Last login: {{ session('last_login') ? \Carbon\Carbon::parse(session('last_login'))->format('d/m/Y H:i') : 'N/A' }}
                            </div>
                        </li>

                        <li><hr class="dropdown-divider"></li>

                        <!-- Logout -->
                        <li>
                            <form action="{{ route('auth.logout') }}" method="POST" class="dropdown-item p-0">
                                @csrf
                                <button type="submit" class="btn btn-link text-decoration-none w-100 text-start p-2">
                                    <i class="bi bi-box-arrow-right me-2"></i>
                                    Sign out
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- CSS FIX untuk dropdown positioning -->
<style>
/* ====================
   FIX DROPDOWN POSITIONING
==================== */
/* Pastikan navbar fixed */
.navbar.fixed-top {
    position: fixed;
    top: 0;
    right: 0;
    left: 0;
    z-index: 1030;
    height: 60px;
}

/* Dropdown menu positioning */
.dropdown-menu {
    position: absolute !important;
    top: 100% !important;
    left: auto !important;
    right: 0 !important;
    z-index: 1000 !important;
    margin-top: 0.125rem !important;
}

/* User menu styling */
.user-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
}

.user-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.avatar-placeholder {
    font-weight: bold;
    display: flex;
    align-items: center;
    justify-content: center;
}

.user-info .user-name {
    font-weight: 600;
    font-size: 0.9rem;
    line-height: 1.2;
}

.user-info .user-role {
    font-size: 0.75rem;
    opacity: 0.7;
}

/* Dropdown header styling */
.dropdown-header.bg-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
}

/* Adjust content untuk fixed navbar */
body {
    padding-top: 60px;
}

/* ====================
   RESPONSIVE FIXES
==================== */
@media (max-width: 991.98px) {
    /* Mobile adjustments */
    .navbar-brand.d-lg-none {
        display: block !important;
    }

    .navbar-brand.d-none.d-lg-block {
        display: none !important;
    }

    .navbar-toggler.d-lg-none {
        display: block !important;
    }

    /* Dropdown positioning di mobile */
    .dropdown-menu {
        position: fixed !important;
        top: 60px !important;
        left: 50% !important;
        right: auto !important;
        transform: translateX(-50%) !important;
        width: 90% !important;
        max-width: 300px !important;
    }
}
</style>

<!-- JavaScript untuk handle dropdown -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Bootstrap 5 dropdowns
    var dropdownElementList = [].slice.call(document.querySelectorAll('[data-bs-toggle="dropdown"]'))
    var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
        return new bootstrap.Dropdown(dropdownToggleEl)
    });

    // Prevent dropdown close when clicking inside
    document.querySelectorAll('.dropdown-menu').forEach(function(element) {
        element.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    });
});
</script>
