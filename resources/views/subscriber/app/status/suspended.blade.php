@extends('subscriber.layouts.onboarding')

@section('title', 'الحساب معطل')

@section('content')
<div class="card auth-card shadow-sm border-0 text-center">
    <div class="card-body p-5">
        <div class="mb-4">
            <iconify-icon icon="solar:user-block-bold" class="text-danger display-1"></iconify-icon>
        </div>
        <h3 class="mb-3">تم تعطيل حسابك</h3>
        <p class="text-muted mb-4">نأسف لإخبارك بأن حسابك قد تم تعطيله من قبل الإدارة. يرجى التواصل مع الدعم الفني للاستفسار.</p>
        <div class="mt-4">
            <form action="{{ route('subscriber.logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-light px-4">تسجيل الخروج</button>
            </form>
        </div>
    </div>
</div>
@endsection
