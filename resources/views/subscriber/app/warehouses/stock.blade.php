@extends('subscriber.layouts.app')

@section('title', 'مخزون المستودع: ' . $warehouse->name)

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="card-title">قائمة المنتجات في {{ $warehouse->name }}</h4>
        <a href="{{ route('subscriber.app.warehouses.index') }}" class="btn btn-light btn-sm">العودة للمستودعات</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>المنتج</th>
                        <th>الصنف</th>
                        <th>الباركود</th>
                        <th>الكمية الحالية</th>
                        <th>الكمية الافتتاحية</th>
                        <th>حد التنبيه</th>
                        <th>الحالة</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stocks as $stock)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                @if($stock->product->image)
                                    <img src="{{ asset('storage/' . $stock->product->image) }}" alt="" class="rounded-circle avatar-xs">
                                @else
                                    <div class="avatar-xs d-flex align-items-center justify-content-center bg-soft-secondary rounded-circle">
                                        <iconify-icon icon="solar:box-bold" class="text-secondary"></iconify-icon>
                                    </div>
                                @endif
                                <span>{{ $stock->product->name }}</span>
                            </div>
                        </td>
                        <td>{{ $stock->product->category->name ?? '-' }}</td>
                        <td>{{ $stock->product->barcode ?? '-' }}</td>
                        <td>
                            <span class="fw-bold {{ $stock->status === 'low' ? 'text-danger' : '' }}">
                                {{ number_format($stock->current_quantity, 2) }}
                            </span>
                        </td>
                        <td>{{ number_format($stock->opening_quantity, 2) }}</td>
                        <td>{{ $stock->alert_quantity ? number_format($stock->alert_quantity, 2) : '-' }}</td>
                        <td>
                            @if($stock->status === 'low')
                                <span class="badge bg-danger">منخفض</span>
                            @else
                                <span class="badge bg-success">طبيعي</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">لا يوجد مخزون في هذا المستودع حالياً.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $stocks->links() }}
        </div>
    </div>
</div>
@endsection
