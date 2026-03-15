@extends('subscriber.layouts.app')

@section('title', 'تفاصيل العميل')

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <div class="avatar-lg bg-soft-primary rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                    <iconify-icon icon="solar:user-circle-bold" class="display-4 text-primary"></iconify-icon>
                </div>
                <h4 class="mb-1">{{ $customer->name }}</h4>
                <p class="text-muted mb-3">{{ $customer->phone }}</p>
                <div class="badge {{ $customer->is_active ? 'bg-success' : 'bg-danger' }} fs-13 px-3">
                    {{ $customer->is_active ? 'نشط' : 'معطل' }}
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm overflow-hidden mb-4">
            <div class="card-header bg-white">
                <h4 class="card-title fw-bold mb-0">صافي الرصيد الحالي</h4>
            </div>
            <div class="card-body">
                <div class="text-center py-2">
                    <div class="text-muted small mb-1">الرصد المستحق على العميل</div>
                    <h2 class="fw-bold text-{{ $balanceInfo['color'] }} mb-2">
                        {{ number_format($balanceInfo['amount'], 2) }} <small class="fs-14">د.ع</small>
                    </h2>
                    <div class="badge bg-soft-{{ $balanceInfo['color'] }} text-{{ $balanceInfo['color'] }} px-3 py-2">
                        {{ $balanceInfo['label'] }}
                    </div>
                </div>
                <hr class="my-4">
                <a href="{{ route('subscriber.app.customers.statement', $customer) }}" class="btn btn-primary w-100 py-2">
                    <iconify-icon icon="solar:document-text-bold" class="me-1"></iconify-icon> عرض كشف الحساب التفصيلي
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">المعلومات الأساسية</h4>
                <a href="{{ route('subscriber.app.customers.edit', $customer) }}" class="btn btn-sm btn-soft-primary">تعديل البيانات</a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label class="text-muted fs-13 d-block">الهاتف الأساسي</label>
                        <span class="fw-bold">{{ $customer->phone }}</span>
                    </div>
                    <div class="col-md-6 mb-4">
                        <label class="text-muted fs-13 d-block">الهاتف الإضافي</label>
                        <span class="fw-bold">{{ $customer->alternate_phone ?? '-' }}</span>
                    </div>
                    <div class="col-md-6 mb-4">
                        <label class="text-muted fs-13 d-block">البريد الإلكتروني</label>
                        <span class="fw-bold">{{ $customer->email ?? '-' }}</span>
                    </div>
                    <div class="col-md-6 mb-4">
                        <label class="text-muted fs-13 d-block">العنوان</label>
                        <span class="fw-bold">{{ $customer->address ?? '-' }}</span>
                    </div>
                    <div class="col-12 mb-0">
                        <label class="text-muted fs-13 d-block">ملاحظات</label>
                        <p class="mb-0 bg-light p-3 rounded">{{ $customer->notes ?? 'لا توجد ملاحظات' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h4 class="card-title">النشاطات المرتبطة (قريباً)</h4>
            </div>
            <div class="card-body text-center py-5">
                <iconify-icon icon="solar:bill-list-bold-duotone" class="display-3 text-muted opacity-25"></iconify-icon>
                <p class="text-muted mt-3 mb-0">سيتم ربط المبيعات والمدفوعات وكشف الحساب بهذا القسم لاحقاً.</p>
            </div>
        </div>
    </div>
</div>
@endsection
