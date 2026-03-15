@extends('subscriber.layouts.onboarding')

@section('title', 'التسجيل مغلق')

@section('content')
<div class="card auth-card shadow-sm border-0 text-center">
    <div class="card-body p-5">
        <div class="mb-4">
            <iconify-icon icon="solar:shield-warning-bold" class="text-warning display-1"></iconify-icon>
        </div>
        <h3 class="mb-3">التسجيل مغلق حالياً</h3>
        <p class="text-muted lead">عذراً، نظام التسجيل متوقف مؤقتاً بأمر الإدارة. يرجى المحاولة مرة أخرى لاحقاً.</p>
        <div class="mt-4">
            <a href="{{ route('subscriber.login') }}" class="btn btn-light px-4">تسجيل الدخول</a>
        </div>
    </div>
</div>
@endsection
