@extends('admin.layouts.admin-layout')

@section('title', 'مراقبة الاستهلاك - ' . $store->name)

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card overflow-hidden">
            <div class="card-body bg-light-subtle">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="mb-1 text-primary">{{ $store->name }}</h4>
                        <p class="text-muted mb-0">المشترك: <strong>{{ $store->subscriber->name }}</strong></p>
                    </div>
                    <div class="text-end">
                        <span class="badge bg-soft-info text-info fs-13 px-2 py-1 mb-1">المتجر #{{ $store->id }}</span>
                        <div class="mt-1">
                            <a href="{{ route('admin.stores.show', $store->id) }}" class="btn btn-sm btn-soft-secondary">بيانات المتجر</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row">
    <!-- Numeric Limits Section -->
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header bg-primary-subtle">
                <h4 class="card-title text-primary">حدود الباقة والاستهلاك الرقمي</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-centered table-nowrap mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>الميزة / الحد</th>
                                <th>الاستهلاك الحالي</th>
                                <th>الحد المسموح</th>
                                <th>المتبقي</th>
                                <th>المصدر</th>
                                <th>الحالة</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($usageData['limits'] as $item)
                            <tr>
                                <td>
                                    <strong>{{ $item['key'] }}</strong>
                                </td>
                                <td>
                                    <span class="fs-14 fw-bold">{{ $item['usage'] }}</span>
                                </td>
                                <td>
                                    <span class="fs-14 fw-bold text-dark">{{ $item['limit'] ?? '∞' }}</span>
                                </td>
                                <td>
                                    @if($item['remaining'] !== null)
                                        <span class="badge {{ $item['remaining'] > 0 ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }}">
                                            {{ $item['remaining'] }}
                                        </span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if($item['source'] == 'override')
                                        <span class="badge bg-warning-subtle text-warning">استثناء يدوي</span>
                                    @elseif($item['source'] == 'plan')
                                        <span class="badge bg-primary-subtle text-primary">الباقة</span>
                                    @else
                                        <span class="badge bg-light text-muted">غير محدد</span>
                                    @endif
                                </td>
                                <td>
                                    @if($item['allowed'])
                                        <iconify-icon icon="solar:check-circle-bold" class="text-success fs-20"></iconify-icon>
                                    @else
                                        <iconify-icon icon="solar:danger-circle-bold" class="text-danger fs-20"></iconify-icon>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Boolean Features Section -->
        <div class="card">
            <div class="card-header bg-info-subtle">
                <h4 class="card-title text-info">الميزات والصلاحيات المتاحة</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($usageData['booleans'] as $item)
                    <div class="col-md-6 col-lg-4 mb-3">
                        <div class="p-2 border rounded d-flex align-items-center justify-content-between {{ $item['allowed'] ? 'bg-success-subtle border-success-subtle' : 'bg-light border-light' }}">
                            <div class="text-truncate me-2">
                                <span class="fs-13 fw-medium {{ $item['allowed'] ? 'text-success' : 'text-muted text-decoration-line-through' }}">
                                    {{ $item['key'] }}
                                </span>
                            </div>
                            <div>
                                @if($item['allowed'])
                                    <iconify-icon icon="solar:check-square-bold" class="text-success"></iconify-icon>
                                @else
                                    <iconify-icon icon="solar:close-square-bold" class="text-muted"></iconify-icon>
                                @endif
                            </div>
                        </div>
                        @if($item['source'] == 'override')
                            <small class="text-warning fw-bold d-block mt-1">استثناء يدوي</small>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Overrides & Actions -->
    <div class="col-xl-4">
        <!-- Add Override Form -->
        @can('manage usage overrides')
        <div class="card">
            <div class="card-header border-bottom">
                <h4 class="card-title">إضافة استثناء يدوي (Override)</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.usage.overrides.store', $store->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">الميزة (Key)</label>
                        <select name="feature_key" class="form-control select2" required>
                            <optgroup label="Limits">
                                @foreach(['max_users', 'max_branches', 'max_products', 'max_customers', 'max_suppliers', 'max_representatives', 'max_warehouses', 'max_invoices_per_month', 'max_orders_per_month', 'max_storage_mb'] as $k)
                                    <option value="{{ $k }}">{{ $k }}</option>
                                @endforeach
                            </optgroup>
                            <optgroup label="Booleans">
                                @foreach(['has_reports', 'has_advanced_reports', 'has_pos', 'has_expenses', 'has_debts', 'has_export_excel', 'has_export_pdf', 'has_printing', 'has_multi_branch', 'has_multi_warehouse', 'has_barcode', 'has_notifications', 'has_support', 'has_api_access'] as $k)
                                    <option value="{{ $k }}">{{ $k }}</option>
                                @endforeach
                            </optgroup>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">نوع القيمة</label>
                            <select name="value_type" class="form-control" required>
                                <option value="limit">Limit (عدد)</option>
                                <option value="boolean">Boolean (نعم/لا)</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">القيمة الجديدة</label>
                            <input type="text" name="override_value" class="form-control" placeholder="10 or true" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">ملاحظات</label>
                        <textarea name="notes" class="form-control" rows="2"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">حفظ الاستثناء</button>
                </form>
            </div>
        </div>
        @endcan

        <!-- Active Overrides List -->
        <div class="card">
            <div class="card-header border-bottom">
                <h4 class="card-title">الاستثناءات المفعلة حالياً</h4>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm table-centered mb-0 text-start">
                        <thead>
                            <tr>
                                <th>المفتاح</th>
                                <th>القيمة</th>
                                <th class="text-center">إجراء</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($store->overrides as $override)
                            <tr>
                                <td><code>{{ $override->feature_key }}</code></td>
                                <td>{{ $override->override_value }}</td>
                                <td class="text-center">
                                    <form action="{{ route('admin.usage.overrides.delete', [$store->id, $override->id]) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-link text-danger p-0" onclick="return confirm('هل أنت متأكد من حذف هذا الاستثناء؟')">
                                            <iconify-icon icon="solar:trash-bin-trash-bold" class="fs-18"></iconify-icon>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center py-3 text-muted">لا توجد استثناءات يدوية</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Update Counters (For Testing) -->
        <div class="card border-warning-subtle">
            <div class="card-header bg-warning-subtle">
                <h4 class="card-title text-warning">تحديث عدادات الاستهلاك (للفحص)</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.usage.counters.update', $store->id) }}" method="POST">
                    @csrf
                    <div class="mb-2">
                        <select name="counter_key" class="form-control form-control-sm">
                            @foreach(['users_count', 'branches_count', 'products_count', 'customers_count', 'suppliers_count', 'representatives_count', 'warehouses_count', 'invoices_this_month', 'orders_this_month', 'storage_used_mb'] as $c)
                                <option value="{{ $c }}">{{ $c }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="input-group input-group-sm">
                        <input type="number" name="current_value" class="form-control" placeholder="القيمة الحالية" required>
                        <button class="btn btn-warning" type="submit">تحديث</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
