@extends('admin.layouts.admin-layout')

@section('title', 'إضافة مشترك جديد')

@section('content')
<div class="row">
    <div class="col-xl-10 mx-auto">
        <form action="{{ route('admin.subscribers.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="row">
                <!-- Subscriber Info -->
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header bg-primary-subtle">
                            <h4 class="card-title text-primary">بيانات المشترك الأساسية</h4>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">الاسم الكامل</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">رقم الهاتف</label>
                                <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">البريد الإلكتروني (اختياري)</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">كلمة المرور</label>
                                        <input type="password" name="password" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">تأكيد كلمة المرور</label>
                                        <input type="password" name="password_confirmation" class="form-control" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">الحالة</label>
                                        <select name="status" class="form-control" required>
                                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>نشط</option>
                                            <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
                                            <option value="suspended" {{ old('status') == 'suspended' ? 'selected' : '' }}>موقف</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check form-switch mt-4 pt-1">
                                        <input class="form-check-input" type="checkbox" name="is_active" id="isActive" {{ old('is_active', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="isActive">تفعيل الحساب</label>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">ملاحظات إدارية</label>
                                <textarea name="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Store Info -->
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header bg-info-subtle">
                            <h4 class="card-title text-info">بيانات المتجر الأول</h4>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">اسم المتجر</label>
                                <input type="text" name="store_name" class="form-control" value="{{ old('store_name') }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">هاتف المتجر</label>
                                <input type="text" name="store_phone" class="form-control" value="{{ old('store_phone') }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">عنوان المتجر</label>
                                <input type="text" name="store_address" class="form-control" value="{{ old('store_address') }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">شعار المتجر</label>
                                <input type="file" name="store_logo" class="form-control">
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">العملة</label>
                                        <input type="text" name="currency" class="form-control" value="{{ old('currency', 'USD') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">اللغة</label>
                                        <select name="locale" class="form-control" required>
                                            <option value="ar" {{ old('locale') == 'ar' ? 'selected' : '' }}>العربية</option>
                                            <option value="en" {{ old('locale') == 'en' ? 'selected' : '' }}>English</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">المنطقة الزمنية</label>
                                        <input type="text" name="timezone" class="form-control" value="{{ old('timezone', 'UTC') }}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">حالة المتجر</label>
                                <select name="store_status" class="form-control" required>
                                    <option value="active" {{ old('store_status') == 'active' ? 'selected' : '' }}>نشط</option>
                                    <option value="inactive" {{ old('store_status') == 'inactive' ? 'selected' : '' }}>معطل</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center mb-4">
                <a href="{{ route('admin.subscribers') }}" class="btn btn-light px-5 me-2">إلغاء</a>
                <button type="submit" class="btn btn-primary px-5">إضافة المشترك والمتجر</button>
            </div>
        </form>
    </div>
</div>
@endsection
