@extends('subscriber.layouts.app')

@section('title', 'تقرير المشتريات')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">تقرير المشتريات</h4>
    <form action="{{ route('subscriber.app.reports.purchases') }}" method="GET" class="d-flex gap-2 align-items-center">
        <select name="filter" class="form-select form-select-sm" onchange="this.form.submit()">
            <option value="today" {{ $range['filter'] == 'today' ? 'selected' : '' }}>اليوم</option>
            <option value="week" {{ $range['filter'] == 'week' ? 'selected' : '' }}>هذا الأسبوع</option>
            <option value="month" {{ $range['filter'] == 'month' ? 'selected' : '' }}>هذا الشهر</option>
            <option value="year" {{ $range['filter'] == 'year' ? 'selected' : '' }}>هذه السنة</option>
            <option value="custom" {{ $range['filter'] == 'custom' ? 'selected' : '' }}>فترة مخصصة</option>
        </select>
        
        @if($range['filter'] == 'custom')
            <input type="date" name="start_date" class="form-control form-control-sm" value="{{ request('start_date') }}">
            <input type="date" name="end_date" class="form-control form-control-sm" value="{{ request('end_date') }}">
            <button type="submit" class="btn btn-sm btn-primary">تطبيق</button>
        @endif
    </form>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm bg-soft-danger">
            <div class="card-body py-4">
                <div class="text-muted small mb-1">إجمالي المشتريات (الصافي)</div>
                <h2 class="fw-bold text-danger mb-0">{{ number_format($data['total_amount'], 2) }} <small>د.ع</small></h2>
                <div class="mt-2 small">{{ $data['invoice_count'] }} فاتورة شراء معتمدة</div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fs-16">أفضل 5 موردين (قيمة التوريد)</h5>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @forelse($data['top_suppliers'] as $item)
                    <div class="list-group-item d-flex justify-content-between align-items-center py-2 px-3">
                        <span class="small fw-bold">{{ $item->supplier->name }}</span>
                        <span class="badge bg-soft-secondary text-secondary">{{ number_format($item->total, 2) }}</span>
                    </div>
                    @empty
                    <div class="p-3 text-center text-muted small">لا توجد بيانات كافية</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 fs-16">سجل فواتير الشراء للفترة المحددة</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr class="small text-muted">
                        <th class="ps-3">رقم الفاتورة</th>
                        <th>التاريخ</th>
                        <th>المورد</th>
                        <th class="text-end pe-3">القيمة الإجمالية</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data['invoices'] as $invoice)
                    <tr>
                        <td class="ps-3 fw-bold text-danger">{{ $invoice->invoice_number }}</td>
                        <td class="small">{{ $invoice->invoice_date->format('Y-m-d') }}</td>
                        <td class="small">{{ $invoice->supplier->name }}</td>
                        <td class="text-end pe-3 fw-bold">{{ number_format($invoice->total_amount, 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-5 text-muted">لا توجد فواتير شراء في هذه الفترة</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
