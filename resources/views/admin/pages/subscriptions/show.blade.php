@extends('admin.layouts.admin-layout')

@section('title', 'تفاصيل الاشتراك')

@section('content')
<div class="row">
    <div class="col-xl-4">
        <div class="card">
            <div class="card-header bg-primary-subtle">
                <h4 class="card-title text-primary text-center">خدمة الاشتراك</h4>
            </div>
            <div class="card-body text-center">
                <iconify-icon icon="solar:card-2-bold-duotone" class="fs-64 text-primary mb-3"></iconify-icon>
                <h4 class="mb-1">{{ $subscription->plan->name }}</h4>
                <p class="text-muted">{{ $subscription->billing_cycle == 'monthly' ? 'خطة شهرية' : ($subscription->billing_cycle == 'yearly' ? 'خطة سنوية' : 'خطة تجريبية') }}</p>
                
                <div class="mt-4 pt-2 border-top">
                    <div class="row g-2">
                        <div class="col-6 text-start">
                            <p class="text-muted mb-1 fs-12">تاريخ البداية</p>
                            <h5 class="fs-13 mb-0">{{ $subscription->starts_at->format('Y-m-d') }}</h5>
                        </div>
                        <div class="col-6 text-end">
                            <p class="text-muted mb-1 fs-12">تاريخ النهاية</p>
                            <h5 class="fs-13 mb-0 text-danger">{{ $subscription->ends_at->format('Y-m-d') }}</h5>
                        </div>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-top">
                    <a href="{{ route('admin.subscriptions.edit', $subscription->id) }}" class="btn btn-primary w-100 mb-2">تعديل الاشتراك</a>
                    <a href="{{ route('admin.subscriptions') }}" class="btn btn-light w-100">قائمة الاشتراكات</a>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body">
                <h5 class="fs-14 text-uppercase text-muted mb-3">حالة الاشتراك</h5>
                <div class="d-flex align-items-center mb-3">
                    <div class="flex-shrink-0">
                         <iconify-icon icon="solar:shield-check-broken" class="fs-24 text-success"></iconify-icon>
                    </div>
                    <div class="flex-grow-1 ms-3 text-start">
                        <h5 class="fs-13 mb-0">حالة النظام</h5>
                        <p class="text-muted mb-0">الحالي: <strong>{{ $subscription->status }}</strong></p>
                    </div>
                </div>
                <div class="d-flex align-items-center mb-0">
                    <div class="flex-shrink-0">
                         <iconify-icon icon="solar:refresh-broken" class="fs-24 text-info"></iconify-icon>
                    </div>
                    <div class="flex-grow-1 ms-3 text-start">
                        <h5 class="fs-13 mb-0">تجديد تلقائي</h5>
                        <p class="text-muted mb-0">الحالة: <strong>{{ $subscription->auto_renew ? 'مفعل' : 'معطل' }}</strong></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-8">
        <div class="row">
            <div class="col-md-6">
                <!-- Subscriber Card -->
                <div class="card text-start">
                    <div class="card-header bg-light-subtle">
                        <h5 class="card-title mb-0">بيانات المشترك</h5>
                    </div>
                    <div class="card-body">
                        <h4 class="fs-16 mb-1 text-primary">{{ $subscription->subscriber->name }}</h4>
                        <p class="text-muted mb-2">{{ $subscription->subscriber->phone }}</p>
                        <a href="{{ route('admin.subscribers.show', $subscription->subscriber_id) }}" class="btn btn-soft-primary btn-sm">ملف المشترك</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <!-- Store Card -->
                <div class="card text-start">
                    <div class="card-header bg-light-subtle">
                        <h5 class="card-title mb-0">بيانات المتجر</h5>
                    </div>
                    <div class="card-body">
                        <h4 class="fs-16 mb-1 text-info">{{ $subscription->store->name }}</h4>
                        <p class="text-muted mb-2">المسلسل: #{{ $subscription->store_id }}</p>
                        <a href="#" class="btn btn-soft-info btn-sm">إدارة المتجر</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">ملاحظات الاشتراك</h5>
            </div>
            <div class="card-body">
                <p class="text-muted text-start">{{ $subscription->notes ?? 'لا توجد ملاحظات إضافية لهذا الاشتراك.' }}</p>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">المدفوعات والقيود (قريباً)</h5>
            </div>
            <div class="card-body py-5 text-center">
                <iconify-icon icon="solar:dollar-minimalistic-broken" class="fs-48 text-muted mb-2"></iconify-icon>
                <p class="text-muted mb-0">هذا الجزء سيحتوي على الدفعات المالية المرتبطة بالاشتراك في المرحلة القادمة.</p>
            </div>
        </div>
    </div>
</div>
@endsection
