@extends('subscriber.layouts.app')

@section('title', 'تقرير الصناديق والسيولة')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">تقرير الصناديق والسيولة النقدية</h4>
</div>

<div class="row mb-4 text-center">
    <div class="col-md-4 mx-auto">
        <div class="card border-0 shadow-sm bg-primary text-white py-4">
            <div class="small opacity-75 mb-1">إجمالي السيولة النقدية الحالية</div>
            <h2 class="fw-bold mb-0 text-white">{{ number_format($data['total_cash'], 2) }} <small>د.ع</small></h2>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 fs-16">أرصدة الصناديق المالية</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr class="small text-muted">
                        <th class="ps-3">اسم الصندوق</th>
                        <th class="text-center">الحالة</th>
                        <th class="text-end pe-3">الرصيد الحالي</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['cashboxes'] as $box)
                    <tr>
                        <td class="ps-3">
                            <div class="fw-bold">{{ $box->name }}</div>
                            <div class="small text-muted">تاريخ آخر حركة: {{ $box->updated_at->format('Y-m-d') }}</div>
                        </td>
                        <td class="text-center">
                            @if($box->is_active)
                                <span class="badge bg-soft-success text-success">نشط</span>
                            @else
                                <span class="badge bg-soft-danger text-danger">معطل</span>
                            @endif
                        </td>
                        <td class="text-end pe-3 fw-bold h5 mb-0 {{ $box->current_balance >= 0 ? 'text-success' : 'text-danger' }}">
                            {{ number_format($box->current_balance, 2) }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
