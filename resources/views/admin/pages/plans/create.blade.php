@extends('admin.layouts.admin-layout')

@section('title', 'إضافة باقة جديدة')

@section('content')
<div class="row">
    <div class="col-xl-8 col-lg-10 mx-auto">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">إنشاء باقة اشتراك جديدة</h4>
            </div>
            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form action="{{ route('admin.plans.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">اسم الباقة</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Slug (فريد)</label>
                                <input type="text" name="slug" class="form-control" value="{{ old('slug') }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">الوصف</label>
                        <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">السعر الشهري</label>
                                <input type="number" step="0.01" name="monthly_price" class="form-control" value="{{ old('monthly_price', 0) }}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">السعر السنوي</label>
                                <input type="number" step="0.01" name="yearly_price" class="form-control" value="{{ old('yearly_price', 0) }}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">العملة</label>
                                <input type="text" name="currency" class="form-control" value="{{ old('currency', 'USD') }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">أيام الفترة التجريبية</label>
                                <input type="number" name="trial_days" class="form-control" value="{{ old('trial_days', 14) }}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">ترتيب العرض</label>
                                <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', 0) }}" required>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" name="is_free" id="isFree" {{ old('is_free') ? 'checked' : '' }}>
                                <label class="form-check-label" for="isFree">باقة مجانية</label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" name="is_active" id="isActive" {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="isActive">نشطة</label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" name="is_visible" id="isVisible" {{ old('is_visible', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="isVisible">ظاهرة للعامة</label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" name="is_default" id="isDefault" {{ old('is_default') ? 'checked' : '' }}>
                                <label class="form-check-label" for="isDefault">باقة افتراضية</label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" name="is_featured" id="isFeatured" {{ old('is_featured') ? 'checked' : '' }}>
                                <label class="form-check-label" for="isFeatured">باقة مميزة</label>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 text-end">
                        <a href="{{ route('admin.plans') }}" class="btn btn-light me-1">إلغاء</a>
                        <button type="submit" class="btn btn-primary px-4">إضافة الباقة</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
