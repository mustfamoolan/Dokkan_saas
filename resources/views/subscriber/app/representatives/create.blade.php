@extends('subscriber.layouts.app')

@section('title', 'إضافة مندوب جديد')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card mt-3">
            <div class="card-header bg-white">
                <h5 class="mb-0">بيانات المندوب الجديد</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('subscriber.app.representatives.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">الاسم الكامل <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">رقم الهاتف <span class="text-danger">*</span></label>
                            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" required>
                            @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">البريد الإلكتروني</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}">
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">كلمة المرور <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">تأكيد كلمة المرور <span class="text-danger">*</span></label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">حالة المندوب</label>
                            <select name="is_active" class="form-select @error('is_active') is-invalid @enderror">
                                <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>نشط</option>
                                <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>معطل</option>
                            </select>
                            @error('is_active') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <hr class="my-3 text-muted">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">نوع العمولة</label>
                            <select name="commission_type" class="form-select @error('commission_type') is-invalid @enderror">
                                <option value="">بدون عمولة</option>
                                <option value="fixed" {{ old('commission_type') == 'fixed' ? 'selected' : '' }}>مبلغ ثابت</option>
                                <option value="percentage" {{ old('commission_type') == 'percentage' ? 'selected' : '' }}>نسبة مئوية</option>
                            </select>
                            @error('commission_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">قيمة العمولة</label>
                            <input type="number" step="0.01" name="commission_value" class="form-control @error('commission_value') is-invalid @enderror" value="{{ old('commission_value') }}">
                            @error('commission_value') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">ملاحظات</label>
                            <textarea name="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('subscriber.app.representatives.index') }}" class="btn btn-light">إلغاء</a>
                        <button type="submit" class="btn btn-primary px-4">حفظ المندوب</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
