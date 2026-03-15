@extends('subscriber.layouts.print')

@section('title', $type . ' - ' . $party->name)

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
    <p style="color: #666;">الفترة: من البداية وحتى تاريخ {{ now()->format('Y-m-d') }}</p>
</div>

<div class="meta-info">
    <div>
        <p><strong>الطرف:</strong> {{ $party->name }}</p>
        <p><strong>الهاتف:</strong> {{ $party->phone }}</p>
    </div>
    <div style="text-align: end;">
        <p><strong>الرصيد الحالي:</strong></p>
        <h3 style="margin: 0; color: #333;">{{ number_format(last($statement)['running_balance'] ?? 0, 2) }} د.ع</h3>
    </div>
</div>

<table>
    <thead>
        <tr>
            <th style="width: 100px;">التاريخ</th>
            <th>نوع الحركة / المرجع</th>
            <th style="width: 100px; text-align: end;">مدين (عليه)</th>
            <th style="width: 100px; text-align: end;">دائن (له)</th>
            <th style="width: 120px; text-align: end;">الرصيد</th>
        </tr>
    </thead>
    <tbody>
        @foreach($statement as $row)
        <tr>
            <td style="font-size: 12px;">{{ \Carbon\Carbon::parse($row['date'])->format('Y-m-d') }}</td>
            <td>
                <div class="fw-bold">{{ $row['type_label'] }}</div>
                @if($row['reference']) <small class="text-muted">مرجع: {{ $row['reference'] }}</small> @endif
            </td>
            <td style="text-align: end;">{{ $row['debit'] > 0 ? number_format($row['debit'], 2) : '-' }}</td>
            <td style="text-align: end;">{{ $row['credit'] > 0 ? number_format($row['credit'], 2) : '-' }}</td>
            <td style="text-align: end; font-weight: bold;">{{ number_format($row['running_balance'], 2) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<div style="margin-top: 30px; padding: 15px; background: #fefefe; border: 1px solid #eee; border-radius: 5px;">
    <p style="font-size: 14px; margin: 0;"><strong>خلاصة الحساب:</strong></p>
    <p style="font-size: 13px; margin: 5px 0 0 0;">
        إجمالي المدين: {{ number_format(collect($statement)->sum('debit'), 2) }} | 
        إجمالي الدائن: {{ number_format(collect($statement)->sum('credit'), 2) }} | 
        الرصيد الصافي: {{ number_format(last($statement)['running_balance'] ?? 0, 2) }} د.ع
    </p>
</div>

<div style="margin-top: 50px; text-align: start; font-size: 13px;">
    <p>أقر أنا الموقع أدناه بصحة الرصيد المذكور أعلاه والمستحق بذمتي.</p>
    <div style="margin-top: 40px; display: flex; justify-content: space-between;">
        <div style="text-align: center; border-top: 1px solid #333; width: 180px; padding-top: 5px;">توقيع العميل / المورد</div>
        <div style="text-align: center; border-top: 1px solid #333; width: 180px; padding-top: 5px;">المحاسب / الختم</div>
    </div>
</div>
@endsection
