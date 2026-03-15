@extends('subscriber.layouts.onboarding')

@section('title', 'إتمام الدفع')

@section('content')
<div class="card auth-card shadow-sm border-0">
    <div class="card-body p-4">
        <div class="onboarding-steps d-flex justify-content-between mb-4">
            <div class="step-item completed">الحساب</div>
            <div class="step-item completed">المتجر</div>
            <div class="step-item completed">الباقة</div>
            <div class="step-item active">الدفع</div>
        </div>

        <h3 class="text-center mb-4">بيانات الدفع اليدوي</h3>
        
        <div class="bg-primary-subtle p-3 rounded mb-4">
            <h5 class="text-primary mb-3">باقة: {{ $plan->name }}</h5>
            <p class="mb-1"><strong>المستلم:</strong> {{ $settings['payment_receiver_name'] ?? 'Dokkan Support' }}</p>
            <p class="mb-1"><strong>رقم الهاتف:</strong> {{ $settings['payment_phone'] ?? '-' }}</p>
            <p class="mb-1"><strong>رقم الحساب:</strong> {{ $settings['payment_account_number'] ?? '-' }}</p>
        </div>

        @if($settings['payment_instructions'])
        <div class="mb-4">
            <h6 class="fw-bold">تعليمات الدفع:</h6>
            <p class="text-muted small">{{ $settings['payment_instructions'] }}</p>
        </div>
        @endif

        <form action="{{ route('subscriber.onboarding.payment.save', $plan->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="mb-3">
                <label class="form-label">دورة الفوترة</label>
                <div class="d-flex gap-3">
                    <div class="form-check border p-2 rounded flex-fill">
                        <input class="form-check-input ms-0 me-2" type="radio" name="billing_cycle" id="monthly" value="monthly" checked>
                        <label class="form-check-label" for="monthly">
                            شهرياً ({{ number_format($plan->price_monthly, 0) }} {{ $settings['default_currency'] ?? 'IQD' }})
                        </label>
                    </div>
                    <div class="form-check border p-2 rounded flex-fill">
                        <input class="form-check-input ms-0 me-2" type="radio" name="billing_cycle" id="yearly" value="yearly">
                        <label class="form-check-label" for="yearly">
                            سنوياً ({{ number_format($plan->price_yearly, 0) }} {{ $settings['default_currency'] ?? 'IQD' }})
                        </label>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">إيصال الدفع (صورة)</label>
                <input type="file" name="receipt" class="form-control" accept="image/*" required>
                <small class="text-muted">يرجى رفع صورة واضحة لإيصال التحويل.</small>
                @error('receipt') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">رقم مرجعي (اختياري)</label>
                <input type="text" name="reference_number" class="form-control" placeholder="رقم التحويل أو العملية">
            </div>

            <div class="mb-4">
                <label class="form-label">ملاحظات (اختياري)</label>
                <textarea name="notes" class="form-control" rows="2"></textarea>
            </div>
            
            <button type="submit" class="btn btn-success w-100 py-2">إرسال الإيصال للمراجعة</button>
            <a href="{{ route('subscriber.onboarding.plan-selection') }}" class="btn btn-link w-100 mt-2 text-decoration-none text-muted">العودة لاختيار الباقة</a>
        </form>
    </div>
</div>
@endsection
