@extends('subscriber.layouts.app')

@section('title', 'المصاريف')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">المصاريف</h4>
    <a href="{{ route('subscriber.app.expenses.create') }}" class="btn btn-danger">
        <iconify-icon icon="solar:bill-list-bold" class="me-1"></iconify-icon> تسجيل مصروف جديد
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">التاريخ</th>
                        <th>البند / الفئة</th>
                        <th>الصندوق</th>
                        <th>المبلغ</th>
                        <th class="pe-4">ملاحظات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($expenses as $expense)
                    <tr>
                        <td class="ps-4 small">{{ $expense->expense_date->format('Y-m-d') }}</td>
                        <td>
                            <span class="fw-bold">{{ $expense->category }}</span>
                        </td>
                        <td>
                             <iconify-icon icon="solar:wallet-linear" class="text-muted me-1"></iconify-icon>
                             {{ $expense->cashbox->name }}
                        </td>
                        <td class="text-danger fw-bold">
                            {{ number_format($expense->amount, 2) }}
                        </td>
                        <td class="pe-4 small text-muted">
                            {{ $expense->notes ?? '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">لا توجد مصاريف مسجلة حالياً</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($expenses->hasPages())
    <div class="card-footer bg-white border-0">
        {{ $expenses->links() }}
    </div>
    @endif
</div>
@endsection
