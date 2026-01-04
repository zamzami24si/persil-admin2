<!DOCTYPE html>
<html lang="en">
<head>
    @include('layouts.admin.css')
    <title>@yield('title', 'AdminLTE v4 | Dashboard')</title>
</head>
<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">
        @include('layouts.admin.header')
        @include('layouts.admin.sidebar')

        <main class="app-main">
            @include('layouts.admin.content-header')

            <div class="app-content">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </div>
        </main>

        @include('layouts.admin.footer')
    </div>

    @include('layouts.admin.js')
    @yield('scripts')
</body>
</html>
