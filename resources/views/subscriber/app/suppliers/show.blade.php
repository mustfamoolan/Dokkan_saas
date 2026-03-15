@extends('subscriber.layouts.app')

@section('title', 'تفاصيل المورد')

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <div class="avatar-lg bg-soft-primary rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                    <iconify-icon icon="solar:shop-bold" class="display-4 text-primary"></iconify-icon>
                </div>
                <h4 class="mb-1">{{ $supplier->name }}</h4>
                <p class="text-muted mb-3">{{ $supplier->phone }}</p>
                <div class="badge {{ $supplier->is_active ? 'bg-success' : 'bg-danger' }} fs-13 px-3">
                    {{ $supplier->is_active ? 'نشط' : 'معطل' }}
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h4 class="card-title">الرصيد الحالي</h4>
            </div>
            <div class="card-body">
                <div class="text-center">
                    @if($supplier->opening_balance_type === 'none')
                        <h2 class="text-muted">0 <small class="fs-14">د.ع</small></h2>
                        <p class="text-muted mb-0">لا يوجد رصيد افتتاحي</p>
                    @else
                        <h2 class="{{ $supplier->opening_balance_type === 'debit' ? 'text-danger' : 'text-success' }}">
                            {{ number_format($supplier->opening_balance, 0) }} <small class="fs-14">د.ع</small>
                        </h2>
                        <p class="fw-bold mb-0">
                            رصيد افتتاحي ({{ $supplier->opening_balance_type === 'debit' ? 'مدين/نطلبه مبالغ' : 'دائن/يطلبنا مبالغ' }})
                        </p>
                    @endif
                </div>
                <hr>
                <div class="alert alert-info py-2 mb-0">
                    <small><iconify-icon icon="solar:info-circle-bold"></iconify-icon> سيظهر لاحقاً كشف الحساب وفواتير المشتريات والمدفوعات هنا.</small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">المعلومات الأساسية</h4>
                <a href="{{ route('subscriber.app.suppliers.edit', $supplier) }}" class="btn btn-sm btn-soft-primary">تعديل البيانات</a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label class="text-muted fs-13 d-block">الهاتف الأساسي</label>
                        <span class="fw-bold">{{ $supplier->phone }}</span>
                    </div>
                    <div class="col-md-6 mb-4">
                        <label class="text-muted fs-13 d-block">الهاتف الإضافي</label>
                        <span class="fw-bold">{{ $supplier->alternate_phone ?? '-' }}</span>
                    </div>
                    <div class="col-md-6 mb-4">
                        <label class="text-muted fs-13 d-block">البريد الإلكتروني</label>
                        <span class="fw-bold">{{ $supplier->email ?? '-' }}</span>
                    </div>
                    <div class="col-md-6 mb-4">
                        <label class="text-muted fs-13 d-block">العنوان</label>
                        <span class="fw-bold">{{ $supplier->address ?? '-' }}</span>
                    </div>
                    <div class="col-12 mb-0">
                        <label class="text-muted fs-13 d-block">ملاحظات</label>
                        <p class="mb-0 bg-light p-3 rounded">{{ $supplier->notes ?? 'لا توجد ملاحظات' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h4 class="card-title">النشاطات المرتبطة (قريباً)</h4>
            </div>
            <div class="card-body text-center py-5">
                <iconify-icon icon="solar:cart-large-minimalistic-bold-duotone" class="display-3 text-muted opacity-25"></iconify-icon>
                <p class="text-muted mt-3 mb-0">سيتم ربط المشتريات والمدفوعات وكشف الحساب بهذا القسم لاحقاً.</p>
            </div>
        </div>
    </div>
</div>
@endsection
