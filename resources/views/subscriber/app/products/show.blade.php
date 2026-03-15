@extends('subscriber.layouts.app')

@section('title', 'عرض المنتج')

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" alt="" class="img-fluid rounded border mb-3">
                @else
                    <div class="avatar-lg bg-light rounded mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 150px; height: 150px;">
                        <iconify-icon icon="solar:box-broken" class="display-1 text-muted"></iconify-icon>
                    </div>
                @endif
                <h4 class="mb-1">{{ $product->name }}</h4>
                <p class="text-muted">{{ $product->category?->name ?? 'بدون صنف' }}</p>
                <div class="badge {{ $product->is_active ? 'bg-success' : 'bg-danger' }} fs-13 px-3">
                    {{ $product->is_active ? 'نشط' : 'معطل' }}
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h4 class="card-title">تفاصيل المنتج</h4>
                <a href="{{ route('subscriber.app.products.edit', $product) }}" class="btn btn-sm btn-primary">تعديل المنتج</a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6 mb-4">
                        <label class="text-muted fs-13">رمز SKU</label>
                        <p class="fw-bold mb-0">{{ $product->sku ?? '-' }}</p>
                    </div>
                    <div class="col-sm-6 mb-4">
                        <label class="text-muted fs-13">باركود</label>
                        <p class="fw-bold mb-0">{{ $product->barcode ?? '-' }}</p>
                    </div>
                    <div class="col-12 mb-4">
                        <label class="text-muted fs-13">الوصف</label>
                        <p class="mb-0">{{ $product->description ?? 'لا يوجد وصف' }}</p>
                    </div>
                </div>
                <hr class="mt-0">
                <div class="row">
                    <div class="col-sm-4 mb-4">
                        <label class="text-muted fs-13">سعر الشراء</label>
                        <p class="fw-bold mb-0 text-danger">{{ number_format($product->purchase_price, 0) }} د.ع</p>
                    </div>
                    <div class="col-sm-4 mb-4">
                        <label class="text-muted fs-13">سعر البيع</label>
                        <p class="fw-bold mb-0 text-success">{{ number_format($product->retail_price, 0) }} د.ع</p>
                    </div>
                    <div class="col-sm-4 mb-4">
                        <label class="text-muted fs-13">سعر الجملة</label>
                        <p class="fw-bold mb-0">{{ $product->wholesale_price ? number_format($product->wholesale_price, 0) . ' د.ع' : '-' }}</p>
                    </div>
                    <div class="col-sm-6 mb-4">
                        <label class="text-muted fs-13">الكمية الحالية</label>
                        <h3 class="mb-0 {{ $product->quantity <= $product->alert_quantity ? 'text-danger' : '' }}">{{ $product->quantity }}</h3>
                    </div>
                    <div class="col-sm-6 mb-4">
                        <label class="text-muted fs-13">حد التنبيه</label>
                        <p class="fw-medium mb-0">{{ $product->alert_quantity }}</p>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-light">
                <small class="text-muted">تاريخ الإضافة: {{ $product->created_at->format('Y-m-d H:i') }}</small>
            </div>
        </div>
    </div>
</div>
@endsection
