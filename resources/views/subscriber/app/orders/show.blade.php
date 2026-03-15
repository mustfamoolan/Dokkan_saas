@extends('subscriber.layouts.app')

@section('title', 'تفاصيل طلب التوصيل')

@section('content')
<div class="row mt-3">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">بيانات الطلب #{{ $order->order_number }}</h5>
                <div>
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
                    <span class="badge {{ $badgeClass }} fs-14 pb-2 px-3">{{ $statusText }}</span>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label class="text-muted small d-block mb-1">العميل</label>
                        <h6 class="mb-1">{{ $order->customer->name }}</h6>
                        <small class="text-muted">{{ $order->customer->phone }}</small>
                    </div>
                    <div class="col-md-6 mb-4">
                        <label class="text-muted small d-block mb-1">تاريخ الطلب</label>
                        <h6 class="mb-1">{{ $order->order_date->format('Y-m-d') }}</h6>
                        <small class="text-muted">تم الإنشاء: {{ $order->created_at->format('H:i') }}</small>
                    </div>
                    <div class="col-md-12 mb-4">
                        <label class="text-muted small d-block mb-1">عنوان التوصيل</label>
                        <p class="mb-0">{{ $order->delivery_address ?: 'غير محدد' }}</p>
                    </div>
                    <div class="col-md-12 mb-4">
                        <label class="text-muted small d-block mb-1">الملاحظات</label>
                        <p class="mb-0 text-muted italic">{{ $order->notes ?: 'لا توجد ملاحظات' }}</p>
                    </div>
                </div>

                @if($order->invoice)
                    <div class="alert alert-light border d-flex justify-content-between align-items-center">
                        <div>
                            <iconify-icon icon="solar:bill-bold" class="text-primary me-2"></iconify-icon>
                            مرتبط بفاتورة مبيعات: <strong>{{ $order->invoice->invoice_number }}</strong>
                        </div>
                        <a href="{{ route('subscriber.app.sales.show', $order->sales_invoice_id) }}" class="btn btn-sm btn-outline-primary px-3">عرض الفاتورة</a>
                    </div>
                @endif
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header bg-white">
                <h5 class="mb-0">تحديث حالة الطلب</h5>
            </div>
            <div class="card-body">
                <div class="d-flex flex-wrap gap-2">
                    <form action="{{ route('subscriber.app.orders.update-status', $order->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="status" value="preparing">
                        <button type="submit" class="btn btn-outline-info {{ $order->status == 'preparing' ? 'active' : '' }}">قيد التجهيز</button>
                    </form>
                    <form action="{{ route('subscriber.app.orders.update-status', $order->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="status" value="out_for_delivery">
                        <button type="submit" class="btn btn-outline-warning {{ $order->status == 'out_for_delivery' ? 'active' : '' }}">خارج للتوصيل</button>
                    </form>
                    <form action="{{ route('subscriber.app.orders.update-status', $order->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="status" value="delivered">
                        <button type="submit" class="btn btn-outline-success {{ $order->status == 'delivered' ? 'active' : '' }}">تم التسليم</button>
                    </form>
                    <form action="{{ route('subscriber.app.orders.update-status', $order->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="status" value="cancelled">
                        <button type="submit" class="btn btn-outline-danger {{ $order->status == 'cancelled' ? 'active' : '' }}">إلغاء الطلب</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">المندوب وتفاصيل التوصيل</h5>
            </div>
            <div class="card-body">
                @if($order->representative)
                    <div class="text-center mb-4">
                        <div class="avatar-md bg-primary-subtle rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2">
                            <iconify-icon icon="solar:user-bold" class="text-primary fs-32"></iconify-icon>
                        </div>
                        <h6 class="mb-0">{{ $order->representative->name }}</h6>
                        <small class="text-muted">{{ $order->representative->phone }}</small>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small d-block mb-1">وقت الإسناد</label>
                        <h6 class="small">{{ $order->assigned_at ? $order->assigned_at->format('Y-m-d H:i') : '-' }}</h6>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small d-block mb-1">وقت التسليم</label>
                        <h6 class="small">{{ $order->delivered_at ? $order->delivered_at->format('Y-m-d H:i') : 'لم يتم التسليم بعد' }}</h6>
                    </div>
                    <hr>
                @endif

                <form action="{{ route('subscriber.app.orders.assign', $order->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">إسناد / تغيير المندوب</label>
                        <select name="representative_id" class="form-select select2" required>
                            <option value="">اختر مندوباً</option>
                            @foreach($representatives as $rep)
                                <option value="{{ $rep->id }}" {{ $order->representative_id == $rep->id ? 'selected' : '' }}>
                                    {{ $rep->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">حفظ الإسناد</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
