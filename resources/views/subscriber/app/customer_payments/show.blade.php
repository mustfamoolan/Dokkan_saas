@extends('subscriber.layouts.app')

@section('title', 'تفاصيل دفعة العميل: ' . $customerPayment->payment_number)

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm overflow-hidden">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0">سند قبض رقم: <span class="text-primary">{{ $customerPayment->payment_number }}</span></h5>
                <div class="d-flex gap-2">
        <a href="{{ route('subscriber.app.print.customer-payment', $customerPayment) }}" target="_blank" class="btn btn-soft-secondary">
            <iconify-icon icon="solar:printer-bold" class="me-1"></iconify-icon> طباعة السند
        </a>
        <a href="{{ route('subscriber.app.customer-payments.index') }}" class="btn btn-light">العودة للقائمة</a>
    </div>
            </div>
            <div class="card-body">
                <div class="row g-4 mb-5 text-center">
                    <div class="col-md-4">
                        <div class="bg-light p-3 rounded">
                            <div class="text-muted small mb-1">المبلغ</div>
                            <div class="h3 fw-bold text-success mb-0">{{ number_format($customerPayment->amount, 2) }} <small>د.ع</small></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="bg-light p-3 rounded">
                            <div class="text-muted small mb-1">التاريخ</div>
                            <div class="h3 fw-bold mb-0">{{ $customerPayment->payment_date->format('Y-m-d') }}</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="bg-light p-3 rounded">
                            <div class="text-muted small mb-1">العميل</div>
                            <div class="h4 fw-bold mb-0">{{ $customerPayment->customer->name }}</div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <tbody>
                            <tr>
                                <th class="bg-light" style="width: 200px;">الصندوق المالي</th>
                                <td>{{ $customerPayment->cashbox->name }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">رقم المرجع / التحويل</th>
                                <td>{{ $customerPayment->reference_number ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">الملاحظات</th>
                                <td>{{ $customerPayment->notes ?? '-' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                @if($customerPayment->transaction)
                <div class="mt-4 p-3 bg-soft-info rounded border border-info">
                    <div class="d-flex align-items-center">
                        <iconify-icon icon="solar:info-circle-bold" class="text-info h4 mb-0 me-2"></iconify-icon>
                        <div class="small fw-bold text-info">مرتبط بحركة مالية رقم #{{ $customerPayment->transaction->id }} في الصندوق.</div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
