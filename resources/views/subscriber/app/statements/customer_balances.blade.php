@extends('subscriber.layouts.app')

@section('title', 'أرصدة العملاء')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">ملخص أرصدة العملاء</h4>
    <a href="{{ route('subscriber.app.customers.index') }}" class="btn btn-light">قائمة العملاء</a>
</div>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm bg-primary text-white">
            <div class="card-body">
                <div class="small opacity-75 mb-1">إجمالي المديونية (مطلوبات السوق)</div>
                <div class="h2 fw-bold mb-0">{{ number_format($balances->where('balance.type', 'debit')->sum('balance.amount'), 2) }} <small>د.ع</small></div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr class="small text-muted">
                        <th class="ps-4">العميل</th>
                        <th>الهاتف</th>
                        <th class="text-center">حالة الرصيد</th>
                        <th class="text-end">المبلغ</th>
                        <th class="text-end pe-4">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($balances as $item)
                    <tr>
                        <td class="ps-4 fw-bold">{{ $item['customer']->name }}</td>
                        <td class="small">{{ $item['customer']->phone }}</td>
                        <td class="text-center">
                            <span class="badge bg-soft-{{ $item['balance']['color'] }} text-{{ $item['balance']['color'] }}">
                                {{ $item['balance']['label'] }}
                            </span>
                        </td>
                        <td class="text-end fw-bold text-{{ $item['balance']['color'] }}">
                            {{ number_format($item['balance']['amount'], 2) }}
                        </td>
                        <td class="text-end pe-4">
                            <a href="{{ route('subscriber.app.customers.statement', $item['customer']) }}" class="btn btn-sm btn-soft-primary">
                                <iconify-icon icon="solar:document-text-bold"></iconify-icon> كشف الحساب
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
