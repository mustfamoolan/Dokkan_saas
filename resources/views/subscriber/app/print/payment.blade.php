@extends('subscriber.layouts.print')

@section('title', $type . ' - ' . ($document->reference_number ?? $document->id))

@section('document_content')
<header>
    <div class="store-info">
        <h1>{{ $store['name'] }}</h1>
        <p>الهاتف: {{ $store['phone'] }}</p>
        <p>العنوان: {{ $store['address'] }}</p>
    </div>
    <div class="logo">
        @if($store['logo'])
            <img src="{{ $store['logo'] }}" alt="Logo" style="max-height: 80px;">
        @else
            <div style="width: 80px; height: 80px; background: #eee; display: flex; align-items: center; justify-content: center; border-radius: 50%;">
                <span style="font-size: 30px; font-weight: bold; color: #ccc;">D</span>
            </div>
        @endif
    </div>
</header>

<div class="document-title">
    <h2>{{ $type }}</h2>
</div>

<div class="meta-info">
    <div>
        <p><strong>{{ ($document instanceof \App\Models\CustomerPayment) ? 'استلمنا من:' : 'صرفنا إلى:' }}</strong> {{ $party->name }}</p>
        <p><strong>الهاتف:</strong> {{ $party->phone }}</p>
    </div>
    <div>
        <p><strong>رقم السند:</strong> {{ $document->reference_number ?? $document->id }}</p>
        <p><strong>التاريخ:</strong> {{ $document->payment_date->format('Y-m-d') }}</p>
        <p><strong>طريقة الدفع:</strong> نقداً - {{ $document->cashbox->name }}</p>
    </div>
</div>

<div style="border: 2px solid #333; padding: 30px; border-radius: 10px; margin-bottom: 40px; background: #fff;">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div style="font-size: 20px;">
            <p>مبلغ وقدره: <strong>{{ number_format($document->amount, 2) }} د.ع</strong></p>
            <p style="font-size: 16px; margin-top: 20px;">وذلك عن: <span>{{ $document->notes ?? 'تسوية حساب' }}</span></p>
        </div>
        <div style="text-align: center; border: 1px solid #ccc; padding: 15px; border-radius: 5px; min-width: 150px;">
            <div style="font-size: 12px; color: #666; margin-bottom: 5px;">المبلغ بالأرقام</div>
            <div style="font-size: 24px; font-weight: bold;">{{ number_format($document->amount, 0) }}</div>
        </div>
    </div>
</div>

<div style="margin-top: 100px; display: flex; justify-content: space-around;">
    <div style="text-align: center;">
        <div style="border-top: 1px solid #333; width: 200px; padding-top: 5px;">توقيع المستلم</div>
    </div>
    <div style="text-align: center;">
        <div style="border-top: 1px solid #333; width: 200px; padding-top: 5px;">توقيع المدير / الختم</div>
    </div>
</div>
@endsection
