@extends('subscriber.layouts.app')

@section('title', 'عرض فاتورة شراء')

@section('content')
<div class="row">
    <div class="col-md-9">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">بيانات الفاتورة: {{ $purchase->invoice_number }}</h4>
                <div class="d-flex gap-2">
                    @if($purchase->status == 'draft')
                        <a href="{{ route('subscriber.app.purchases.edit', $purchase) }}" class="btn btn-soft-primary btn-sm">تعديل</a>
                    @endif
                    <div class="d-flex align-items-center gap-2">
                        <a href="{{ route('subscriber.app.print.purchase-invoice', $purchase) }}" target="_blank" class="btn btn-soft-secondary">
                            <iconify-icon icon="solar:printer-bold" class="me-1"></iconify-icon> طباعة
                        </a>
                        @if($purchase->status == 'draft')
                            <form action="{{ route('subscriber.app.purchases.post', $purchase) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success">
                                    <iconify-icon icon="solar:check-read-bold" class="me-1"></iconify-icon> اعتماد الفاتورة
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-sm-4">
                        <h6 class="text-muted mb-1">المورد:</h6>
                        <h5>{{ $purchase->supplier->name }}</h5>
                        <p class="mb-0">{{ $purchase->supplier->phone }}</p>
                    </div>
                    <div class="col-sm-4 text-center">
                        <h6 class="text-muted mb-1">المستودع:</h6>
                        <h5>{{ $purchase->warehouse->name }}</h5>
                    </div>
                    <div class="col-sm-4 text-end">
                        <h6 class="text-muted mb-1">تاريخ الفاتورة:</h6>
                        <h5>{{ $purchase->invoice_date->format('Y-m-d') }}</h5>
                        <p class="mb-0">الحالة: 
                            @if($purchase->status == 'draft')
                                <span class="text-warning fw-bold">مسودة</span>
                            @elseif($purchase->status == 'posted')
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
                                <th class="text-end">سعر التكلفة</th>
                                <th class="text-end">الإجمالي</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($purchase->items as $item)
                            <tr>
                                <td>{{ $item->product->name }}</td>
                                <td class="text-center">{{ number_format($item->quantity, 2) }}</td>
                                <td class="text-end">{{ number_format($item->unit_cost, 0) }} د.ع</td>
                                <td class="text-end fw-bold">{{ number_format($item->line_total, 0) }} د.ع</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="h5">
                            <tr>
                                <th colspan="3" class="text-end">الإجمالي الفرعي:</th>
                                <th class="text-end">{{ number_format($purchase->subtotal, 0) }} د.ع</th>
                            </tr>
                            @if($purchase->discount_amount > 0)
                            <tr>
                                <th colspan="3" class="text-end text-danger">الخصم:</th>
                                <th class="text-end text-danger">-{{ number_format($purchase->discount_amount, 0) }} د.ع</th>
                            </tr>
                            @endif
                            <tr class="table-primary">
                                <th colspan="3" class="text-end">الصافي النهائي:</th>
                                <th class="text-end text-primary">{{ number_format($purchase->total_amount, 0) }} د.ع</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                @if($purchase->notes)
                <div class="mt-4">
                    <h6 class="text-muted">ملاحظات:</h6>
                    <div class="bg-light p-3 rounded">{{ $purchase->notes }}</div>
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
                <p>تاريخ الإنشاء: <br> <span class="fw-bold">{{ $purchase->created_at->format('Y-m-d H:i') }}</span></p>
                <p>بواسطة: <br> <span class="fw-bold">{{ Auth::guard('subscriber')->user()->name }}</span></p>
                
                <hr>
                
                <div class="alert alert-info py-2">
                    <small><iconify-icon icon="solar:info-circle-bold"></iconify-icon> الفواتير المعتمدة تزيد من رصيد المخزن مباشرة.</small>
                </div>

                @if($purchase->status == 'draft')
                    <form action="{{ route('subscriber.app.purchases.cancel', $purchase) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من إلغاء هذه الفاتورة؟')">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger w-100 mt-4">إلغاء الفاتورة</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
