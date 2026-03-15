@extends('subscriber.layouts.print')

@section('title', $type . ' - ' . $document->invoice_number)

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
        <p><strong>إلى:</strong> {{ $party->name }}</p>
        <p><strong>الهاتف:</strong> {{ $party->phone }}</p>
        @if($party->address)
            <p><strong>العنوان:</strong> {{ $party->address }}</p>
        @endif
    </div>
    <div>
        <p><strong>رقم المستند:</strong> {{ $document->invoice_number }}</p>
        <p><strong>التاريخ:</strong> {{ $document->invoice_date->format('Y-m-d') }}</p>
        <p><strong>الحالة:</strong> {{ $document->status == 'posted' ? 'معتمدة' : 'مسودة' }}</p>
    </div>
</div>

<table>
    <thead>
        <tr>
            <th style="width: 50px;">#</th>
            <th>المنتج</th>
            <th style="width: 80px; text-align: center;">الكمية</th>
            <th style="width: 100px; text-align: end;">السعر</th>
            <th style="width: 120px; text-align: end;">الإجمالي</th>
        </tr>
    </thead>
    <tbody>
        @foreach($items as $index => $item)
        <tr>
            <td style="text-align: center;">{{ $index + 1 }}</td>
            <td>
                {{ $item->product->name }}
                @if($item->product->sku) <small>({{ $item->product->sku }})</small> @endif
            </td>
            <td style="text-align: center;">{{ number_format($item->quantity, 2) }}</td>
            <td style="text-align: end;">{{ number_format($item->unit_price, 2) }}</td>
            <td style="text-align: end;">{{ number_format($item->total_price, 2) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="totals">
    <p><span>الإجمالي الفرعي:</span> <span>{{ number_format($document->subtotal, 2) }} د.ع</span></p>
    @if($document->discount > 0)
        <p><span>الخصم:</span> <span>{{ number_format($document->discount, 2) }} د.ع</span></p>
    @endif
    @if($document->tax_amount > 0)
        <p><span>الضريبة:</span> <span>{{ number_format($document->tax_amount, 2) }} د.ع</span></p>
    @endif
    <p class="grand-total"><span>الإجمالي النهائي:</span> <span>{{ number_format($document->total_amount, 2) }} د.ع</span></p>
</div>

@if($document->notes)
<div style="margin-top: 30px; font-size: 13px;">
    <p><strong>ملاحظات:</strong></p>
    <p>{{ $document->notes }}</p>
</div>
@endif

<div style="margin-top: 50px; display: flex; justify-content: space-around;">
    <div style="text-align: center; border-top: 1px solid #333; width: 150px; padding-top: 5px;">توقيع العميل</div>
    <div style="text-align: center; border-top: 1px solid #333; width: 150px; padding-top: 5px;">ختم المتجر</div>
    @if(isset($store['footer']) && $store['footer'])
    <div class="print-footer mt-5 border-top pt-3">
        <p class="mb-0">{{ $store['footer'] }}</p>
    </div>
    @endif
</div>
@endsection
