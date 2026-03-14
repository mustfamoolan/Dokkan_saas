<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} - تسجيل دخول المسؤول</title>
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
</head>
<body class="bg-light">
    <div class="account-pages py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="card auth-card shadow-lg mt-5">
                        <div class="card-body p-4">
                            <div class="text-center w-75 mx-auto auth-logo mb-4">
                                <h3 class="text-primary font-weight-bold">دكان - المسؤول</h3>
                            </div>

                            @if(session('error'))
                                <div class="alert alert-danger" role="alert">
                                    {{ session('error') }}
                                </div>
                            @endif

                            <form action="{{ route('admin.login') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="emailaddress" class="form-label">البريد الإلكتروني</label>
                                    <input class="form-control @error('email') is-invalid @enderror" type="email" name="email" id="emailaddress" required="" value="{{ old('email') }}" placeholder="أدخل بريدك الإلكتروني">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">كلمة المرور</label>
                                    <input class="form-control @error('password') is-invalid @enderror" type="password" name="password" required="" id="password" placeholder="أدخل كلمة المرور">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" name="remember" class="form-check-input" id="checkbox-signin">
                                        <label class="form-check-label" for="checkbox-signin">تذكرني</label>
                                    </div>
                                </div>
                                <div class="mb-3 text-center d-grid">
                                    <button class="btn btn-primary" type="submit"> تسجيل الدخول </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
