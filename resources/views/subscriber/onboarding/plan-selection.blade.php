@extends('subscriber.layouts.onboarding')

@section('title', 'اختيار الباقة')

@section('content')
<div class="container-fluid mb-5">
    <div class="onboarding-steps d-flex justify-content-center mx-auto mb-5" style="max-width: 500px;">
        <div class="step-item completed">الحساب</div>
        <div class="step-item completed">المتجر</div>
        <div class="step-item active">الباقة</div>
        <div class="step-item">الدفع</div>
    </div>

    <h2 class="text-center mb-5">اختر الباقة المناسبة لعملك</h2>

    <div class="row justify-content-center g-4">
        @foreach($plans as $plan)
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0 transition-transform hover-up">
                <div class="card-body p-4 d-flex flex-column text-center">
                    <h4 class="fw-bold mb-3 text-primary">{{ $plan->name }}</h4>
                    <p class="text-muted mb-4">{{ $plan->description }}</p>
                    
                    <div class="mb-4">
                        @if($plan->is_free)
                            <h2 class="fw-bold">مجانية</h2>
                        @else
                            <h2 class="fw-bold">{{ number_format($plan->price_monthly, 0) }} <small class="fs-14">IQD / شهرياً</small></h2>
                            <p class="text-success mb-0 fw-medium">أو {{ number_format($plan->price_yearly, 0) }} سنوياً</p>
                        @endif
                    </div>

                    <div class="text-start mb-4 flex-grow-1">
                        <ul class="list-unstyled">
                            @foreach($plan->features->take(5) as $feature)
                            <li class="mb-2">
                                <iconify-icon icon="solar:check-circle-bold" class="text-success me-2"></iconify-icon>
                                @php
                                    $label = str_replace('_', ' ', $feature->feature_key);
                                    if ($feature->value_type == 'limit') $label .= ": " . $feature->feature_value;
                                @endphp
                                <span class="fs-14">{{ $label }}</span>
                            </li>
                            @endforeach
                            @if($plan->features->count() > 5)
                                <li class="text-muted fs-13 mt-2">+ والمزيد من الميزات...</li>
                            @endif
                        </ul>
                    </div>

                    <form action="{{ route('subscriber.onboarding.plan-selection.save', $plan->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn {{ $plan->is_free ? 'btn-soft-primary' : 'btn-primary' }} w-100 py-2">
                            اختيار {{ $plan->name }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<style>
    .transition-transform { transition: transform 0.2s ease; }
    .hover-up:hover { transform: translateY(-5px); }
</style>
@endsection
