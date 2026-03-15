@extends('subscriber.layouts.onboarding')

@section('title', 'إنشاء حساب جديد')

@section('content')
<div class="card auth-card shadow-sm border-0">
    <div class="card-body p-4">
        <h3 class="text-center mb-4">سجل متجرك الآن</h3>
        <form action="{{ route('subscriber.register') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label text-end">الاسم الكامل</label>
                <input type="text" name="name" class="form-control" placeholder="أدخل اسمك الكامل" required value="{{ old('name') }}">
                @error('name') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">رقم الهاتف</label>
                <input type="text" name="phone" class="form-control" placeholder="0770 000 0000" required value="{{ old('phone') }}">
                @error('phone') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">البريد الإلكتروني (اختياري)</label>
                <input type="email" name="email" class="form-control" placeholder="example@mail.com" value="{{ old('email') }}">
                @error('email') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">كلمة المرور</label>
                <input type="password" name="password" class="form-control" placeholder="********" required>
                @error('password') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
            <div class="mb-4">
                <label class="form-label">تأكيد كلمة المرور</label>
                <input type="password" name="password_confirmation" class="form-control" placeholder="********" required>
            </div>
            <button type="submit" class="btn btn-primary w-100 py-2">تسجيل الحساب</button>
            <div class="text-center mt-3">
                <span class="text-muted">لديك حساب بالفعل؟</span>
                <a href="{{ route('subscriber.login') }}" class="text-primary fw-bold text-decoration-none">تسجيل الدخول</a>
            </div>
        </form>
    </div>
</div>
@endsection
