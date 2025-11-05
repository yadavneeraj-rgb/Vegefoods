<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>@yield('title', 'Dashboard | Neeraj E-Commerce')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesbrand" name="author" />

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('admin-assets/images/favicon.ico') }}">

    <!-- Bootstrap CSS -->
    <link href="{{ asset('admin-assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet"
        type="text/css" />

    <!-- Icons CSS -->
    <link href="{{ asset('admin-assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- App CSS -->
    <link href="{{ asset('admin-assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />

    <!-- Add cache busting for CSS -->
    <link href="{{ asset('admin-assets/css/bootstrap.min.css') }}?v={{ time() }}" rel="stylesheet">
    <link href="{{ asset('admin-assets/css/icons.min.css') }}?v={{ time() }}" rel="stylesheet">
    <link href="{{ asset('admin-assets/css/app.min.css') }}?v={{ time() }}" rel="stylesheet">

    <script src="{{ asset('admin-assets/js/plugin.js') }}"></script>
</head>

<body data-sidebar="dark">
    @include('admin.layouts.header')

    <div id="layout-wrapper">
        @include('admin.layouts.sidebar')

        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    @include('admin.layouts.footer')

    <script src="{{ asset('admin-assets/libs/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('admin-assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('admin-assets/libs/metismenu/metisMenu.min.js') }}"></script>
    <script src="{{ asset('admin-assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('admin-assets/libs/node-waves/waves.min.js') }}"></script>
    <script src="{{ asset('admin-assets/libs/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('admin-assets/js/pages/dashboard-blog.init.js') }}"></script>
    <script src="{{ asset('admin-assets/js/app.js') }}"></script>

    @stack('script')
</body>

</html>