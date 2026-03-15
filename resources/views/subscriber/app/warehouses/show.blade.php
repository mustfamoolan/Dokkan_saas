@extends('subscriber.layouts.app')

@section('title', 'تفاصيل المستودع')

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <div class="avatar-lg bg-soft-primary rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                    <iconify-icon icon="solar:home-bold" class="display-4 text-primary"></iconify-icon>
                </div>
                <h4 class="mb-1">{{ $warehouse->name }}</h4>
                <p class="text-muted mb-3">{{ $warehouse->code ?? 'بدون كود' }}</p>
                <div class="d-flex justify-content-center gap-2">
                    <div class="badge {{ $warehouse->is_active ? 'bg-success' : 'bg-danger' }} fs-13 px-3">
                        {{ $warehouse->is_active ? 'نشط' : 'معطل' }}
                    </div>
                    @if($warehouse->is_default)
                        <div class="badge bg-info fs-13 px-3">الافتراضي</div>
                    @endif
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h4 class="card-title">إحصائيات سريعة</h4>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>إجمالي الأصناف:</span>
                    <span class="fw-bold">{{ $warehouse->stocks()->count() }}</span>
                </div>
                <hr>
                <a href="{{ route('subscriber.app.warehouses.stock', $warehouse) }}" class="btn btn-primary w-100 italic">عرض المخزون بالتفصيل</a>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">معلومات الموقع</h4>
                <a href="{{ route('subscriber.app.warehouses.edit', $warehouse) }}" class="btn btn-sm btn-soft-primary">تعديل البيانات</a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 mb-4">
                        <label class="text-muted fs-13 d-block">العنوان</label>
                        <span class="fw-bold">{{ $warehouse->address ?? 'غير محدد' }}</span>
                    </div>
                    <div class="col-12 mb-0">
                        <label class="text-muted fs-13 d-block">ملاحظات</label>
                        <p class="mb-0 bg-light p-3 rounded">{{ $warehouse->notes ?? 'لا توجد ملاحظات' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
