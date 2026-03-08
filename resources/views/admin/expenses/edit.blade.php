@extends('layouts.vertical', ['title' => 'تعديل مصروف'])

@section('content')
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-transparent border-0 shadow-none">
                <div class="card-body p-0">
                    <div class="d-flex align-items-center">
                        <a href="{{ route('admin.expenses.index') }}"
                            class="btn btn-soft-secondary rounded-circle me-3 p-2 d-flex align-items-center justify-content-center">
                            <iconify-icon icon="solar:alt-arrow-right-bold" class="fs-20"></iconify-icon>
                        </a>
                        <div>
                            <h2 class="fw-black mb-1" style="letter-spacing: -0.5px;">تعديل مصروف</h2>
                            <div style="width: 40px; height: 4px; background: #22c55e; border-radius: 2px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm" style="border-radius: 28px;">
                <div class="card-body p-4">
                    <form action="{{ route('admin.expenses.update', $expense) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-4">
                            <div class="col-md-12">
                                <label class="form-label fw-bold">عنوان المصروف <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                                    placeholder="مثلاً: إيجار المحل، رواتب الموظفين..."
                                    value="{{ old('title', $expense->title) }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">المبلغ <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" step="0.01" name="amount"
                                        class="form-control @error('amount') is-invalid @enderror" placeholder="0.00"
                                        value="{{ old('amount', $expense->amount) }}" required>
                                    <span class="input-group-text bg-light">د.ع</span>
                                </div>
                                @error('amount')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">التاريخ <span class="text-danger">*</span></label>
                                <input type="date" name="expense_date"
                                    class="form-control @error('expense_date') is-invalid @enderror"
                                    value="{{ old('expense_date', $expense->expense_date->format('Y-m-d')) }}" required>
                                @error('expense_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <label class="form-label fw-bold">الفئة</label>
                                <input type="text" name="category"
                                    class="form-control @error('category') is-invalid @enderror"
                                    placeholder="مثلاً: رواتب، إيجار، تشغيل..."
                                    value="{{ old('category', $expense->category) }}" list="category-suggestions">
                                <datalist id="category-suggestions">
                                    <option value="رواتب">
                                    <option value="إيجار">
                                    <option value="كهرباء">
                                    <option value="قرطاسية">
                                    <option value="تسويق">
                                    <option value="أخرى">
                                </datalist>
                                @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <label class="form-label fw-bold">ملاحظات إضافية</label>
                                <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="4"
                                    placeholder="تفاصيل إضافية عن المصروف...">{{ old('notes', $expense->notes) }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 mt-5">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('admin.expenses.index') }}"
                                        class="btn btn-light rounded-pill px-4">إلغاء</a>
                                    <button type="submit" class="btn btn-success rounded-pill px-5">تحديث المصروف</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection