@extends('subscriber.layouts.app')

@section('title', 'تعديل بيانات المستودع')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">تعديل المستودع: {{ $warehouse->name }}</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('subscriber.app.warehouses.update', $warehouse) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">اسم المستودع</label>
                            <input type="text" name="name" class="form-control" required value="{{ old('name', $warehouse->name) }}">
                            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">كود المستودع (اختياري)</label>
                            <input type="text" name="code" class="form-control" value="{{ old('code', $warehouse->code) }}">
                            @error('code') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">العنوان</label>
                            <input type="text" name="address" class="form-control" value="{{ old('address', $warehouse->address) }}">
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <div class="form-check form-switch mt-4">
                                <input class="form-check-input" type="checkbox" name="is_default" value="1" id="isDefault" {{ $warehouse->is_default ? 'checked' : '' }}>
                                <label class="form-check-label" for="isDefault">تعيين كمستودع افتراضي</label>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">الحالة</label>
                            <select name="is_active" class="form-select">
                                <option value="1" {{ $warehouse->is_active ? 'selected' : '' }}>نشط</option>
                                <option value="0" {{ !$warehouse->is_active ? 'selected' : '' }}>معطل</option>
                            </select>
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label">ملاحظات</label>
                            <textarea name="notes" class="form-control" rows="3">{{ old('notes', $warehouse->notes) }}</textarea>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary px-4">تحديث بيانات المستودع</button>
                        <a href="{{ route('subscriber.app.warehouses.index') }}" class="btn btn-light">إلغاء</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
