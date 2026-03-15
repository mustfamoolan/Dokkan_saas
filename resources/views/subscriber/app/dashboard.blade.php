@extends('subscriber.layouts.app')

@section('title', 'لوحة التحكم')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card bg-primary-subtle border-0 mb-4">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h3 class="mb-1 text-primary">مرحباً بك، {{ $subscriber->name }}!</h3>
                        <p class="text-muted mb-0">أنت تشاهد ملخص متجر <strong>{{ $store->name }}</strong></p>
                    </div>
                    <div class="text-end">
                        <span class="badge bg-primary fs-13 px-3 py-2">باقة {{ $subscription->plan->name }}</span>
                        @if($subscription->is_trial)
                            <div class="mt-2 text-warning fw-bold small">فترة تجريبية</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Quick Stats -->
    @foreach([
        ['title' => 'المنتجات', 'icon' => 'solar:box-bold', 'data' => $metrics['products'], 'color' => 'primary'],
        ['title' => 'العملاء', 'icon' => 'solar:users-group-two-rounded-bold', 'data' => $metrics['customers'], 'color' => 'success'],
        ['title' => 'الفواتير', 'icon' => 'solar:bill-list-bold', 'data' => $metrics['invoices'], 'color' => 'info'],
        ['title' => 'المستخدمين', 'icon' => 'solar:user-bold', 'data' => $metrics['users'], 'color' => 'warning'],
    ] as $stat)
    <div class="col-md-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-sm bg-{{ $stat['color'] }}-subtle rounded-circle d-flex align-items-center justify-content-center">
                        <iconify-icon icon="{{ $stat['icon'] }}" class="text-{{ $stat['color'] }} fs-20"></iconify-icon>
                    </div>
                    <div>
                        <p class="text-muted mb-1">{{ $stat['title'] }}</p>
                        <h4 class="mb-0">{{ $stat['data']['usage'] }} / {{ $stat['data']['limit'] ?? '∞' }}</h4>
                    </div>
                </div>
                <div class="progress mt-3" style="height: 5px;">
                    @php
                        $perc = $stat['data']['limit'] ? ($stat['data']['usage'] / $stat['data']['limit']) * 100 : 0;
                    @endphp
                    <div class="progress-bar bg-{{ $stat['color'] }}" style="width: {{ $perc }}%"></div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="row">
    <!-- Subscription Info -->
    <div class="col-xl-4">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">معلومات الاشتراك</h4>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="text-muted">الباقة الحالية:</span>
                    <span class="fw-bold">{{ $subscription->plan->name }}</span>
                </div>
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="text-muted">تاريخ الانتهاء:</span>
                    <span class="fw-bold">{{ $subscription->ends_at ? $subscription->ends_at->format('Y-m-d') : 'دائم' }}</span>
                </div>
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="text-muted">الحالة:</span>
                    <span class="badge bg-success-subtle text-success">مفعل</span>
                </div>
                <hr>
                <div class="text-center">
                    <a href="#" class="btn btn-soft-primary btn-sm">ترقية الباقة</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Store Info -->
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">بيانات المتجر</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6 mb-3">
                        <label class="text-muted fs-13">اسم المتجر</label>
                        <p class="fw-medium">{{ $store->name }}</p>
                    </div>
                    <div class="col-sm-6 mb-3">
                        <label class="text-muted fs-13">رقم الهاتف</label>
                        <p class="fw-medium">{{ $store->phone }}</p>
                    </div>
                    <div class="col-sm-6 mb-3">
                        <label class="text-muted fs-13">العنوان</label>
                        <p class="fw-medium">{{ $store->address }}</p>
                    </div>
                    <div class="col-sm-6 mb-3">
                        <label class="text-muted fs-13">العملة الافتراضية</label>
                        <p class="fw-medium">{{ $store->currency }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
