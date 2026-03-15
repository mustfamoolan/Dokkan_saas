@extends('subscriber.layouts.app')

@section('title', 'إعدادات المتجر')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm overflow-hidden">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">إعدادات المتجر والتفضيلات</h5>
            </div>
            <div class="card-body p-0">
                <div class="row g-0">
                    <div class="col-md-3 border-start">
                        <div class="nav flex-column nav-pills p-3" id="settings-tabs" role="tablist" aria-orientation="vertical">
                            <button class="nav-link active text-end mb-2" id="branding-tab" data-bs-toggle="pill" data-bs-target="#branding" type="button" role="tab">
                                <iconify-icon icon="solar:shop-2-bold" class="me-2"></iconify-icon> الهوية (Branding)
                            </button>
                            <button class="nav-link text-end mb-2" id="operational-tab" data-bs-toggle="pill" data-bs-target="#operational" type="button" role="tab">
                                <iconify-icon icon="solar:settings-minimalistic-bold" class="me-2"></iconify-icon> الإعدادات التشغيلية
                            </button>
                            <button class="nav-link text-end mb-2" id="numbering-tab" data-bs-toggle="pill" data-bs-target="#numbering" type="button" role="tab">
                                <iconify-icon icon="solar:list-number-bold" class="me-2"></iconify-icon> الترقيم (Numbering)
                            </button>
                            <button class="nav-link text-end mb-2" id="printing-tab" data-bs-toggle="pill" data-bs-target="#printing" type="button" role="tab">
                                <iconify-icon icon="solar:printer-bold" class="me-2"></iconify-icon> إعدادات الطباعة
                            </button>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="tab-content p-4" id="settings-tabContent">
                            
                            <!-- Branding Section -->
                            <div class="tab-pane fade show active" id="branding" role="tabpanel">
                                <form action="{{ route('subscriber.app.settings.store.branding') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <h5 class="mb-4">بيانات هوية المتجر</h5>
                                    
                                    <div class="row g-3">
                                        <div class="col-md-12 mb-3 text-center">
                                            <div class="mb-3">
                                                @if($store->logo)
                                                    <img src="{{ $store->logo }}" alt="Logo" class="rounded border p-1" style="max-height: 120px;">
                                                @else
                                                    <div class="mx-auto bg-light rounded border d-flex align-items-center justify-content-center" style="width: 120px; height: 120px;">
                                                        <iconify-icon icon="solar:shop-2-bold" class="fs-1 text-muted"></iconify-icon>
                                                    </div>
                                                @endif
                                            </div>
                                            <label class="btn btn-soft-primary btn-sm">
                                                تغيير الشعار
                                                <input type="file" name="logo" class="d-none">
                                            </label>
                                            <p class="text-muted small mt-2">يفضل استخدام صورة مربعة بحجم 512x512 بكسل.</p>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">اسم المتجر</label>
                                            <input type="text" name="name" class="form-control" value="{{ old('name', $store->name) }}" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">رقم الهاتف</label>
                                            <input type="text" name="phone" class="form-control" value="{{ old('phone', $store->phone) }}">
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">العنوان</label>
                                            <textarea name="address" class="form-control" rows="2">{{ old('address', $store->address) }}</textarea>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">العملة</label>
                                            <input type="text" name="currency" class="form-control" value="{{ old('currency', $store->currency) }}" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">التوقيت (Timezone)</label>
                                            <input type="text" name="timezone" class="form-control" value="{{ old('timezone', $store->timezone) }}" required>
                                        </div>
                                    </div>
                                    
                                    <hr class="my-4">
                                    <div class="text-end">
                                        <button type="submit" class="btn btn-primary">حفظ الهوية</button>
                                    </div>
                                </form>
                            </div>

                            <!-- Operational Section -->
                            <div class="tab-pane fade" id="operational" role="tabpanel">
                                <form action="{{ route('subscriber.app.settings.store.operational') }}" method="POST">
                                    @csrf
                                    <h5 class="mb-4">التفضيلات والقيم الافتراضية</h5>
                                    
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label">المستودع الافتراضي</label>
                                            <select name="default_warehouse_id" class="form-select">
                                                <option value="">-- اختر مستودع --</option>
                                                @foreach($warehouses as $wh)
                                                    <option value="{{ $wh->id }}" {{ old('default_warehouse_id', $config->default_warehouse_id) == $wh->id ? 'selected' : '' }}>
                                                        {{ $wh->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">الصندوق المالي الافتراضي</label>
                                            <select name="default_cashbox_id" class="form-select">
                                                <option value="">-- اختر صندوق --</option>
                                                @foreach($cashboxes as $cb)
                                                    <option value="{{ $cb->id }}" {{ old('default_cashbox_id', $config->default_cashbox_id) == $cb->id ? 'selected' : '' }}>
                                                        {{ $cb->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label">العميل الافتراضي (للمبيعات السريعة)</label>
                                            <select name="default_walk_in_customer_id" class="form-select">
                                                <option value="">-- اختر عميل --</option>
                                                @foreach($customers as $c)
                                                    <option value="{{ $c->id }}" {{ old('default_walk_in_customer_id', $config->default_walk_in_customer_id) == $c->id ? 'selected' : '' }}>
                                                        {{ $c->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        
                                        <div class="col-12 mt-4">
                                            <div class="form-check form-switch mb-2">
                                                <input class="form-check-input" type="checkbox" name="allow_sale_without_customer" value="1" {{ $config->allow_sale_without_customer ? 'checked' : '' }}>
                                                <label class="form-check-label">السماح بالبيع بدون تحديد عميل</label>
                                            </div>
                                            <div class="form-check form-switch mb-2">
                                                <input class="form-check-input" type="checkbox" name="allow_negative_stock" value="1" {{ $config->allow_negative_stock ? 'checked' : '' }}>
                                                <label class="form-check-label">السماح ببيع كميات غير متوفرة (مخزون سالب)</label>
                                            </div>
                                        </div>
                                    </div>

                                    <hr class="my-4">
                                    <div class="text-end">
                                        <button type="submit" class="btn btn-primary">حفظ التفضيلات</button>
                                    </div>
                                </form>
                            </div>

                            <!-- Numbering Section -->
                            <div class="tab-pane fade" id="numbering" role="tabpanel">
                                <form action="{{ route('subscriber.app.settings.store.numbering') }}" method="POST">
                                    @csrf
                                    <h5 class="mb-4">تخصيص تسلسل المرقبات (Prefixes)</h5>
                                    <div class="alert alert-soft-info small mb-4">
                                        <iconify-icon icon="solar:info-circle-bold" class="me-1"></iconify-icon>
                                        تغيير البادئة سيؤثر على الفواتير والمستندات الجديدة فقط. لن يتم تغيير أرقام المستندات القديمة.
                                    </div>

                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label">بادئة فواتير البيع</label>
                                            <input type="text" name="sales_prefix" class="form-control" value="{{ old('sales_prefix', $config->sales_prefix) }}" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">بادئة فواتير الشراء</label>
                                            <input type="text" name="purchase_prefix" class="form-control" value="{{ old('purchase_prefix', $config->purchase_prefix) }}" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">بادئة سندات القبض</label>
                                            <input type="text" name="customer_payment_prefix" class="form-control" value="{{ old('customer_payment_prefix', $config->customer_payment_prefix) }}" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">بادئة سندات الصرف</label>
                                            <input type="text" name="supplier_payment_prefix" class="form-control" value="{{ old('supplier_payment_prefix', $config->supplier_payment_prefix) }}" required>
                                        </div>
                                    </div>

                                    <hr class="my-4">
                                    <div class="text-end">
                                        <button type="submit" class="btn btn-primary">حفظ الترقيم</button>
                                    </div>
                                </form>
                            </div>

                            <!-- Printing Section -->
                            <div class="tab-pane fade" id="printing" role="tabpanel">
                                <form action="{{ route('subscriber.app.settings.store.printing') }}" method="POST">
                                    @csrf
                                    <h5 class="mb-4">إعدادات الطباعة والتقارير</h5>
                                    
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <label class="form-label">عنوان المطبوعات (Header Title)</label>
                                            <input type="text" name="print_header_title" class="form-control" value="{{ old('print_header_title', $config->print_header_title) }}" placeholder="اتركه فارغاً لاستخدام اسم المتجر">
                                        </div>
                                        
                                        <div class="col-12 mt-3">
                                            <p class="form-label fw-bold">ماذا يظهر في الترويسة؟</p>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="show_logo_on_print" value="1" {{ $config->show_logo_on_print ? 'checked' : '' }}>
                                                <label class="form-check-label">الشعار</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="show_phone_on_print" value="1" {{ $config->show_phone_on_print ? 'checked' : '' }}>
                                                <label class="form-check-label">رقم الهاتف</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="show_address_on_print" value="1" {{ $config->show_address_on_print ? 'checked' : '' }}>
                                                <label class="form-check-label">العنوان</label>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <label class="form-label">ملاحظة أسفل المستند (Footer Note)</label>
                                            <textarea name="print_footer_note" class="form-control" rows="3">{{ old('print_footer_note', $config->print_footer_note) }}</textarea>
                                        </div>
                                    </div>

                                    <hr class="my-4">
                                    <div class="text-end">
                                        <button type="submit" class="btn btn-primary">حفظ إعدادات الطباعة</button>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
