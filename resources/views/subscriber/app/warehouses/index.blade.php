@extends('subscriber.layouts.app')

@section('title', 'إدارة المستودعات')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="card-title">قائمة المستودعات</h4>
        <a href="{{ route('subscriber.app.warehouses.create') }}" class="btn btn-primary btn-sm">إضافة مستودع جديد</a>
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
                        <th>اسم المستودع</th>
                        <th>الكود</th>
                        <th>الحالة</th>
                        <th>افتراضي</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($warehouses as $warehouse)
                    <tr>
                        <td>{{ $warehouse->name }}</td>
                        <td>{{ $warehouse->code ?? '-' }}</td>
                        <td>
                            <span class="badge {{ $warehouse->is_active ? 'bg-success' : 'bg-danger' }}">
                                {{ $warehouse->is_active ? 'نشط' : 'معطل' }}
                            </span>
                        </td>
                        <td>
                            @if($warehouse->is_default)
                                <span class="badge bg-info">نعم (الافتراضي)</span>
                            @else
                                <span class="text-muted">لا</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('subscriber.app.warehouses.stock', $warehouse) }}" class="btn btn-sm btn-soft-success" title="المخزون">
                                    <iconify-icon icon="solar:box-bold"></iconify-icon>
                                </a>
                                <a href="{{ route('subscriber.app.warehouses.show', $warehouse) }}" class="btn btn-sm btn-soft-info" title="عرض">
                                    <iconify-icon icon="solar:eye-bold"></iconify-icon>
                                </a>
                                <a href="{{ route('subscriber.app.warehouses.edit', $warehouse) }}" class="btn btn-sm btn-soft-primary" title="تعديل">
                                    <iconify-icon icon="solar:pen-bold"></iconify-icon>
                                </a>
                                @if(!$warehouse->is_default)
                                <form action="{{ route('subscriber.app.warehouses.destroy', $warehouse) }}" method="POST" onsubmit="return confirm('هل أنت متأكد؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-soft-danger" title="حذف">
                                        <iconify-icon icon="solar:trash-bin-trash-bold"></iconify-icon>
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
            {{ $warehouses->links() }}
        </div>
    </div>
</div>
@endsection
