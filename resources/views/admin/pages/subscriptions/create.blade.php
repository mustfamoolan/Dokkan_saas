@extends('admin.layouts.admin-layout')

@section('title', 'إضافة اشتراك جديد')

@section('content')
<div class="row">
    <div class="col-xl-8 mx-auto">
        <div class="card">
            <div class="card-header bg-primary-subtle">
                <h4 class="card-title text-primary">إنشاء اشتراك يدوي جديد</h4>
            </div>
            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.subscriptions.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">المشترك</label>
                            <select name="subscriber_id" id="subscriber_select" class="form-control" required>
                                <option value="">اختر المشترك...</option>
                                @foreach($subscribers as $subscriber)
                                    <option value="{{ $subscriber->id }}" data-store-id="{{ $subscriber->store->id ?? '' }}">{{ $subscriber->name }} ({{ $subscriber->phone }})</option>
                                @endforeach
                            </select>
                        </div>
                        <input type="hidden" name="store_id" id="store_id_input">
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">الباقة</label>
                            <select name="plan_id" class="form-control" required>
                                <option value="">اختر الباقة...</option>
                                @foreach($plans as $plan)
                                    <option value="{{ $plan->id }}">{{ $plan->name }} ({{ $plan->monthly_price }} $ / شهر)</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">دورة الفوترة</label>
                            <select name="billing_cycle" class="form-control" required>
                                <option value="monthly">شهري</option>
                                <option value="yearly">سنوي</option>
                                <option value="trial">تجريبي</option>
                                <option value="custom">مخصص</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">الحالة</label>
                            <select name="status" class="form-control" required>
                                <option value="active">نشط</option>
                                <option value="pending">قيد الانتظار</option>
                                <option value="trial">تجريبي</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">تاريخ البداية</label>
                            <input type="date" name="starts_at" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">تاريخ النهاية</label>
                            <input type="date" name="ends_at" class="form-control" value="{{ date('Y-m-d', strtotime('+1 month')) }}" required>
                        </div>
                    </div>

                    <div class="row border-top pt-3 mt-2">
                        <div class="col-md-4 mb-3">
                            <div class="form-check form-switch mt-4">
                                <input class="form-check-input" type="checkbox" name="is_trial" id="isTrial">
                                <label class="form-check-label" for="isTrial">فترة تجريبية</label>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">أيام التجربة</label>
                            <input type="number" name="trial_days" class="form-control" value="0" min="0">
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-check form-switch mt-4">
                                <input class="form-check-input" type="checkbox" name="auto_renew" id="autoRenew" checked>
                                <label class="form-check-label" for="autoRenew">تجديد تلقائي</label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">ملاحظات</label>
                        <textarea name="notes" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="text-end border-top pt-3 mt-3">
                        <a href="{{ route('admin.subscriptions') }}" class="btn btn-light px-4 me-2">إلغاء</a>
                        <button type="submit" class="btn btn-primary px-5">حفظ الاشتراك</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('subscriber_select').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const storeId = selectedOption.getAttribute('data-store-id');
    document.getElementById('store_id_input').value = storeId;
});
</script>
@endsection
