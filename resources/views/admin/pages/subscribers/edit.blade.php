@extends('admin.layouts.admin-layout')

@section('title', 'تعديل بيانات المشترك')

@section('content')
<div class="row">
    <div class="col-xl-10 mx-auto">
        <form action="{{ route('admin.subscribers.update', $subscriber->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="row">
                <!-- Subscriber Info -->
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header bg-primary-subtle">
                            <h4 class="card-title text-primary">تعديل بيانات المشترك: {{ $subscriber->name }}</h4>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">الاسم الكامل</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name', $subscriber->name) }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">رقم الهاتف</label>
                                <input type="text" name="phone" class="form-control" value="{{ old('phone', $subscriber->phone) }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">البريد الإلكتروني (اختياري)</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email', $subscriber->email) }}">
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">كلمة المرور (اتركها فارغة للتخطي)</label>
                                        <input type="password" name="password" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">تأكيد كلمة المرور</label>
                                        <input type="password" name="password_confirmation" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">الحالة</label>
                                        <select name="status" class="form-control" required>
                                            <option value="active" {{ old('status', $subscriber->status) == 'active' ? 'selected' : '' }}>نشط</option>
                                            <option value="pending" {{ old('status', $subscriber->status) == 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
                                            <option value="suspended" {{ old('status', $subscriber->status) == 'suspended' ? 'selected' : '' }}>موقف</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check form-switch mt-4 pt-1">
                                        <input class="form-check-input" type="checkbox" name="is_active" id="isActive" {{ old('is_active', $subscriber->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="isActive">تفعيل الحساب</label>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">ملاحظات إدارية</label>
                                <textarea name="notes" class="form-control" rows="3">{{ old('notes', $subscriber->notes) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Store Info -->
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header bg-info-subtle">
                            <h4 class="card-title text-info">بيانات المتجر: {{ $subscriber->store->name }}</h4>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">اسم المتجر</label>
                                <input type="text" name="store_name" class="form-control" value="{{ old('store_name', $subscriber->store->name) }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">هاتف المتجر</label>
                                <input type="text" name="store_phone" class="form-control" value="{{ old('store_phone', $subscriber->store->phone) }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">عنوان المتجر</label>
                                <input type="text" name="store_address" class="form-control" value="{{ old('store_address', $subscriber->store->address) }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">شعار المتجر</label>
                                @if($subscriber->store->logo)
                                    <div class="mb-2">
                                        <img src="{{ Storage::url($subscriber->store->logo) }}" alt="Logo" class="img-thumbnail" style="height: 60px;">
                                    </div>
                                @endif
                                <input type="file" name="store_logo" class="form-control">
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">العملة</label>
                                        <input type="text" name="currency" class="form-control" value="{{ old('currency', $subscriber->store->currency) }}" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">اللغة</label>
                                        <select name="locale" class="form-control" required>
                                            <option value="ar" {{ old('locale', $subscriber->store->locale) == 'ar' ? 'selected' : '' }}>العربية</option>
                                            <option value="en" {{ old('locale', $subscriber->store->locale) == 'en' ? 'selected' : '' }}>English</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">المنطقة الزمنية</label>
                                        <input type="text" name="timezone" class="form-control" value="{{ old('timezone', $subscriber->store->timezone) }}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">حالة المتجر</label>
                                <select name="store_status" class="form-control" required>
                                    <option value="active" {{ old('store_status', $subscriber->store->status) == 'active' ? 'selected' : '' }}>نشط</option>
                                    <option value="inactive" {{ old('store_status', $subscriber->store->status) == 'inactive' ? 'selected' : '' }}>معطل</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center mb-4">
                <a href="{{ route('admin.subscribers') }}" class="btn btn-light px-5 me-2">إلغاء</a>
                <button type="submit" class="btn btn-primary px-5">تحديث البيانات</button>
            </div>
        </form>
    </div>
</div>
@endsection
