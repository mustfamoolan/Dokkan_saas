@extends('subscriber.layouts.app')

@section('title', 'عرض فاتورة بيع')

@section('content')
<div class="row">
    <div class="col-md-9">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">بيانات الفاتورة: {{ $sale->invoice_number }}</h4>
                <div class="d-flex gap-2">
                    <a href="{{ route('subscriber.app.print.sales-invoice', $sale) }}" target="_blank" class="btn btn-soft-secondary btn-sm">
                        <iconify-icon icon="solar:printer-bold" class="me-1"></iconify-icon> طباعة
                    </a>
                    @if($sale->status == 'draft')
                        <a href="{{ route('subscriber.app.sales.edit', $sale) }}" class="btn btn-soft-primary btn-sm">تعديل</a>
                        <form action="{{ route('subscriber.app.sales.post', $sale) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm">اعتماد الفاتورة</button>
                        </form>
                    @endif
                </div>
            </div>
            <div class="card-body">
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                
                <div class="row mb-4">
                    <div class="col-sm-4">
                        <h6 class="text-muted mb-1">العميل:</h6>
                        <h5>{{ $sale->customer->name }}</h5>
                        <p class="mb-0">{{ $sale->customer->phone }}</p>
                    </div>
                    <div class="col-sm-4 text-center">
                        <h6 class="text-muted mb-1">المستودع:</h6>
                        <h5>{{ $sale->warehouse->name }}</h5>
                    </div>
                    <div class="col-sm-4 text-end">
                        <h6 class="text-muted mb-1">تاريخ الفاتورة:</h6>
                        <h5>{{ $sale->invoice_date->format('Y-m-d') }}</h5>
                        <p class="mb-0">الحالة: 
                            @if($sale->status == 'draft')
                                <span class="text-warning fw-bold">مسودة</span>
                            @elseif($sale->status == 'posted')
                                <span class="text-success fw-bold">معتمدة</span>
                            @else
                                <span class="text-danger fw-bold">ملغاة</span>
                            @endif
                        </p>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="bg-light">
                            <tr>
                                <th>المنتج</th>
                                <th class="text-center">الكمية</th>
                                <th class="text-end">سعر الوحدة</th>
                                <th class="text-end">الإجمالي</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sale->items as $item)
                            <tr>
                                <td>{{ $item->product->name }}</td>
                                <td class="text-center">{{ number_format($item->quantity, 2) }}</td>
                                <td class="text-end">{{ number_format($item->unit_price, 0) }} د.ع</td>
                                <td class="text-end fw-bold">{{ number_format($item->line_total, 0) }} د.ع</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="h5">
                            <tr>
                                <th colspan="3" class="text-end">الإجمالي الفرعي:</th>
                                <th class="text-end">{{ number_format($sale->subtotal, 0) }} د.ع</th>
                            </tr>
                            @if($sale->discount_amount > 0)
                            <tr>
                                <th colspan="3" class="text-end text-danger">الخصم:</th>
                                <th class="text-end text-danger">-{{ number_format($sale->discount_amount, 0) }} د.ع</th>
                            </tr>
                            @endif
                            <tr class="table-primary">
                                <th colspan="3" class="text-end">الصافي النهائي:</th>
                                <th class="text-end text-primary">{{ number_format($sale->total_amount, 0) }} د.ع</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                @if($sale->notes)
                <div class="mt-4">
                    <h6 class="text-muted">ملاحظات:</h6>
                    <div class="bg-light p-3 rounded">{{ $sale->notes }}</div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card h-100">
            <div class="card-header">
                <h4 class="card-title">معلومات إضافية</h4>
            </div>
            <div class="card-body">
                <p>تاريخ الإنشاء: <br> <span class="fw-bold">{{ $sale->created_at->format('Y-m-d H:i') }}</span></p>
                <p>بواسطة: <br> <span class="fw-bold">{{ Auth::guard('subscriber')->user()->name }}</span></p>
                
                <hr>
                
                <div class="alert alert-info py-2">
                    <small><iconify-icon icon="solar:info-circle-bold"></iconify-icon> الفواتير المعتمدة تنقص من رصيد المخزن مباشرة.</small>
                </div>

                @if($sale->status == 'draft')
                    <form action="{{ route('subscriber.app.sales.cancel', $sale) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من إلغاء هذه الفاتورة؟')">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger w-100 mt-4">إلغاء الفاتورة</button>
                    </form>
                @endif
                
                <div class="mt-3">
                    <button class="btn btn-soft-secondary w-100 italic" onclick="window.print()">
                        <iconify-icon icon="solar:printer-bold"></iconify-icon> طباعة الفاتورة
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
