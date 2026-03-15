@extends('admin.layouts.admin-layout')

@section('title', 'إعدادات النظام')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">تكوين إعدادات المنصة</h4>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

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

                <form action="{{ route('admin.settings') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-3">
                            <div class="nav flex-column nav-pills" id="settings-pills-tab" role="tablist" aria-orientation="vertical">
                                <button class="nav-link active text-start" id="general-settings-tab" data-bs-toggle="pill" data-bs-target="#general-settings" type="button" role="tab">
                                    <iconify-icon icon="solar:settings-broken" class="me-1"></iconify-icon> الإعدادات العامة
                                </button>
                                <button class="nav-link text-start" id="registration-settings-tab" data-bs-toggle="pill" data-bs-target="#registration-settings" type="button" role="tab">
                                    <iconify-icon icon="solar:user-plus-broken" class="me-1"></iconify-icon> إعدادات التسجيل
                                </button>
                                <button class="nav-link text-start" id="payment-settings-tab" data-bs-toggle="pill" data-bs-target="#payment-settings" type="button" role="tab">
                                    <iconify-icon icon="solar:wallet-broken" class="me-1"></iconify-icon> الدفع اليدوي
                                </button>
                                <button class="nav-link text-start" id="branding-settings-tab" data-bs-toggle="pill" data-bs-target="#branding-settings" type="button" role="tab">
                                    <iconify-icon icon="solar:palette-broken" class="me-1"></iconify-icon> الهوية البصرية
                                </button>
                            </div>
                        </div>

                        <div class="col-md-9 mt-3 mt-md-0">
                            <div class="tab-content" id="settings-pills-tabContent">
                                <!-- General Settings -->
                                <div class="tab-pane fade show active" id="general-settings" role="tabpanel">
                                    <h5 class="mb-3">الإعدادات الأساسية</h5>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label">اسم المنصة</label>
                                            <input type="text" name="platform_name" class="form-control" value="{{ $settings['platform_name'] }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">وصف قصير (Tagline)</label>
                                            <input type="text" name="platform_tagline" class="form-control" value="{{ $settings['platform_tagline'] }}">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label">بريد الدعم الفني</label>
                                            <input type="email" name="support_email" class="form-control" value="{{ $settings['support_email'] }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">رقم هاتف الدعم</label>
                                            <input type="text" name="support_phone" class="form-control" value="{{ $settings['support_phone'] }}">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">عنوان الشركة</label>
                                        <textarea name="company_address" class="form-control" rows="2">{{ $settings['company_address'] }}</textarea>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label class="form-label">العملة الافتراضية</label>
                                            <input type="text" name="default_currency" class="form-control" value="{{ $settings['default_currency'] }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">المنطقة الزمنية</label>
                                            <input type="text" name="default_timezone" class="form-control" value="{{ $settings['default_timezone'] }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">اللغة الافتراضية</label>
                                            <select name="default_locale" class="form-select">
                                                <option value="ar" {{ $settings['default_locale'] == 'ar' ? 'selected' : '' }}>العربية</option>
                                                <option value="en" {{ $settings['default_locale'] == 'en' ? 'selected' : '' }}>English</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Registration Settings -->
                                <div class="tab-pane fade" id="registration-settings" role="tabpanel">
                                    <h5 class="mb-3">إعدادات تسجيل الحسابات</h5>
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" name="registration_enabled" id="regEnabled" {{ $settings['registration_enabled'] ? 'checked' : '' }}>
                                        <label class="form-check-label" for="regEnabled">تفعيل إمكانية التسجيل للعامة</label>
                                    </div>
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" name="auto_activate_accounts" id="autoActivate" {{ $settings['auto_activate_accounts'] ? 'checked' : '' }}>
                                        <label class="form-check-label" for="autoActivate">تفعيل الحسابات تلقائياً بعد التسجيل</label>
                                    </div>
                                    <hr>
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" name="trial_enabled" id="trialEnabled" {{ $settings['trial_enabled'] ? 'checked' : '' }}>
                                        <label class="form-check-label" for="trialEnabled">تفعيل الفترة التجريبية</label>
                                    </div>
                                    <div class="mb-3 w-50">
                                        <label class="form-label">مدة الفترة التجريبية (بالأيام)</label>
                                        <input type="number" name="trial_days" class="form-control" value="{{ $settings['trial_days'] }}">
                                    </div>
                                </div>

                                <!-- Payment Settings -->
                                <div class="tab-pane fade" id="payment-settings" role="tabpanel">
                                    <h5 class="mb-3">إعدادات الدفع اليدوي والتحويلات</h5>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label">اسم المستلم</label>
                                            <input type="text" name="payment_receiver_name" class="form-control" value="{{ $settings['payment_receiver_name'] }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">رقم هاتف الدفع</label>
                                            <input type="text" name="payment_phone" class="form-control" value="{{ $settings['payment_phone'] }}">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">رقم الحساب البنكي / المحفظة</label>
                                        <input type="text" name="payment_account_number" class="form-control" value="{{ $settings['payment_account_number'] }}">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">تعليمات الدفع للعميل</label>
                                        <textarea name="payment_instructions" class="form-control" rows="4">{{ $settings['payment_instructions'] }}</textarea>
                                    </div>
                                </div>

                                <!-- Branding Settings -->
                                <div class="tab-pane fade" id="branding-settings" role="tabpanel">
                                    <h5 class="mb-3">الهوية البصرية للمنصة</h5>
                                    <div class="mb-4">
                                        <label class="form-label d-block">شعار المنصة (Logo)</label>
                                        @if($settings['platform_logo'])
                                            <div class="mb-2">
                                                <img src="{{ Storage::url($settings['platform_logo']) }}" alt="Logo" class="img-thumbnail" style="max-height: 80px;">
                                            </div>
                                        @endif
                                        <input type="file" name="platform_logo" class="form-control">
                                        <small class="text-muted">المقاس الموصى به: 200x50 بكسل. الحد الأقصى 2 ميجا.</small>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label d-block">أيقونة المتصفح (Favicon)</label>
                                        @if($settings['favicon'])
                                            <div class="mb-2">
                                                <img src="{{ Storage::url($settings['favicon']) }}" alt="Favicon" class="img-thumbnail" style="max-height: 32px;">
                                            </div>
                                        @endif
                                        <input type="file" name="favicon" class="form-control">
                                        <small class="text-muted">المقاس الموصى به: 32x32 بكسل.</small>
                                    </div>
                                    <div class="mb-3 w-50">
                                        <label class="form-label">اللون الرئيسي للنظام</label>
                                        <input type="color" name="primary_color" class="form-control form-control-color" value="{{ $settings['primary_color'] }}">
                                    </div>
                                </div>
                            </div>

                            <hr>
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary px-4">حفظ جميع الإعدادات</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tab persistence
    const lastTab = localStorage.getItem('activeSettingsTab');
    if (lastTab) {
        const tabElement = document.querySelector(`[data-bs-target="${lastTab}"]`);
        if (tabElement) {
            new bootstrap.Tab(tabElement).show();
        }
    }

    document.querySelectorAll('[data-bs-toggle="pill"]').forEach(tab => {
        tab.addEventListener('shown.bs.tab', function(event) {
            localStorage.setItem('activeSettingsTab', event.target.getAttribute('data-bs-target'));
        });
    });
});
</script>
@endsection
