@extends('subscriber.layouts.app')

@section('title', 'مركز التقارير')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">مركز التقارير والإحصائيات</h4>
    <form action="{{ route('subscriber.app.reports.index') }}" method="GET" class="d-flex gap-2">
        <select name="filter" class="form-select form-select-sm" onchange="this.form.submit()">
            <option value="today" {{ $range['filter'] == 'today' ? 'selected' : '' }}>اليوم</option>
            <option value="week" {{ $range['filter'] == 'week' ? 'selected' : '' }}>هذا الأسبوع</option>
            <option value="month" {{ $range['filter'] == 'month' ? 'selected' : '' }}>هذا الشهر</option>
            <option value="year" {{ $range['filter'] == 'year' ? 'selected' : '' }}>هذه السنة</option>
        </select>
    </form>
</div>

<!-- Summary Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-soft-success text-success p-2 rounded me-3">
                        <iconify-icon icon="solar:round-alt-arrow-up-bold" class="h4 mb-0"></iconify-icon>
                    </div>
                    <div class="text-muted small">إجمالي المبيعات</div>
                </div>
                <h3 class="fw-bold mb-0">{{ number_format($metrics['total_sales'], 2) }}</h3>
                <div class="text-muted small mt-1">{{ $metrics['sales_count'] }} فاتورة</div>
            </div>
            <div class="card-footer bg-white border-0 py-2">
                <a href="{{ route('subscriber.app.reports.sales', ['filter' => $range['filter']]) }}" class="small text-primary">تفاصيل المبيعات <iconify-icon icon="solar:alt-arrow-left-linear"></iconify-icon></a>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-soft-danger text-danger p-2 rounded me-3">
                        <iconify-icon icon="solar:round-alt-arrow-down-bold" class="h4 mb-0"></iconify-icon>
                    </div>
                    <div class="text-muted small">إجمالي المشتريات</div>
                </div>
                <h3 class="fw-bold mb-0">{{ number_format($metrics['total_purchases'], 2) }}</h3>
                <div class="text-muted small mt-1">{{ $metrics['purchases_count'] }} فاتورة</div>
            </div>
            <div class="card-footer bg-white border-0 py-2">
                <a href="{{ route('subscriber.app.reports.purchases', ['filter' => $range['filter']]) }}" class="small text-primary">تفاصيل المشتريات <iconify-icon icon="solar:alt-arrow-left-linear"></iconify-icon></a>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-soft-warning text-warning p-2 rounded me-3">
                        <iconify-icon icon="solar:bill-list-bold" class="h4 mb-0"></iconify-icon>
                    </div>
                    <div class="text-muted small">إجمالي المصروفات</div>
                </div>
                <h3 class="fw-bold mb-0">{{ number_format($metrics['total_expenses'], 2) }}</h3>
                <div class="text-muted small mt-1">{{ $metrics['expenses_count'] }} حركة</div>
            </div>
            <div class="card-footer bg-white border-0 py-2">
                <a href="{{ route('subscriber.app.reports.expenses', ['filter' => $range['filter']]) }}" class="small text-primary">تفاصيل المصروفات <iconify-icon icon="solar:alt-arrow-left-linear"></iconify-icon></a>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm bg-primary text-white">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-white bg-opacity-25 p-2 rounded me-3">
                        <iconify-icon icon="solar:wallet-bold" class="h4 mb-0"></iconify-icon>
                    </div>
                    <div class="small opacity-75">إجمالي السيولة النقدية</div>
                </div>
                <h3 class="fw-bold mb-0 text-white">{{ number_format($metrics['total_cashbox_balance'], 2) }}</h3>
                <div class="small opacity-75 mt-1">عبر {{ $metrics['cashboxes_count'] }} صناديق</div>
            </div>
            <div class="card-footer bg-primary border-0 py-2">
                <a href="{{ route('subscriber.app.reports.cashboxes') }}" class="small text-white opacity-75">تفاصيل الصناديق <iconify-icon icon="solar:alt-arrow-left-linear"></iconify-icon></a>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Quick Stats Cards (Smaller) -->
    <div class="col-md-3">
        <div class="card border-0 shadow-sm text-center py-4">
            <div class="h1 text-primary fw-bold mb-1">{{ $metrics['products_count'] }}</div>
            <div class="text-muted small">منتجاً مسجلاً</div>
            <a href="{{ route('subscriber.app.reports.inventory') }}" class="stretched-link"></a>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm text-center py-4">
            <div class="h1 text-info fw-bold mb-1">{{ $metrics['customers_count'] }}</div>
            <div class="text-muted small">عميلاً مسجلاً</div>
            <a href="{{ route('subscriber.app.reports.customers') }}" class="stretched-link"></a>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm text-center py-4">
            <div class="h1 text-secondary fw-bold mb-1">{{ $metrics['suppliers_count'] }}</div>
            <div class="text-muted small">مورداً مسجلاً</div>
            <a href="{{ route('subscriber.app.reports.suppliers') }}" class="stretched-link"></a>
        </div>
    </div>
</div>
@endsection
