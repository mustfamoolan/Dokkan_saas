@extends('subscriber.layouts.onboarding')

@section('title', 'الاشتراك منتهي')

@section('content')
<div class="card auth-card shadow-sm border-0 text-center">
    <div class="card-body p-5">
        <div class="mb-4">
            <iconify-icon icon="solar:calendar-broken" class="text-danger display-1"></iconify-icon>
        </div>
        <h3 class="mb-3">عذراً، اشتراكك منتهي</h3>
        <p class="text-muted mb-4">لقد انتهت صلاحية اشتراكك الحالي. يرجى تجديد الاشتراك أو الترقية لمتابعة استخدام خدمات دكان.</p>
        <div class="mt-4 gap-2 d-flex justify-content-center">
            <a href="{{ route('subscriber.onboarding.plan-selection') }}" class="btn btn-primary px-4">تجديد الآن</a>
            <form action="{{ route('subscriber.logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-light px-4">تسجيل الخروج</button>
            </form>
        </div>
    </div>
</div>
@endsection
