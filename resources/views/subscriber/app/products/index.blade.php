@extends('subscriber.layouts.app')

@section('title', 'إدارة المنتجات')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="card-title">قائمة المنتجات</h4>
        <a href="{{ route('subscriber.app.products.create') }}" class="btn btn-primary btn-sm">إضافة منتج جديد</a>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>الصورة</th>
                        <th>المنتج</th>
                        <th>الصنف</th>
                        <th>سعر البيع</th>
                        <th>الكمية</th>
                        <th>الحالة</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                    <tr>
                        <td>
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="" class="avatar-sm rounded">
                            @else
                                <div class="avatar-sm bg-light rounded d-flex align-items-center justify-content-center">
                                    <iconify-icon icon="solar:box-broken" class="fs-20 text-muted"></iconify-icon>
                                </div>
                            @endif
                        </td>
                        <td>
                            <div class="fw-bold">{{ $product->name }}</div>
                            <small class="text-muted">SKU: {{ $product->sku ?? '-' }}</small>
                        </td>
                        <td>{{ $product->category?->name ?? 'بدون صنف' }}</td>
                        <td>{{ number_format($product->retail_price, 0) }} د.ع</td>
                        <td>
                            <span class="{{ $product->quantity <= $product->alert_quantity ? 'text-danger fw-bold' : '' }}">
                                {{ $product->quantity }}
                            </span>
                        </td>
                        <td>
                            <span class="badge {{ $product->is_active ? 'bg-success' : 'bg-danger' }}">
                                {{ $product->is_active ? 'نشط' : 'معطل' }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('subscriber.app.products.show', $product) }}" class="btn btn-sm btn-soft-info" title="عرض">
                                    <iconify-icon icon="solar:eye-bold"></iconify-icon>
                                </a>
                                <a href="{{ route('subscriber.app.products.edit', $product) }}" class="btn btn-sm btn-soft-primary" title="تعديل">
                                    <iconify-icon icon="solar:pen-bold"></iconify-icon>
                                </a>
                                <form action="{{ route('subscriber.app.products.destroy', $product) }}" method="POST" onsubmit="return confirm('هل أنت متأكد؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-soft-danger" title="حذف">
                                        <iconify-icon icon="solar:trash-bin-trash-bold"></iconify-icon>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection
