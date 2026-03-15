@extends('subscriber.layouts.app')

@section('title', 'دفعات الموردين (سندات الصرف)')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">دفعات الموردين (سندات الصرف)</h4>
    <a href="{{ route('subscriber.app.supplier-payments.create') }}" class="btn btn-primary">
        <iconify-icon icon="solar:add-circle-bold" class="me-1"></iconify-icon> تسجيل سند صرف جديد
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr class="small text-muted">
                        <th class="ps-4">رقم السند</th>
                        <th>المورد</th>
                        <th>الصندوق</th>
                        <th>التاريخ</th>
                        <th>المبلغ</th>
                        <th>المرجع</th>
                        <th class="text-end pe-4">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $payment)
                    <tr>
                        <td class="ps-4">
                            <span class="fw-bold text-primary">{{ $payment->payment_number }}</span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <iconify-icon icon="solar:shop-bold" class="text-muted me-2"></iconify-icon>
                                <span>{{ $payment->supplier->name }}</span>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-soft-info text-info">{{ $payment->cashbox->name }}</span>
                        </td>
                        <td class="small">{{ $payment->payment_date->format('Y-m-d') }}</td>
                        <td class="fw-bold text-danger">{{ number_format($payment->amount, 2) }}</td>
                        <td class="small text-muted">{{ $payment->reference_number ?? '-' }}</td>
                        <td class="text-end pe-4">
                            <a href="{{ route('subscriber.app.supplier-payments.show', $payment) }}" class="btn btn-sm btn-soft-primary">
                                <iconify-icon icon="solar:eye-bold"></iconify-icon> عرض التفاصيل
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">لا توجد دفعات مسجلة حالياً</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($payments->hasPages())
    <div class="card-footer bg-white border-0">
        {{ $payments->links() }}
    </div>
    @endif
</div>
@endsection
