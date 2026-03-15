@extends('subscriber.layouts.app')

@section('title', 'تعديل المنتج')

@section('content')
<div class="card">
    <div class="card-header">
        <h4 class="card-title">تعديل: {{ $product->name }}</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('subscriber.app.products.update', $product) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">اسم المنتج</label>
                            <input type="text" name="name" class="form-control" required value="{{ old('name', $product->name) }}">
                            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">الصنف</label>
                            <select name="category_id" class="form-select">
                                <option value="">-- بدون صنف --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">SKU (رمز الحفظ)</label>
                            <input type="text" name="sku" class="form-control" value="{{ old('sku', $product->sku) }}">
                            @error('sku') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">باركود</label>
                            <input type="text" name="barcode" class="form-control" value="{{ old('barcode', $product->barcode) }}">
                            @error('barcode') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">الوصف</label>
                            <textarea name="description" class="form-control" rows="3">{{ old('description', $product->description) }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-center">
                    <div class="mb-3">
                        <label class="form-label d-block text-start">صورة المنتج</label>
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="" class="img-fluid rounded mb-2 border p-1" style="max-height: 150px;">
                        @endif
                        <input type="file" name="image" class="form-control" accept="image/*">
                        @error('image') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="mb-3 text-start">
                        <label class="form-label">الحالة</label>
                        <select name="is_active" class="form-select">
                            <option value="1" {{ old('is_active', $product->is_active) == '1' ? 'selected' : '' }}>نشط</option>
                            <option value="0" {{ old('is_active', $product->is_active) == '0' ? 'selected' : '' }}>معطل</option>
                        </select>
                    </div>
                </div>
            </div>

            <hr class="my-4">
            <h5 class="mb-3 text-primary">الأسعار والكميات</h5>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">سعر الشراء</label>
                    <input type="number" step="0.01" name="purchase_price" class="form-control" required value="{{ old('purchase_price', $product->purchase_price) }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">سعر البيع (مفرد)</label>
                    <input type="number" step="0.01" name="retail_price" class="form-control" required value="{{ old('retail_price', $product->retail_price) }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">سعر البيع (جملة)</label>
                    <input type="number" step="0.01" name="wholesale_price" class="form-control" value="{{ old('wholesale_price', $product->wholesale_price) }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">الكمية الحالية</label>
                    <input type="number" name="quantity" class="form-control" required value="{{ old('quantity', $product->quantity) }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">حد تنبيه المخزون</label>
                    <input type="number" name="alert_quantity" class="form-control" required value="{{ old('alert_quantity', $product->alert_quantity) }}">
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary px-4">تحديث المنتج</button>
                <a href="{{ route('subscriber.app.products.index') }}" class="btn btn-light">إلغاء</a>
            </div>
        </form>
    </div>
</div>
@endsection
