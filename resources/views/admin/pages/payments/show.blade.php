@extends('admin.layouts.admin-layout')

@section('title', 'تفاصيل الدفعة')

@section('content')
<div class="row">
    <div class="col-xl-4">
        <!-- Payment Card -->
        <div class="card">
            <div class="card-header bg-primary-subtle text-center py-4">
                <h2 class="mb-1 text-primary">{{ number_format($payment->amount, 2) }} {{ $payment->currency }}</h2>
                <p class="text-muted mb-0">مبلغ العملية</p>
                <div class="mt-3">
                    @php
                        $statusClasses = [
                            'pending' => 'bg-warning',
                            'approved' => 'bg-success',
                            'rejected' => 'bg-danger',
                        ];
                        $statusLabels = [
                            'pending' => 'قيد المراجعة',
                            'approved' => 'مقبول',
                            'rejected' => 'مرفوض',
                        ];
                    @endphp
                    <span class="badge fs-14 py-2 px-3 {{ $statusClasses[$payment->status] ?? 'bg-light' }}">
                        {{ $statusLabels[$payment->status] ?? $payment->status }}
                    </span>
                </div>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="d-flex justify-content-between mb-3 pb-2 border-bottom">
                        <span class="text-muted">طريقة الدفع:</span>
                        <span class="fw-bold text-start">{{ $payment->payment_method }}</span>
                    </li>
                    <li class="d-flex justify-content-between mb-3 pb-2 border-bottom">
                        <span class="text-muted">رقم المرجع:</span>
                        <span class="fw-bold text-start">{{ $payment->reference_number ?? 'N/A' }}</span>
                    </li>
                    <li class="d-flex justify-content-between mb-3 pb-2 border-bottom">
                        <span class="text-muted">تاريخ التسجيل:</span>
                        <span class="fw-bold text-start">{{ $payment->created_at->format('Y-m-d H:i') }}</span>
                    </li>
                    @if($payment->reviewed_at)
                    <li class="d-flex justify-content-between mb-3 pb-2 border-bottom">
                        <span class="text-muted">تاريخ المراجعة:</span>
                        <span class="fw-bold text-start">{{ $payment->reviewed_at->format('Y-m-d H:i') }}</span>
                    </li>
                    <li class="d-flex justify-content-between mb-0">
                        <span class="text-muted">المراجع:</span>
                        <span class="fw-bold text-start text-primary">{{ $payment->reviewer->name ?? 'System' }}</span>
                    </li>
                    @endif
                </ul>
            </div>
        </div>

        @if($payment->status == 'pending' && auth()->user()->can('review payments'))
        <div class="card mt-3">
            <div class="card-body">
                <h5 class="card-title mb-3">إجراءات المراجعة</h5>
                <form action="{{ route('admin.payments.approve', $payment->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-success w-100 mb-2">اعتماد الدفع (Approve)</button>
                </form>
                
                <hr>
                
                <form action="{{ route('admin.payments.reject', $payment->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fs-12 text-muted">سبب الرفض (إلزامي للرفض)</label>
                        <textarea name="rejection_reason" class="form-control form-control-sm" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-danger w-100">رفض الدفعة (Reject)</button>
                </form>
            </div>
        </div>
        @endif
    </div>

    <div class="col-xl-8">
        <div class="row">
            <div class="col-md-6">
                <!-- Subscriber info -->
                <div class="card text-start h-100">
                    <div class="card-header bg-light-subtle">
                        <h5 class="card-title mb-0">بيانات المشترك والمتجر</h5>
                    </div>
                    <div class="card-body">
                        <h4 class="fs-16 mb-1 text-primary">{{ $payment->subscriber->name }}</h4>
                        <p class="text-muted mb-3">{{ $payment->subscriber->phone }}</p>
                        <h5 class="fs-14 mb-1 text-info">{{ $payment->store->name }}</h5>
                        <p class="text-muted mb-0">رقم المتجر: #{{ $payment->store_id }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <!-- Subscription info -->
                <div class="card text-start h-100">
                    <div class="card-header bg-light-subtle">
                        <h5 class="card-title mb-0">الاشتراك المرتبط</h5>
                    </div>
                    <div class="card-body">
                        <h4 class="fs-16 mb-1 text-dark">خطة: {{ $payment->subscription->plan->name }}</h4>
                        <p class="text-muted mb-1">دورة الفوترة: {{ $payment->subscription->billing_cycle }}</p>
                        <p class="text-muted mb-3">حالة الاشتراك: {{ $payment->subscription->status }}</p>
                        <a href="{{ route('admin.subscriptions.show', $payment->subscription_id) }}" class="btn btn-soft-dark btn-sm">عرض الاشتراك</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Receipt Card -->
        <div class="card mt-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0 text-start">إيصال الدفع</h5>
                @if($payment->receipt)
                    <a href="{{ asset('storage/' . $payment->receipt->file_path) }}" target="_blank" class="btn btn-soft-primary btn-sm">فتح في نافذة جديدة</a>
                @endif
            </div>
            <div class="card-body text-center p-0 overflow-hidden">
                @if($payment->receipt)
                    <img src="{{ asset('storage/' . $payment->receipt->file_path) }}" class="img-fluid" alt="Receipt">
                @else
                    <div class="py-5 bg-light">
                        <iconify-icon icon="solar:camera-broken" class="fs-48 text-muted mb-2"></iconify-icon>
                        <p class="text-muted">لم يتم رفع إيصال لهذه العملية</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0 text-start">ملاحظات وسجل المراجعة</h5>
            </div>
            <div class="card-body">
                <div class="p-3 bg-light rounded text-start">
                    {!! nl2br(e($payment->notes ?: 'لا توجد ملاحظات.')) !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
