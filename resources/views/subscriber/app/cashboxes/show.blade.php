@extends('subscriber.layouts.app')

@section('title', 'حركات الصندوق: ' . $cashbox->name)

@section('content')
<div class="row g-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body text-center py-4">
                <div class="bg-soft-primary p-3 rounded-circle d-inline-block mb-3">
                    <iconify-icon icon="solar:wallet-money-bold" class="text-primary h1 mb-0"></iconify-icon>
                </div>
                <h5 class="text-muted mb-1">{{ $cashbox->name }}</h5>
                <h2 class="fw-bold mb-0 text-primary">{{ number_format($cashbox->current_balance, 2) }}</h2>
                <small class="text-muted">الرصيد الحالي (د.ع)</small>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">تسوية الرصيد يدويًا</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('subscriber.app.cashboxes.adjust', $cashbox) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small text-muted">الرصيد الجديد</label>
                        <input type="number" step="0.01" name="new_balance" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small text-muted">السبب / ملاحظات</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="مثال: تصحيح خطأ عد، جرد أسبوعي..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-soft-warning w-100" onclick="return confirm('هل أنت متأكد من تسوية الرصيد يدوياً؟ سيتم تسجيل حركة تصحيحية.')">
                        تحديث وتسوية الرصيد
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0">سجل حركات الصندوق</h5>
                <span class="badge bg-light text-dark">{{ $transactions->total() }} حركة</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr class="small text-muted">
                                <th class="ps-4">التاريخ</th>
                                <th>النوع</th>
                                <th>المبلغ</th>
                                <th>البيان</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $trx)
                            <tr>
                                <td class="ps-4 small">{{ $trx->created_at->format('Y-m-d H:i') }}</td>
                                <td>
                                    @php
                                        $badges = [
                                            'income' => ['bg-success', 'دخل'],
                                            'expense' => ['bg-danger', 'مصروف'],
                                            'sale' => ['bg-info', 'بيع'],
                                            'purchase' => ['bg-warning', 'شراء'],
                                            'adjustment' => ['bg-secondary', 'تسوية'],
                                        ];
                                        $badge = $badges[$trx->type] ?? ['bg-light', $trx->type];
                                    @endphp
                                    <span class="badge {{ $badge[0] }}">{{ $badge[1] }}</span>
                                </td>
                                <td class="fw-bold {{ $trx->direction == 'in' ? 'text-success' : 'text-danger' }}">
                                    {{ $trx->direction == 'in' ? '+' : '-' }}
                                    {{ number_format($trx->amount, 2) }}
                                </td>
                                <td class="small text-muted">
                                    {{ $trx->notes ?? '-' }}
                                    @if($trx->reference_type)
                                        <div class="text-xs text-info mt-1">
                                            <iconify-icon icon="solar:link-bold"></iconify-icon>
                                            مرتبط بـ #{{ $trx->reference_id }}
                                        </div>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted small">لا توجد حركات مسجلة لهذا الصندوق</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($transactions->hasPages())
            <div class="card-footer bg-white border-0">
                {{ $transactions->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
