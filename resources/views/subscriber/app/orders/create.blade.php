@extends('subscriber.layouts.app')

@section('title', 'إنشاء طلب توصيل جديد')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card mt-3">
            <div class="card-header bg-white">
                <h5 class="mb-0">بيانات طلب التوصيل</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('subscriber.app.orders.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">رقم الطلب <span class="text-danger">*</span></label>
                            <input type="text" name="order_number" class="form-control @error('order_number') is-invalid @enderror" value="{{ old('order_number', 'DO-' . date('Ymd') . '-' . rand(100, 999)) }}" required>
                            @error('order_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">التاريخ <span class="text-danger">*</span></label>
                            <input type="date" name="order_date" class="form-control @error('order_date') is-invalid @enderror" value="{{ old('order_date', date('Y-m-d')) }}" required>
                            @error('order_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">العميل <span class="text-danger">*</span></label>
                            <select name="customer_id" class="form-select @error('customer_id') is-invalid @enderror" required>
                                <option value="">اختر العميل</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" {{ (old('customer_id') == $customer->id || (isset($selectedInvoice) && $selectedInvoice->customer_id == $customer->id)) ? 'selected' : '' }}>
                                        {{ $customer->name }} ({{ $customer->phone }})
                                    </option>
                                @endforeach
                            </select>
                            @error('customer_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label">فاتورة البيع المرتبطة (اختياري)</label>
                            <select name="sales_invoice_id" class="form-select @error('sales_invoice_id') is-invalid @enderror">
                                <option value="">بدون فاتورة مرتبطة</option>
                                @if(isset($selectedInvoice))
                                    <option value="{{ $selectedInvoice->id }}" selected>{{ $selectedInvoice->invoice_number }} - {{ $selectedInvoice->total_amount }} د.أ</option>
                                @endif
                                <!-- Could add search or list last 50 invoices here -->
                            </select>
                            <small class="text-muted">إذا تم الربط، سيمكنك تتبع الفاتورة من الطلب والعكس</small>
                            @error('sales_invoice_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label">المندوب (اختياري)</label>
                            <select name="representative_id" class="form-select @error('representative_id') is-invalid @enderror">
                                <option value="">غير مسند حالياً</option>
                                @foreach($representatives as $rep)
                                    <option value="{{ $rep->id }}" {{ old('representative_id') == $rep->id ? 'selected' : '' }}>
                                        {{ $rep->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('representative_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label">عنوان التوصيل</label>
                            <textarea name="delivery_address" class="form-control" rows="2">{{ old('delivery_address', isset($selectedInvoice) ? $selectedInvoice->customer->address : '') }}</textarea>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label">ملاحظات الطلب</label>
                            <textarea name="notes" class="form-control" rows="2">{{ old('notes') }}</textarea>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('subscriber.app.orders.index') }}" class="btn btn-light">إلغاء</a>
                        <button type="submit" class="btn btn-primary px-4">إنشاء طلب التوصيل</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
