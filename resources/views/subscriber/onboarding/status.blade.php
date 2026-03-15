@extends('subscriber.layouts.onboarding')

@section('title', 'حالة الاشتراك')

@section('content')
<div class="card auth-card shadow-sm border-0 text-center">
    <div class="card-body p-5">
        @if($subscription->status == 'active' || $subscription->status == 'trial')
            <div class="mb-4">
                <iconify-icon icon="solar:check-circle-bold" class="text-success display-1"></iconify-icon>
            </div>
            <h3 class="mb-3">تم تفعيل حسابك بنجاح!</h3>
            <p class="text-muted mb-4">متجرك <strong>{{ $store->name }}</strong> جاهز للعمل. يمكنك الآن البدء في استخدام النظام.</p>
            <div class="alert alert-info">
                باقة: <strong>{{ $subscription->plan->name }}</strong><br>
                تنتهي في: <strong>{{ $subscription->ends_at->format('Y-m-d') }}</strong>
            </div>
            <div class="mt-4">
                {{-- No merchant dashboard yet as requested --}}
                <p class="text-primary fw-bold">سيتم توجيهك إلى لوحة تحكم المتجر قريباً (عند الانتهاء من بنائها).</p>
                <form action="{{ route('subscriber.logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-light px-4">تسجيل الخروج</button>
                </form>
            </div>
        @elseif($subscription->status == 'pending')
            <div class="mb-4">
                <iconify-icon icon="solar:hourglass-bold" class="text-warning display-1"></iconify-icon>
            </div>
            <h3 class="mb-3">طلبك قيد المراجعة</h3>
            <p class="text-muted mb-4">لقد استلمنا طلب اشتراكك في باقة <strong>{{ $subscription->plan->name }}</strong>. جاري مراجعة إيصال الدفع من قبل الإدارة.</p>
            <div class="alert alert-warning">
                سيتم تفعيل حسابك وتنبيهك فور تأكيد العملية.
            </div>
            <div class="mt-4">
                <form action="{{ route('subscriber.logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-light px-4">تسجيل الخروج</button>
                </form>
            </div>
        @else
            <div class="mb-4">
                <iconify-icon icon="solar:danger-circle-bold" class="text-danger display-1"></iconify-icon>
            </div>
            <h3 class="mb-3">حالة الاشتراك: {{ $subscription->status }}</h3>
            <p class="text-muted mb-4">يبدو أن هناك مشكلة في اشتراكك. يرجى التواصل مع الدعم الفني.</p>
            <form action="{{ route('subscriber.logout') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-light px-4">تسجيل الخروج</button>
            </form>
        @endif
    </div>
</div>
@endsection
