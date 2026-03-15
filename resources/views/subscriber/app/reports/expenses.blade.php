@extends('subscriber.layouts.app')

@section('title', 'تقرير المصروفات')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">تقرير المصروفات النقدية</h4>
    <form action="{{ route('subscriber.app.reports.expenses') }}" method="GET" class="d-flex gap-2 align-items-center">
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
        <div class="card border-0 shadow-sm bg-soft-warning">
            <div class="card-body py-4">
                <div class="text-muted small mb-1">إجمالي المصروفات</div>
                <h2 class="fw-bold text-warning mb-0">{{ number_format($data['total_amount'], 2) }} <small>د.ع</small></h2>
                <div class="mt-2 small">{{ $data['count'] }} حركات صرف</div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fs-16">توزيع المصروفات حسب الصناديق</h5>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @forelse($data['by_cashbox'] as $item)
                    <div class="list-group-item d-flex justify-content-between align-items-center py-2 px-3">
                        <span class="small fw-bold">{{ $item->cashbox->name }}</span>
                        <span class="badge bg-soft-warning text-warning">{{ number_format($item->total, 2) }}</span>
                    </div>
                    @empty
                    <div class="p-3 text-center text-muted small">لا توجد بيانات</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 fs-16">سجل المصروفات للفترة المحدرة</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr class="small text-muted">
                        <th class="ps-3">التاريخ</th>
                        <th>الصندوق</th>
                        <th>البيان</th>
                        <th class="text-end pe-3">المبلغ</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data['expenses'] as $expense)
                    <tr>
                        <td class="ps-3 small">{{ $expense->expense_date->format('Y-m-d') }}</td>
                        <td class="small fw-bold">{{ $expense->cashbox->name }}</td>
                        <td class="small">{{ $expense->reason }}</td>
                        <td class="text-end pe-3 fw-bold text-danger">{{ number_format($expense->amount, 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-5 text-muted">لا يوجد مصروفات في هذه الفترة</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
