@extends('subscriber.layouts.app')

@section('title', 'فواتير البيع')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="card-title">قائمة فواتير البيع</h4>
        <a href="{{ route('subscriber.app.sales.create') }}" class="btn btn-primary btn-sm">إضافة فاتورة جديدة</a>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>رقم الفاتورة</th>
                        <th>العميل</th>
                        <th>المستودع</th>
                        <th>التاريخ</th>
                        <th>الحالة</th>
                        <th>الإجمالي</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoices as $invoice)
                    <tr>
                        <td>{{ $invoice->invoice_number }}</td>
                        <td>{{ $invoice->customer->name }}</td>
                        <td>{{ $invoice->warehouse->name }}</td>
                        <td>{{ $invoice->invoice_date->format('Y-m-d') }}</td>
                        <td>
                            @if($invoice->status == 'draft')
                                <span class="badge bg-warning">مسودة</span>
                            @elseif($invoice->status == 'posted')
                                <span class="badge bg-success">معتمد</span>
                            @else
                                <span class="badge bg-danger">ملغي</span>
                            @endif
                        </td>
                        <td>{{ number_format($invoice->total_amount, 0) }} د.ع</td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('subscriber.app.sales.show', $invoice) }}" class="btn btn-sm btn-soft-info" title="عرض">
                                    <iconify-icon icon="solar:eye-bold"></iconify-icon>
                                </a>
                                @if($invoice->status == 'draft')
                                    <a href="{{ route('subscriber.app.sales.edit', $invoice) }}" class="btn btn-sm btn-soft-primary" title="تعديل">
                                        <iconify-icon icon="solar:pen-bold"></iconify-icon>
                                    </a>
                                    <form action="{{ route('subscriber.app.sales.post', $invoice) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-soft-success" title="اعتماد">
                                            <iconify-icon icon="solar:check-circle-bold"></iconify-icon>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $invoices->links() }}
        </div>
    </div>
</div>
@endsection
