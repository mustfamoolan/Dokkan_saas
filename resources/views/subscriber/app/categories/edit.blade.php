@extends('subscriber.layouts.app')

@section('title', 'تعديل الصنف')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">تعديل: {{ $category->name }}</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('subscriber.app.categories.update', $category) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">الاسم</label>
                        <input type="text" name="name" class="form-control" required value="{{ old('name', $category->name) }}">
                        @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">الوصف</label>
                        <textarea name="description" class="form-control" rows="3">{{ old('description', $category->description) }}</textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">ترتيب العرض</label>
                            <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $category->sort_order) }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">الحالة</label>
                            <select name="is_active" class="form-select">
                                <option value="1" {{ old('is_active', $category->is_active) == '1' ? 'selected' : '' }}>نشط</option>
                                <option value="0" {{ old('is_active', $category->is_active) == '0' ? 'selected' : '' }}>معطل</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">تحديث بيانات الصنف</button>
                        <a href="{{ route('subscriber.app.categories.index') }}" class="btn btn-light">إلغاء</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
