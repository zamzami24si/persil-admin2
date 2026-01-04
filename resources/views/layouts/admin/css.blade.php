<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<!--begin::Accessibility Meta Tags-->
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
<meta name="color-scheme" content="light dark" />
<meta name="theme-color" content="#007bff" media="(prefers-color-scheme: light)" />
<meta name="theme-color" content="#1a1a1a" media="(prefers-color-scheme: dark)" />
<!--end::Accessibility Meta Tags-->

<!--begin::Primary Meta Tags-->
<meta name="title" content="AdminLTE v4 | Dashboard" />
<meta name="author" content="ColorlibHQ" />
<meta name="description" content="AdminLTE is a Free Bootstrap 5 Admin Dashboard" />
<!--end::Primary Meta Tags-->

<!--begin::Accessibility Features-->
<meta name="supported-color-schemes" content="light dark" />
<link rel="preload" href="{{ asset('assets-admin/css/adminlte.css') }}" as="style" />
<!--end::Accessibility Features-->

<!--begin::Fonts-->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" />
<!--end::Fonts-->

<!--begin::Third Party Plugin(OverlayScrollbars)-->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css" />
<!--end::Third Party Plugin(OverlayScrollbars)-->

<!--begin::Third Party Plugin(Bootstrap Icons)-->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" />
<!--end::Third Party Plugin(Bootstrap Icons)-->

<!--begin::Required Plugin(AdminLTE)-->
<link rel="stylesheet" href="{{ asset('assets-admin/css/adminlte.css') }}" />
<!--end::Required Plugin(AdminLTE)-->

<!-- apexcharts -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css" />

<!-- jsvectormap -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/css/jsvectormap.min.css" />
<style>
    /* Sidebar User Menu Styles */
.sidebar-footer {
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    margin-top: auto;
}

.user-menu .dropdown-toggle {
    padding: 10px 15px;
    border-radius: 8px;
    transition: all 0.3s;
}

.user-menu .dropdown-toggle:hover {
    background: rgba(255, 255, 255, 0.1);
}

.user-avatar img {
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

/* Dropdown positioning untuk sidebar */
.sidebar-footer .dropdown-menu {
    position: fixed !important;
    left: 15px !important;
    bottom: 70px !important;
    top: auto !important;
    transform: none !important;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .sidebar-footer .dropdown-menu {
        position: absolute !important;
        left: auto !important;
        right: 15px !important;
        bottom: 70px !important;
        top: auto !important;
    }
}

/* Memastikan dropdown tetap di atas konten */
.dropdown-menu {
    z-index: 1060 !important;
}

/* Style untuk treeview arrow */
.nav-arrow {
    float: right;
    margin-top: 3px;
    transition: transform 0.3s;
}

.nav-item.menu-open > .nav-link > .nav-arrow {
    transform: rotate(90deg);
}
</style>

