@extends('layouts.vertical', ['title' => 'إدارة المصروفات'])

@section('content')
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-transparent border-0 shadow-none">
                <div class="card-body p-0">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h2 class="fw-black mb-1" style="letter-spacing: -0.5px;">إدارة المصروفات</h2>
                            <div style="width: 40px; height: 4px; background: #4e73df; border-radius: 2px;"></div>
                        </div>
                        @if(auth()->user()->isAdmin() || auth()->user()->can('expenses.create'))
                            <a href="{{ route('admin.expenses.create') }}" class="btn btn-primary rounded-pill px-4">
                                <iconify-icon icon="solar:add-circle-bold" class="me-1"></iconify-icon>
                                إضافة مصروف جديد
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="border-radius: 20px;">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.expenses.index') }}" class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">البحث</label>
                            <input type="text" name="search" class="form-control" placeholder="ابحث عن عنوان المصروف..."
                                value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-bold">الفئة</label>
                            <select name="category" class="form-select">
                                <option value="">جميع الفئات</option>
                                @foreach($expenseCategories as $cat)
                                    <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>
                                        {{ $cat }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-bold">من تاريخ</label>
                            <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-bold">إلى تاريخ</label>
                            <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <iconify-icon icon="solar:filter-bold-duotone" class="me-1"></iconify-icon>
                                تصفية
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Expenses Table -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="border-radius: 20px;">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">التاريخ</th>
                                    <th>العنوان</th>
                                    <th>الفئة</th>
                                    <th>المبلغ</th>
                                    <th>بواسطة</th>
                                    <th class="text-end pe-4">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($expenses as $expense)
                                    <tr>
                                        <td class="ps-4">{{ $expense->expense_date->format('Y-m-d') }}</td>
                                        <td>
                                            <div class="fw-bold">{{ $expense->title }}</div>
                                            @if($expense->notes)
                                                <small class="text-muted text-truncate d-inline-block"
                                                    style="max-width: 200px;">{{ $expense->notes }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-soft-info text-info rounded-pill px-3">
                                                {{ $expense->category ?? 'عام' }}
                                            </span>
                                        </td>
                                        <td class="fw-bold text-danger">{{ format_currency($expense->amount) }}</td>
                                        <td>{{ $expense->creator->name ?? 'غير معروف' }}</td>
                                        <td class="text-end pe-4">
                                            <div class="d-flex justify-content-end gap-2">
                                                @if(auth()->user()->isAdmin() || auth()->user()->can('expenses.edit'))
                                                    <a href="{{ route('admin.expenses.edit', $expense) }}"
                                                        class="btn btn-sm btn-soft-primary">
                                                        <iconify-icon icon="solar:pen-bold-duotone"></iconify-icon>
                                                    </a>
                                                @endif

                                                @if(auth()->user()->isAdmin() || auth()->user()->can('expenses.delete'))
                                                    <form action="{{ route('admin.expenses.destroy', $expense) }}" method="POST"
                                                        class="d-inline"
                                                        onsubmit="return confirm('هل أنت متأكد من حذف هذا المصروف؟')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-soft-danger">
                                                            <iconify-icon icon="solar:trash-bin-trash-bold-duotone"></iconify-icon>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5">
                                            <iconify-icon icon="solar:bill-list-broken"
                                                class="fs-48 text-muted mb-2"></iconify-icon>
                                            <p class="text-muted mb-0">لا توجد مصروفات مسجلة</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $expenses->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection