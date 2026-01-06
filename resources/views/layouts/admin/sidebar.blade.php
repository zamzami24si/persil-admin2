<aside class="app-sidebar bg-body-secondary shadow offcanvas-lg offcanvas-start" tabindex="-1" id="sidebarMenu" aria-labelledby="sidebarMenuLabel" data-bs-theme="dark">

    <div class="sidebar-brand">
        <a href="{{ url('/') }}" class="brand-link">
            <img src="{{ asset('assets-admin/assets/img/AdminLTELogo.png') }}" alt="Logo" class="brand-image opacity-75 shadow" />
            <span class="brand-text fw-light">Bina Desa</span>
        </a>
    </div>

    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" data-accordion="false">

                <li class="nav-item">
                    <a href="{{ url('/') }}" class="nav-link {{ request()->is('/') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-speedometer"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-item {{ request()->is('warga*') || request()->is('persil*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('warga*') || request()->is('persil*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-database"></i>
                        <p>
                            Data Master
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview" style="{{ request()->is('warga*') || request()->is('persil*') ? 'display: block;' : 'display: none;' }}">
                        <li class="nav-item">
                            <a href="{{ route('warga.index') }}" class="nav-link {{ request()->is('warga*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-people-fill"></i>
                                <p>Data Warga</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('persil.index') }}" class="nav-link {{ request()->is('persil*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-map-fill"></i>
                                <p>Data Persil</p>
                            </a>
                        </li>
                    </ul>
                </li>

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

                @if(Auth::check() && (Auth::user()->role === 'admin' || Auth::user()->role === 'super_admin'))
                <li class="nav-item">
                    <a href="{{ route('users.index') }}" class="nav-link {{ request()->is('users*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-people-fill"></i>
                        <p>User Management</p>
                    </a>
                </li>
                @endif

            </ul>
        </nav>
    </div>
</aside>

<style>
    /* FIX: Memastikan Sidebar ada di LAPISAN PALING ATAS saat mode mobile */
    #sidebarMenu {
        z-index: 1060 !important; /* Di atas Navbar (1030) */
    }

    /* Memastikan backdrop (layar gelap) juga menutupi navbar */
    .offcanvas-backdrop {
        z-index: 1050 !important;
    }
</style>

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
