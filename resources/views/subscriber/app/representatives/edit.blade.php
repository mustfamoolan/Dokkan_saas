@extends('subscriber.layouts.app')

@section('title', 'تعديل بيانات المندوب')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card mt-3">
            <div class="card-header bg-white">
                <h5 class="mb-0">تعديل بيانات: {{ $representative->name }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('subscriber.app.representatives.update', $representative->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">الاسم الكامل <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $representative->name) }}" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">رقم الهاتف <span class="text-danger">*</span></label>
                            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $representative->phone) }}" required>
                            @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">البريد الإلكتروني</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $representative->email) }}">
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">تحديث كلمة المرور (اختياري)</label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                            <small class="text-muted">اتركها فارغة إذا لم ترد التغيير</small>
                            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">حالة المندوب</label>
                            <select name="is_active" class="form-select">
                                <option value="1" {{ $representative->is_active ? 'selected' : '' }}>نشط</option>
                                <option value="0" {{ !$representative->is_active ? 'selected' : '' }}>معطل</option>
                            </select>
                        </div>
                        <hr class="my-3 text-muted">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">نوع العمولة</label>
                            <select name="commission_type" class="form-select @error('commission_type') is-invalid @enderror">
                                <option value="">بدون عمولة</option>
                                <option value="fixed" {{ old('commission_type', $representative->commission_type) == 'fixed' ? 'selected' : '' }}>مبلغ ثابت</option>
                                <option value="percentage" {{ old('commission_type', $representative->commission_type) == 'percentage' ? 'selected' : '' }}>نسبة مئوية</option>
                            </select>
                            @error('commission_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">قيمة العمولة</label>
                            <input type="number" step="0.01" name="commission_value" class="form-control @error('commission_value') is-invalid @enderror" value="{{ old('commission_value', $representative->commission_value) }}">
                            @error('commission_value') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">ملاحظات</label>
                            <textarea name="notes" class="form-control" rows="3">{{ old('notes', $representative->notes) }}</textarea>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('subscriber.app.representatives.index') }}" class="btn btn-light">إلغاء</a>
                        <button type="submit" class="btn btn-primary px-4">تحديث البيانات</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
