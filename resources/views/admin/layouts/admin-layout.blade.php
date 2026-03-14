<!DOCTYPE html>
<html lang="ar" dir="rtl" data-bs-theme="light" data-menu-color="dark" data-topbar-color="light" data-menu-size="default">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} - Admin</title>
    @vite(['resources/scss/app.scss', 'resources/js/app.js', 'resources/js/config.js', 'resources/js/layout.js'])
    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
</head>
<body>
    
    <!-- Begin page -->
    <div class="wrapper">

        @include('admin.components.navbar')
        @include('admin.components.sidebar')

        <div class="page-content">
            <div class="container-fluid">
                @yield('content')
            </div>
        </div>

    </div>
    <!-- END wrapper -->

</body>
</html>
