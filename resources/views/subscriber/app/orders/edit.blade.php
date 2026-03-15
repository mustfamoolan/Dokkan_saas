@extends('subscriber.layouts.app')

@section('title', 'تعديل طلب التوصيل')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card mt-3">
            <div class="card-header bg-white">
                <h5 class="mb-0">تعديل طلب رقم: #{{ $order->order_number }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('subscriber.app.orders.update', $order->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">رقم الطلب <span class="text-danger">*</span></label>
                            <input type="text" name="order_number" class="form-control @error('order_number') is-invalid @enderror" value="{{ old('order_number', $order->order_number) }}" required>
                            @error('order_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">التاريخ <span class="text-danger">*</span></label>
                            <input type="date" name="order_date" class="form-control @error('order_date') is-invalid @enderror" value="{{ old('order_date', $order->order_date->format('Y-m-d')) }}" required>
                            @error('order_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">العميل <span class="text-danger">*</span></label>
                            <select name="customer_id" class="form-select @error('customer_id') is-invalid @enderror" required>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" {{ old('customer_id', $order->customer_id) == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->name }} ({{ $customer->phone }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">حالة الطلب <span class="text-danger">*</span></label>
                            <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="new" {{ $order->status == 'new' ? 'selected' : '' }}>جديد</option>
                                <option value="preparing" {{ $order->status == 'preparing' ? 'selected' : '' }}>قيد التجهيز</option>
                                <option value="out_for_delivery" {{ $order->status == 'out_for_delivery' ? 'selected' : '' }}>خارج للتوصيل</option>
                                <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>تم التسليم</option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>ملغي</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">المندوب</label>
                            <select name="representative_id" class="form-select">
                                <option value="">غير مسند</option>
                                @foreach($representatives as $rep)
                                    <option value="{{ $rep->id }}" {{ old('representative_id', $order->representative_id) == $rep->id ? 'selected' : '' }}>
                                        {{ $rep->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label">عنوان التوصيل</label>
                            <textarea name="delivery_address" class="form-control" rows="2">{{ old('delivery_address', $order->delivery_address) }}</textarea>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label">ملاحظات الطلب</label>
                            <textarea name="notes" class="form-control" rows="2">{{ old('notes', $order->notes) }}</textarea>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('subscriber.app.orders.index') }}" class="btn btn-light">إلغاء</a>
                        <button type="submit" class="btn btn-primary px-4">تحديث الطلب</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
