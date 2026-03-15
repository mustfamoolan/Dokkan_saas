<!DOCTYPE html>
<html lang="ar" dir="rtl" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - {{ config('app.name') }}</title>
    @vite(['resources/scss/app.scss', 'resources/js/app.js', 'resources/js/config.js'])
    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
    <style>
        body { background-color: #f8f9fa; }
        .auth-card { max-width: 500px; margin: 50px auto; }
        .onboarding-steps { margin-bottom: 30px; }
        .step-item { flex: 1; text-align: center; color: #ced4da; }
        .step-item.active { color: #0d6efd; font-weight: bold; }
        .step-item.completed { color: #198754; }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 text-center mt-5 mb-4">
                <h2 class="text-primary fw-bold">{{ config('app.name') }}</h2>
            </div>
            <div class="col-12">
                @yield('content')
            </div>
        </div>
    </div>
</body>
</html>
