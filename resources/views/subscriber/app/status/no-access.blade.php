@extends('subscriber.layouts.onboarding')

@section('title', 'لا تملك صلاحية الدخول')

@section('content')
<div class="card auth-card shadow-sm border-0 text-center">
    <div class="card-body p-5">
        <div class="mb-4">
            <iconify-icon icon="solar:lock-bold" class="text-warning display-1"></iconify-icon>
        </div>
        <h3 class="mb-3">دخول غير مصرح</h3>
        <p class="text-muted mb-4">يبدو أن اشتراكك الحالي لا يسمح لك بالدخول إلى لوحة التحكم حالياً.</p>
        <div class="mt-4">
            <a href="{{ route('subscriber.onboarding.status') }}" class="btn btn-primary px-4">العودة لصفحة الحالة</a>
            <form action="{{ route('subscriber.logout') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-light px-4">تسجيل الخروج</button>
            </form>
        </div>
    </div>
</div>
@endsection
