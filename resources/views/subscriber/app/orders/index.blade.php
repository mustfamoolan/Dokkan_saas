@extends('subscriber.layouts.app')

@section('title', 'طلبات التوصيل')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card mt-3">
            <div class="card-header d-flex justify-content-between align-items-center bg-white">
                <h5 class="mb-0">سجل طلبات التوصيل</h5>
                <a href="{{ route('subscriber.app.orders.create') }}" class="btn btn-primary btn-sm">
                    <iconify-icon icon="solar:cart-plus-bold" class="me-1"></iconify-icon>إنشاء طلب توصيل
                </a>
            </div>
            <div class="card-body">
                <!-- Filters -->
                <form action="{{ route('subscriber.app.orders.index') }}" method="GET" class="row g-3 mb-4">
                    <div class="col-md-3">
                        <select name="status" class="form-select" onchange="this.form.submit()">
                            <option value="">كل الحالات</option>
                            <option value="new" {{ request('status') == 'new' ? 'selected' : '' }}>جديد</option>
                            <option value="preparing" {{ request('status') == 'preparing' ? 'selected' : '' }}>قيد التجهيز</option>
                            <option value="out_for_delivery" {{ request('status') == 'out_for_delivery' ? 'selected' : '' }}>خارج للتوصيل</option>
                            <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>تم التسليم</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>ملغي</option>
                        </select>
                    </div>
                </form>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>رقم الطلب</th>
                                <th>التاريخ</th>
                                <th>العميل</th>
                                <th>المندوب</th>
                                <th>الحالة</th>
                                <th>الفاتورة</th>
                                <th class="text-end">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                                <tr>
                                    <td><span class="fw-bold fs-14 text-primary">#{{ $order->order_number }}</span></td>
                                    <td>{{ $order->order_date->format('Y-m-d') }}</td>
                                    <td>{{ $order->customer->name }}</td>
                                    <td>
                                        @if($order->representative)
                                            <div class="d-flex align-items-center gap-1">
                                                <iconify-icon icon="solar:user-bold" class="text-muted"></iconify-icon>
                                                {{ $order->representative->name }}
                                            </div>
                                        @else
                                            <span class="text-muted small">لم يسند بعد</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $badgeClass = 'bg-secondary-subtle text-secondary';
                                            $statusText = 'جديد';
                                            switch($order->status) {
                                                case 'preparing': $badgeClass = 'bg-info-subtle text-info'; $statusText = 'قيد التجهيز'; break;
                                                case 'out_for_delivery': $badgeClass = 'bg-warning-subtle text-warning'; $statusText = 'خارج للتوصيل'; break;
                                                case 'delivered': $badgeClass = 'bg-success-subtle text-success'; $statusText = 'تم التسليم'; break;
                                                case 'cancelled': $badgeClass = 'bg-danger-subtle text-danger'; $statusText = 'ملغي'; break;
                                            }
                                        @endphp
                                        <span class="badge {{ $badgeClass }}">{{ $statusText }}</span>
                                    </td>
                                    <td>
                                        @if($order->invoice)
                                            <a href="{{ route('subscriber.app.sales.show', $order->sales_invoice_id) }}" class="text-primary small">
                                                {{ $order->invoice->invoice_number }}
                                            </a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex justify-content-end gap-1">
                                            <a href="{{ route('subscriber.app.orders.show', $order->id) }}" class="btn btn-sm btn-light-primary btn-icon">
                                                <iconify-icon icon="solar:eye-bold"></iconify-icon>
                                            </a>
                                            <a href="{{ route('subscriber.app.orders.edit', $order->id) }}" class="btn btn-sm btn-light-info btn-icon">
                                                <iconify-icon icon="solar:pen-new-square-bold"></iconify-icon>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">لا توجد طلبات توصيل حالياً</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
