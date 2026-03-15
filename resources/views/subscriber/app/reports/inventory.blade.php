@extends('subscriber.layouts.app')

@section('title', 'تقرير المخزون')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">تقرير المخزون والمستودعات</h4>
    <div class="d-flex gap-2 align-items-center">
        <a href="{{ route('subscriber.app.reports.inventory.export') }}" class="btn btn-sm btn-soft-primary">
            <iconify-icon icon="solar:download-bold" class="me-1"></iconify-icon> تصدير CSV
        </a>
        <form action="{{ route('subscriber.app.reports.inventory') }}" method="GET" class="d-flex gap-2 align-items-center">
        <select name="warehouse_id" class="form-select form-select-sm" onchange="this.form.submit()">
            <option value="">جميع المستودعات</option>
            @foreach($data['warehouses'] as $wh)
                <option value="{{ $wh->id }}" {{ $warehouseId == $wh->id ? 'selected' : '' }}>{{ $wh->name }}</option>
            @endforeach
        </select>
    </form>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm text-center py-4">
            <div class="h2 fw-bold text-primary mb-1">{{ $data['total_products'] }}</div>
            <div class="text-muted small">إجمالي الأصناف</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm text-center py-4">
            <div class="h2 fw-bold text-warning mb-1">{{ $data['low_stock']->count() }}</div>
            <div class="text-muted small">منتجات منخفضة المخزون</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm text-center py-4">
            <div class="h2 fw-bold text-danger mb-1">{{ $data['out_of_stock']->count() }}</div>
            <div class="text-muted small">منتجات نافدة</div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 fs-16">جرد الكميات التفصيلي</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr class="small text-muted">
                        <th class="ps-3">المنتج</th>
                        <th>المستودع</th>
                        <th>الباركود / SKU</th>
                        <th class="text-center">الكمية الحالية</th>
                        <th class="text-center pe-3">الحالة</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data['all_stocks'] as $stock)
                    <tr>
                        <td class="ps-3 fw-bold">{{ $stock->product->name }}</td>
                        <td class="small">{{ $stock->warehouse->name }}</td>
                        <td class="small text-muted">{{ $stock->product->barcode ?? $stock->product->sku ?? '-' }}</td>
                        <td class="text-center fw-bold">{{ number_format($stock->quantity, 0) }}</td>
                        <td class="text-center pe-3">
                            @if($stock->quantity <= 0)
                                <span class="badge bg-soft-danger text-danger">نافد</span>
                            @elseif($stock->quantity <= 10)
                                <span class="badge bg-soft-warning text-warning">منخفض</span>
                            @else
                                <span class="badge bg-soft-success text-success">متوفر</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">لا توجد بيانات مخزون حالية</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
