@extends('subscriber.layouts.onboarding')

@section('title', 'تسجيل الدخول')

@section('content')
<div class="card auth-card shadow-sm border-0">
    <div class="card-body p-4">
        <h3 class="text-center mb-4">تسجيل الدخول</h3>
        <form action="{{ route('subscriber.login') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label text-end">رقم الهاتف</label>
                <input type="text" name="phone" class="form-control" placeholder="0770 000 0000" required value="{{ old('phone') }}">
                @error('phone') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">كلمة المرور</label>
                <input type="password" name="password" class="form-control" placeholder="********" required>
                @error('password') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
            <div class="mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label" for="remember">تذكرني</label>
                </div>
            </div>
            <button type="submit" class="btn btn-primary w-100 py-2">دخول</button>
            <div class="text-center mt-3">
                <span class="text-muted">ليس لديك حساب؟</span>
                <a href="{{ route('subscriber.register') }}" class="text-primary fw-bold text-decoration-none">سجل الآن</a>
            </div>
        </form>
    </div>
</div>
@endsection
