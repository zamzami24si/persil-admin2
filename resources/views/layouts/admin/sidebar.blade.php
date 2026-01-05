<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <!--begin::Sidebar Brand-->
    <div class="sidebar-brand">
        <a href="{{ url('/') }}" class="brand-link">
            <img src="{{ asset('assets-admin/assets/img/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image opacity-75 shadow" />
            <span class="brand-text fw-light">Bina Desa</span>
        </a>
    </div>
    <!--end::Sidebar Brand-->

    <!--begin::Sidebar Wrapper-->
    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" data-accordion="false">
                <!-- Dashboard Menu -->
                <li class="nav-item">
                    <a href="{{ url('/') }}" class="nav-link {{ request()->is('/') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-speedometer"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- Data Master Menu -->
                <li class="nav-item {{ request()->is('warga*') || request()->is('persil*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('warga*') || request()->is('persil*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-database"></i>
                        <p>
                            Data Master
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview" style="{{ request()->is('warga*') || request()->is('persil*') ? 'display: block;' : 'display: none;' }}">
                        <!-- Menu Warga -->
                        <li class="nav-item">
                            <a href="{{ route('warga.index') }}" class="nav-link {{ request()->is('warga*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-people-fill"></i>
                                <p>Data Warga</p>
                            </a>
                        </li>

                        <!-- Menu Persil -->
                        <li class="nav-item">
                            <a href="{{ route('persil.index') }}" class="nav-link {{ request()->is('persil*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-map-fill"></i>
                                <p>Data Persil</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Pertanahan Menu -->
                <li class="nav-item {{ request()->is('dokumen-persil*') || request()->is('peta-persil*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('dokumen-persil*') || request()->is('peta-persil*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-house-door"></i>
                        <p>
                            Pertanahan
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview" style="{{ request()->is('dokumen-persil*') || request()->is('peta-persil*') ? 'display: block;' : 'display: none;' }}">
                        <li class="nav-item">
                            <a href="{{ route('dokumen-persil.index') }}" class="nav-link {{ request()->is('dokumen-persil*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-file-earmark-text"></i>
                                <p>Dokumen Persil</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('peta-persil.index') }}" class="nav-link {{ request()->is('peta-persil*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-pin-map"></i>
                                <p>Peta Persil</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a href="{{ route('sengketa-persil.index') }}" class="nav-link {{ request()->is('sengketa-persil*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-shield-exclamation"></i>
                        <p>Sengketa Persil</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('jenis-penggunaan.index') }}" class="nav-link {{ request()->is('jenis-penggunaan*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-tags"></i>
                        <p>Jenis Penggunaan</p>
                    </a>
                </li>

                <!-- ============================================
                     USER MANAGEMENT MENU - HANYA UNTUK ADMIN
                ============================================ -->
                @if(Auth::check() && (Auth::user()->role === 'admin' || Auth::user()->role === 'super_admin'))
                <li class="nav-item">
                    <a href="{{ route('users.index') }}" class="nav-link {{ request()->is('users*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-people-fill"></i>
                        <p>User Management</p>
                    </a>
                </li>
                @endif
                <!-- ============================================
                     END USER MANAGEMENT MENU
                ============================================ -->

                <!-- Pengaturan Menu -->

            </ul>
        </nav>
    </div>
    <!--end::Sidebar Wrapper-->

    <!-- USER MENU di bagian bawah sidebar -->
    <div class="sidebar-footer mt-auto border-top border-secondary">
        @if(Auth::check())
            <div class="user-menu p-3">
                <div class="dropdown">
                    <!-- User Info Toggle -->
                    <a href="#" class="d-flex align-items-center text-decoration-none text-white dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <!-- User Avatar -->
                        

                        <!-- User Info -->
                        <div class="user-info">
                            <div class="user-name small">{{ Auth::user()->name }}</div>
                            <div class="user-role text-white-50 x-small">
                                {{ ucfirst(str_replace('_', ' ', Auth::user()->role)) }}
                            </div>
                        </div>
                    </a>

                    <!-- Dropdown Menu -->
                    <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0" style="min-width: 250px;">
                        <!-- User Header -->
                        <li class="dropdown-header text-bg-primary p-3">
                            <div class="d-flex align-items-center">
                                @if(Auth::user()->avatar && file_exists(storage_path('app/public/' . Auth::user()->avatar)))
                                    <img src="{{ asset('storage/' . Auth::user()->avatar) }}"
                                         class="rounded-circle shadow me-3" width="60" height="60" alt="User Avatar" />
                                @elseif(file_exists(public_path('assets-admin/assets/img/user2-160x160.jpg')))
                                    <img src="{{ asset('assets-admin/assets/img/user2-160x160.jpg') }}"
                                         class="rounded-circle shadow me-3" width="60" height="60" alt="User Image" />
                                @else
                                    <div class="avatar-placeholder rounded-circle bg-white text-primary d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px; font-size: 1.5rem;">
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

                        <!-- Profile Link -->
                        <li>
                            <a class="dropdown-item" href="#">
                                <i class="bi bi-person me-2"></i>
                                Profile
                            </a>
                        </li>



                        <!-- Tambahkan User Management link di dropdown jika user adalah admin -->
                        @if(Auth::check() && (Auth::user()->role === 'admin' || Auth::user()->role === 'super_admin'))
                        <li>
                            <a class="dropdown-item" href="{{ route('users.index') }}">
                                <i class="bi bi-people-fill me-2"></i>
                                User Management
                            </a>
                        </li>
                        @endif

                        <!-- Last Login Info -->
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
                </div>
            </div>
        @else
            <!-- Login Link when not authenticated -->
            <div class="p-3">
                <a href="{{ route('login') }}" class="btn btn-outline-light w-100">
                    <i class="bi bi-box-arrow-in-right me-1"></i>
                    Login
                </a>
            </div>
        @endif
    </div>
</aside>

<!-- CSS Fix untuk dropdown positioning dan hover aktif -->
<style>
    /* Fix untuk dropdown positioning di sidebar */
    .sidebar-footer .dropdown-menu {
        position: absolute !important;
        left: 0 !important;
        right: auto !important;
        bottom: 70px !important;
        top: auto !important;
        transform: none !important;
        margin-left: 15px;
        margin-bottom: 5px;
        z-index: 9999 !important;
    }

    @media (max-width: 767.98px) {
        .sidebar-footer .dropdown-menu {
            position: fixed !important;
            left: 15px !important;
            bottom: 70px !important;
            right: auto !important;
        }
    }

    /* User avatar styling */
    .user-avatar {
        width: 40px;
        height: 40px;
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
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
    }

    .user-info {
        flex: 1;
    }

    .user-name {
        font-weight: 600;
        font-size: 0.9rem;
        line-height: 1.2;
        color: white;
    }

    .user-role {
        font-size: 0.75rem;
        opacity: 0.8;
    }

    /* Dropdown styling */
    .dropdown-header.text-bg-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    }

    /* Dropdown toggle hover effect */
    .sidebar-footer .dropdown-toggle:hover {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 8px;
    }

    /* Memastikan dropdown di atas konten lainnya */
    .dropdown-menu {
        z-index: 1060 !important;
    }

    /* Styling untuk menu aktif */
    .nav-link.active {
        background-color: rgba(255, 255, 255, 0.1) !important;
        color: white !important;
        border-left: 3px solid #4e73df !important;
    }

    .nav-link:hover:not(.active) {
        background-color: rgba(255, 255, 255, 0.05) !important;
        color: #dee2e6 !important;
    }

    /* Styling untuk menu parent yang aktif (menu-open) */
    .nav-item.menu-open > .nav-link {
        background-color: rgba(255, 255, 255, 0.15) !important;
        color: white !important;
    }

    .nav-item.menu-open > .nav-link > .nav-arrow {
        transform: rotate(90deg) !important;
    }

    /* Styling untuk menu treeview */
    .nav-treeview {
        padding-left: 20px;
    }

    /* Styling untuk submenu yang aktif */
    .nav-treeview .nav-link.active {
        background-color: rgba(255, 255, 255, 0.15) !important;
        color: white !important;
        border-left: 3px solid #36b9cc !important;
    }

    /* Hover effect untuk submenu */
    .nav-treeview .nav-link:hover:not(.active) {
        background-color: rgba(255, 255, 255, 0.08) !important;
        color: #dee2e6 !important;
    }

    /* Icon arrow untuk menu terbuka */
    .nav-item.menu-open > .nav-link > .nav-arrow {
        transform: rotate(90deg);
    }

    /* Transisi untuk arrow */
    .nav-arrow {
        transition: transform 0.3s ease;
        float: right;
        margin-top: 3px;
    }
</style>

<!-- JavaScript untuk handle dropdown dan treeview -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle dropdown di sidebar footer
        const sidebarDropdownToggle = document.querySelector('.sidebar-footer .dropdown-toggle');
        const sidebarDropdownMenu = document.querySelector('.sidebar-footer .dropdown-menu');

        if (sidebarDropdownToggle && sidebarDropdownMenu) {
            sidebarDropdownToggle.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                // Toggle show class
                sidebarDropdownMenu.classList.toggle('show');

                // Tutup dropdown lainnya
                document.querySelectorAll('.dropdown-menu').forEach(function(menu) {
                    if (menu !== sidebarDropdownMenu) {
                        menu.classList.remove('show');
                    }
                });
            });

            // Tutup dropdown saat klik di luar
            document.addEventListener('click', function(e) {
                if (!sidebarDropdownToggle.contains(e.target) && !sidebarDropdownMenu.contains(e.target)) {
                    sidebarDropdownMenu.classList.remove('show');
                }
            });

            // Auto close dropdown saat item dipilih
            sidebarDropdownMenu.querySelectorAll('.dropdown-item').forEach(function(item) {
                item.addEventListener('click', function() {
                    sidebarDropdownMenu.classList.remove('show');
                });
            });
        }

        // Handle treeview menu (Data Master dan Pertanahan)
        const treeviewLinks = document.querySelectorAll('.nav-item > .nav-link[href="#"]');

        treeviewLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                const parentItem = this.closest('.nav-item');
                const submenu = this.nextElementSibling;
                const arrow = this.querySelector('.nav-arrow');

                if (submenu && submenu.classList.contains('nav-treeview')) {
                    // Toggle submenu display
                    if (submenu.style.display === 'block') {
                        submenu.style.display = 'none';
                        parentItem.classList.remove('menu-open');
                        if (arrow) {
                            arrow.style.transform = 'rotate(0deg)';
                        }
                    } else {
                        submenu.style.display = 'block';
                        parentItem.classList.add('menu-open');
                        if (arrow) {
                            arrow.style.transform = 'rotate(90deg)';
                        }
                    }
                }
            });
        });

        // Handle hover untuk menu
        const navLinks = document.querySelectorAll('.nav-link:not([href="#"])');

        navLinks.forEach(link => {
            link.addEventListener('mouseenter', function() {
                if (!this.classList.contains('active')) {
                    this.style.backgroundColor = 'rgba(255, 255, 255, 0.05)';
                }
            });

            link.addEventListener('mouseleave', function() {
                if (!this.classList.contains('active')) {
                    this.style.backgroundColor = '';
                }
            });
        });
    });
</script>
