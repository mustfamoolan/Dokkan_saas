@extends('admin.layouts.admin-layout')

@section('title', 'تعديل الاشتراك')

@section('content')
<div class="row">
    <div class="col-xl-8 mx-auto">
        <div class="card">
            <div class="card-header bg-info-subtle">
                <h4 class="card-title text-info">تعديل الاشتراك #{{ $subscription->id }}</h4>
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

                <div class="alert alert-light border-0 mb-4 py-2">
                    <p class="mb-0"><strong>المشترك:</strong> {{ $subscription->subscriber->name }} | <strong>المتجر:</strong> {{ $subscription->store->name }}</p>
                </div>

                <form action="{{ route('admin.subscriptions.update', $subscription->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">الباقة</label>
                            <select name="plan_id" class="form-control" required>
                                @foreach($plans as $plan)
                                    <option value="{{ $plan->id }}" {{ $subscription->plan_id == $plan->id ? 'selected' : '' }}>{{ $plan->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">دورة الفوترة</label>
                            <select name="billing_cycle" class="form-control" required>
                                <option value="monthly" {{ $subscription->billing_cycle == 'monthly' ? 'selected' : '' }}>شهري</option>
                                <option value="yearly" {{ $subscription->billing_cycle == 'yearly' ? 'selected' : '' }}>سنوي</option>
                                <option value="trial" {{ $subscription->billing_cycle == 'trial' ? 'selected' : '' }}>تجريبي</option>
                                <option value="custom" {{ $subscription->billing_cycle == 'custom' ? 'selected' : '' }}>مخصص</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">الحالة</label>
                            <select name="status" class="form-control" required>
                                <option value="pending" {{ $subscription->status == 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
                                <option value="active" {{ $subscription->status == 'active' ? 'selected' : '' }}>نشط</option>
                                <option value="trial" {{ $subscription->status == 'trial' ? 'selected' : '' }}>تجريبي</option>
                                <option value="expired" {{ $subscription->status == 'expired' ? 'selected' : '' }}>منتهي</option>
                                <option value="suspended" {{ $subscription->status == 'suspended' ? 'selected' : '' }}>موقف</option>
                                <option value="cancelled" {{ $subscription->status == 'cancelled' ? 'selected' : '' }}>ملغي</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">تاريخ البداية</label>
                            <input type="date" name="starts_at" class="form-control" value="{{ $subscription->starts_at->format('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">تاريخ النهاية</label>
                            <input type="date" name="ends_at" class="form-control" value="{{ $subscription->ends_at->format('Y-m-d') }}" required>
                        </div>
                    </div>

                    <div class="row border-top pt-3 mt-2">
                        <div class="col-md-4 mb-3">
                            <div class="form-check form-switch mt-4">
                                <input class="form-check-input" type="checkbox" name="is_trial" id="isTrial" {{ $subscription->is_trial ? 'checked' : '' }}>
                                <label class="form-check-label" for="isTrial">فترة تجريبية</label>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">أيام التجربة</label>
                            <input type="number" name="trial_days" class="form-control" value="{{ $subscription->trial_days }}" min="0">
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-check form-switch mt-4">
                                <input class="form-check-input" type="checkbox" name="auto_renew" id="autoRenew" {{ $subscription->auto_renew ? 'checked' : '' }}>
                                <label class="form-check-label" for="autoRenew">تجديد تلقائي</label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">ملاحظات</label>
                        <textarea name="notes" class="form-control" rows="3">{{ $subscription->notes }}</textarea>
                    </div>

                    <div class="text-end border-top pt-3 mt-3">
                        <a href="{{ route('admin.subscriptions') }}" class="btn btn-light px-4 me-2">إلغاء</a>
                        <button type="submit" class="btn btn-info px-5">تحديث الاشتراك</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
