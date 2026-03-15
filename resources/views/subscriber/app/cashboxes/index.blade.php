@extends('subscriber.layouts.app')

@section('title', 'الصناديق المالية')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">الصناديق المالية</h4>
    <a href="{{ route('subscriber.app.cashboxes.create') }}" class="btn btn-primary">
        <iconify-icon icon="solar:add-circle-bold" class="me-1"></iconify-icon> إضافة صندوق جديد
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">اسم الصندوق</th>
                        <th>الرصيد الحالي</th>
                        <th>الحالة</th>
                        <th class="text-end pe-4">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cashboxes as $cashbox)
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                <div class="bg-soft-primary p-2 rounded me-3">
                                    <iconify-icon icon="solar:wallet-2-bold" class="text-primary h4 mb-0"></iconify-icon>
                                </div>
                                <span class="fw-bold">{{ $cashbox->name }}</span>
                            </div>
                        </td>
                        <td>
                            <span class="h5 mb-0 fw-bold">{{ number_format($cashbox->current_balance, 2) }}</span>
                            <small class="text-muted ms-1">د.ع</small>
                        </td>
                        <td>
                            @if($cashbox->is_active)
                                <span class="badge bg-soft-success text-success px-3">نشط</span>
                            @else
                                <span class="badge bg-soft-danger text-danger px-3">معطل</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <a href="{{ route('subscriber.app.cashboxes.show', $cashbox) }}" class="btn btn-sm btn-soft-info" title="عرض الحركات">
                                <iconify-icon icon="solar:history-bold"></iconify-icon>
                            </a>
                            <a href="{{ route('subscriber.app.cashboxes.edit', $cashbox) }}" class="btn btn-sm btn-soft-primary" title="تعديل">
                                <iconify-icon icon="solar:pen-bold"></iconify-icon>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-5 text-muted">لا توجد صناديق مالية حالياً</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
