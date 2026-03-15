@extends('subscriber.layouts.onboarding')

@section('title', 'تهيئة المتجر')

@section('content')
<div class="card auth-card shadow-sm border-0">
    <div class="card-body p-4">
        <div class="onboarding-steps d-flex justify-content-between">
            <div class="step-item active">الحساب</div>
            <div class="step-item active">المتجر</div>
            <div class="step-item">الباقة</div>
            <div class="step-item">الدفع</div>
        </div>

        <h3 class="text-center mb-4">بيانات متجرك</h3>
        <form action="{{ route('subscriber.onboarding.store-setup.save') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label class="form-label">اسم المتجر</label>
                <input type="text" name="name" class="form-control" placeholder="أدخل اسم متجرك" required value="{{ old('name') }}">
                @error('name') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">هاتف المتجر</label>
                <input type="text" name="phone" class="form-control" placeholder="رقم التواصل الخاص بالمتجر" required value="{{ old('phone') }}">
                @error('phone') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">عنوان المتجر</label>
                <input type="text" name="address" class="form-control" placeholder="المدينة، المنطقة، الشارع" required value="{{ old('address') }}">
                @error('address') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
            <div class="mb-4">
                <label class="form-label">شعار المتجر (Logo)</label>
                <input type="file" name="logo" class="form-control" accept="image/*">
                <small class="text-muted">اختياري، يفضل صورة مربعة.</small>
                @error('logo') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
            
            <button type="submit" class="btn btn-primary w-100 py-2">التالي: اختيار الباقة</button>
        </form>
    </div>
</div>
@endsection
