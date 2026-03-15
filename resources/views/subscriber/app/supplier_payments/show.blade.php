@extends('subscriber.layouts.app')

@section('title', 'تفاصيل سند الصرف: ' . $supplierPayment->payment_number)

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm overflow-hidden">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0">سند صرف رقم: <span class="text-primary">{{ $supplierPayment->payment_number }}</span></h5>
                <div class="d-flex gap-2">
        <a href="{{ route('subscriber.app.print.supplier-payment', $supplierPayment) }}" target="_blank" class="btn btn-soft-secondary">
            <iconify-icon icon="solar:printer-bold" class="me-1"></iconify-icon> طباعة السند
        </a>
        <a href="{{ route('subscriber.app.supplier-payments.index') }}" class="btn btn-light">العودة للقائمة</a>
    </div>
            </div>
            <div class="card-body">
                <div class="row g-4 mb-5 text-center">
                    <div class="col-md-4">
                        <div class="bg-light p-3 rounded">
                            <div class="text-muted small mb-1">المبلغ المصروف</div>
                            <div class="h3 fw-bold text-danger mb-0">{{ number_format($supplierPayment->amount, 2) }} <small>د.ع</small></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="bg-light p-3 rounded">
                            <div class="text-muted small mb-1">التاريخ</div>
                            <div class="h3 fw-bold mb-0">{{ $supplierPayment->payment_date->format('Y-m-d') }}</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="bg-light p-3 rounded">
                            <div class="text-muted small mb-1">المورد</div>
                            <div class="h4 fw-bold mb-0">{{ $supplierPayment->supplier->name }}</div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <tbody>
                            <tr>
                                <th class="bg-light" style="width: 200px;">الصندوق المالي</th>
                                <td>{{ $supplierPayment->cashbox->name }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">رقم المرجع / التحويل</th>
                                <td>{{ $supplierPayment->reference_number ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">الملاحظات</th>
                                <td>{{ $supplierPayment->notes ?? '-' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                @if($supplierPayment->transaction)
                <div class="mt-4 p-3 bg-soft-info rounded border border-info">
                    <div class="d-flex align-items-center">
                        <iconify-icon icon="solar:info-circle-bold" class="text-info h4 mb-0 me-2"></iconify-icon>
                        <div class="small fw-bold text-info">مرتبط بحركة مالية رقم #{{ $supplierPayment->transaction->id }} صادرة من الصندوق.</div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
