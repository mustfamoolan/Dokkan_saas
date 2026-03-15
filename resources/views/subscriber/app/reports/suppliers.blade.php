@extends('subscriber.layouts.app')

@section('title', 'تقرير أرصدة الموردين')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">تقرير أرصدة ومديونيات الموردين</h4>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body text-center py-4 bg-soft-danger">
        <div class="text-muted small mb-1">إجمالي المبالغ المستحقة للموردين (مطلوبات على المتجر)</div>
        <h2 class="fw-bold text-danger mb-0">
            {{ number_format(collect($data)->where('balance.type', 'credit')->sum('balance.amount'), 2) }} <small>د.ع</small>
        </h2>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr class="small text-muted">
                        <th class="ps-3">المورد</th>
                        <th>الهاتف</th>
                        <th class="text-center">حالة الرصيد</th>
                        <th class="text-end">المبلغ</th>
                        <th class="text-end pe-3">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $item)
                    <tr>
                        <td class="ps-3 fw-bold">{{ $item['supplier']->name }}</td>
                        <td class="small">{{ $item['supplier']->phone }}</td>
                        <td class="text-center">
                            <span class="badge bg-soft-{{ $item['balance']['color'] }} text-{{ $item['balance']['color'] }}">
                                {{ $item['balance']['label'] }}
                            </span>
                        </td>
                        <td class="text-end fw-bold text-{{ $item['balance']['color'] }}">
                            {{ number_format($item['balance']['amount'], 2) }}
                        </td>
                        <td class="text-end pe-3">
                            <a href="{{ route('subscriber.app.suppliers.statement', $item['supplier']) }}" class="btn btn-sm btn-soft-primary">
                                كشف الحساب
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
