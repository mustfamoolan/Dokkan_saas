@extends('subscriber.layouts.app')

@section('title', 'تفاصيل المندوب')

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card mt-3">
            <div class="card-body text-center">
                <div class="avatar-lg bg-primary-subtle rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3">
                    <iconify-icon icon="solar:user-bold" class="text-primary fs-48"></iconify-icon>
                </div>
                <h4 class="mb-1">{{ $representative->name }}</h4>
                <p class="text-muted mb-3">{{ $representative->phone }}</p>
                @if($representative->is_active)
                    <span class="badge bg-success-subtle text-success px-3 fs-13">نشط</span>
                @else
                    <span class="badge bg-danger-subtle text-danger px-3 fs-13">معطل</span>
                @endif
                <hr class="my-4">
                <div class="d-grid gap-2">
                    <a href="{{ route('subscriber.app.representatives.edit', $representative->id) }}" class="btn btn-outline-primary">تعديل البيانات</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card mt-3">
            <div class="card-header bg-white">
                <h5 class="mb-0">المعلومات الشخصية والعمولات</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6 mb-4">
                        <label class="text-muted small d-block mb-1">البريد الإلكتروني</label>
                        <h6 class="mb-0">{{ $representative->email ?: 'غير متوفر' }}</h6>
                    </div>
                    <div class="col-sm-6 mb-4">
                        <label class="text-muted small d-block mb-1">تاريخ الانضمام</label>
                        <h6 class="mb-0">{{ $representative->created_at->format('Y-m-d H:i') }}</h6>
                    </div>
                    <div class="col-sm-6 mb-4">
                        <label class="text-muted small d-block mb-1">نوع العمولة</label>
                        <h6 class="mb-0">
                            {{ $representative->commission_type == 'fixed' ? 'مبلغ ثابت' : ($representative->commission_type == 'percentage' ? 'نسبة مئوية' : 'بدون عمولة') }}
                        </h6>
                    </div>
                    <div class="col-sm-6 mb-4">
                        <label class="text-muted small d-block mb-1">قيمة العمولة</label>
                        <h6 class="mb-0">
                            @if($representative->commission_type)
                                {{ $representative->commission_value }}
                                {{ $representative->commission_type == 'percentage' ? '%' : 'د.أ' }}
                            @else
                                -
                            @endif
                        </h6>
                    </div>
                    <div class="col-12 mb-0">
                        <label class="text-muted small d-block mb-1">ملاحظات</label>
                        <p class="mb-0">{{ $representative->notes ?: 'لا توجد ملاحظات' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">إحصائيات الطلبات (تمهيدي)</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col">
                        <h5 class="mb-1">{{ $representative->orders()->count() }}</h5>
                        <small class="text-muted">إجمالي الطلبات</small>
                    </div>
                    <div class="col border-start">
                        <h5 class="mb-1">{{ $representative->orders()->where('status', 'delivered')->count() }}</h5>
                        <small class="text-muted">طلبات منفذة</small>
                    </div>
                    <div class="col border-start">
                        <h5 class="mb-1">{{ $representative->orders()->where('status', 'out_for_delivery')->count() }}</h5>
                        <small class="text-muted">قيد التوصيل</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@section('styles')
<style>
    .avatar-lg { width: 80px; height: 80px; }
</style>
@endsection
@endsection
