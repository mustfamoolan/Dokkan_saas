@extends('subscriber.layouts.app')

@section('title', 'إدارة الموردين')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="card-title">قائمة الموردين</h4>
        <a href="{{ route('subscriber.app.suppliers.create') }}" class="btn btn-primary btn-sm">إضافة مورد جديد</a>
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
                        <th>الاسم</th>
                        <th>الهاتف</th>
                        <th>البريد الإلكتروني</th>
                        <th>الرصيد الافتتاحي</th>
                        <th>الحالة</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($suppliers as $supplier)
                    <tr>
                        <td>{{ $supplier->name }}</td>
                        <td>{{ $supplier->phone }}</td>
                        <td>{{ $supplier->email ?? '-' }}</td>
                        <td>
                            @if($supplier->opening_balance_type === 'none')
                                <span class="text-muted">-</span>
                            @else
                                <span class="{{ $supplier->opening_balance_type === 'debit' ? 'text-danger' : 'text-success' }}">
                                    {{ number_format($supplier->opening_balance, 0) }} د.ع
                                    <small>({{ $supplier->opening_balance_type === 'debit' ? 'مدين/مطلوب منه' : 'دائن/يطلب مبالغ' }})</small>
                                </span>
                            @endif
                        </td>
                        <td>
                            <span class="badge {{ $supplier->is_active ? 'bg-success' : 'bg-danger' }}">
                                {{ $supplier->is_active ? 'نشط' : 'معطل' }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('subscriber.app.suppliers.show', $supplier) }}" class="btn btn-sm btn-soft-info" title="عرض">
                                    <iconify-icon icon="solar:eye-bold"></iconify-icon>
                                </a>
                                <a href="{{ route('subscriber.app.suppliers.edit', $supplier) }}" class="btn btn-sm btn-soft-primary" title="تعديل">
                                    <iconify-icon icon="solar:pen-bold"></iconify-icon>
                                </a>
                                <form action="{{ route('subscriber.app.suppliers.destroy', $supplier) }}" method="POST" onsubmit="return confirm('هل أنت متأكد؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-soft-danger" title="حذف">
                                        <iconify-icon icon="solar:trash-bin-trash-bold"></iconify-icon>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $suppliers->links() }}
        </div>
    </div>
</div>
@endsection
