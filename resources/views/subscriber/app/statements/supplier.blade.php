@extends('subscriber.layouts.app')

@section('title', 'كشف حساب مورد: ' . $supplier->name)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 d-print-none">
    <h4 class="mb-0">كشف حساب مورد</h4>
    <div>
        <a href="{{ route('subscriber.app.print.supplier-statement', $supplier) }}" target="_blank" class="btn btn-soft-secondary me-2">
            <iconify-icon icon="solar:printer-bold" class="me-1"></iconify-icon> طباعة الكشف
        </a>
        <a href="{{ route('subscriber.app.suppliers.index') }}" class="btn btn-light">
            <iconify-icon icon="solar:arrow-right-linear" class="me-1"></iconify-icon> عودة للموردين
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="d-flex align-items-center mb-2">
                    <div class="bg-soft-info text-info p-3 rounded-circle me-3">
                        <iconify-icon icon="solar:shop-bold" class="h3 mb-0"></iconify-icon>
                    </div>
                    <div>
                        <h5 class="mb-1">{{ $supplier->name }}</h5>
                        <div class="text-muted small">{{ $supplier->phone }} | {{ $supplier->address }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 text-md-end mt-3 mt-md-0">
                <div class="bg-light p-3 rounded d-inline-block text-start" style="min-width: 250px;">
                    <div class="text-muted small mb-1">الرصيد المستحق (له بذمتنا)</div>
                    <div class="h3 fw-bold text-{{ $balanceInfo['color'] }} mb-0">
                        {{ number_format($balanceInfo['amount'], 2) }} <small>د.ع</small>
                    </div>
                    <div class="badge bg-soft-{{ $balanceInfo['color'] }} text-{{ $balanceInfo['color'] }} mt-2">
                        {{ $balanceInfo['label'] }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm overflow-hidden">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr class="small text-muted">
                        <th class="ps-4">التاريخ</th>
                        <th>النوع</th>
                        <th>المرجع</th>
                        <th>البيان</th>
                        <th class="text-danger">مدين (سداد)</th>
                        <th class="text-success">دائن (شراء)</th>
                        <th class="pe-4 text-end">الرصيد الجاري</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transactions as $trx)
                    <tr>
                        <td class="ps-4 small">{{ $trx['date'] instanceof \Illuminate\Support\Carbon ? $trx['date']->format('Y-m-d') : $trx['date'] }}</td>
                        <td>
                            <span class="badge bg-soft-secondary text-secondary small">{{ $trx['type'] }}</span>
                        </td>
                        <td class="small fw-bold">{{ $trx['reference'] }}</td>
                        <td class="small">{{ $trx['description'] }}</td>
                        <td class="text-danger fw-bold">
                            {{ $trx['debit'] > 0 ? number_format($trx['debit'], 2) : '-' }}
                        </td>
                        <td class="text-success fw-bold">
                            {{ $trx['credit'] > 0 ? number_format($trx['credit'], 2) : '-' }}
                        </td>
                        <td class="pe-4 text-end fw-bold">
                            <span class="{{ $trx['balance'] >= 0 ? 'text-success' : 'text-danger' }}">
                                {{ number_format(abs($trx['balance']), 2) }}
                                <small>{{ $trx['balance'] >= 0 ? 'له' : 'عليه' }}</small>
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-light fw-bold">
                    <tr>
                        <td colspan="4" class="ps-4 text-end">الإجمالي</td>
                        <td class="text-danger">{{ number_format($transactions->sum('debit'), 2) }}</td>
                        <td class="text-success">{{ number_format($transactions->sum('credit'), 2) }}</td>
                        <td class="pe-4 text-end text-{{ $balanceInfo['color'] }}">
                            {{ number_format($balanceInfo['amount'], 2) }}
                            <small>{{ $balanceInfo['type'] === 'credit' ? 'له' : 'عليه' }}</small>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection
