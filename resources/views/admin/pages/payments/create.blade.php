@extends('admin.layouts.admin-layout')

@section('title', 'تسجيل دفع يدوي')

@section('content')
<div class="row">
    <div class="col-xl-8 mx-auto">
        <div class="card">
            <div class="card-header bg-primary-subtle">
                <h4 class="card-title text-primary">تسجيل دفع يدوي للاشتراك</h4>
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

                <form action="{{ route('admin.payments.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label">اختر الاشتراك</label>
                        <select name="subscription_id" class="form-control" required>
                            <option value="">اختر الاشتراك المراد الدفع له...</option>
                            @foreach($subscriptions as $subscription)
                                <option value="{{ $subscription->id }}">
                                    #{{ $subscription->id }} - {{ $subscription->subscriber->name }} ({{ $subscription->store->name }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">المبلغ</label>
                            <input type="number" name="amount" class="form-control" step="0.01" min="0.01" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">العملة</label>
                            <select name="currency" class="form-control" required>
                                <option value="USD">USD - دولار أمريكي</option>
                                <option value="IQD">IQD - دينار عراقي</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">طريقة الدفع</label>
                            <select name="payment_method" class="form-control" required>
                                <option value="bank_transfer">تحويل بنكي</option>
                                <option value="zain_cash">زين كاش</option>
                                <option value="asia_pay">آسيا حوالة</option>
                                <option value="cash">نقدي</option>
                                <option value="other">أخرى</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">رقم المرجع (إن وجد)</label>
                            <input type="text" name="reference_number" class="form-control">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">رفع إيصال الدفع (صورة)</label>
                        <input type="file" name="receipt" class="form-control" accept="image/*" required>
                        <small class="text-muted">يجب رفع صورة واضحة للإيصال.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">ملاحظات إضافية</label>
                        <textarea name="notes" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="text-end border-top pt-3 mt-3">
                        <a href="{{ route('admin.payments') }}" class="btn btn-light px-4 me-2">إلغاء</a>
                        <button type="submit" class="btn btn-primary px-5">تسجيل الدفعة</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
