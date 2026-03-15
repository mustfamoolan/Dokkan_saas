@extends('subscriber.layouts.app')

@section('title', 'تسجيل مصروف')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">تسجيل مصروف جديد</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('subscriber.app.expenses.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label">الصندوق المالي <span class="text-danger">*</span></label>
                        <select name="cashbox_id" class="form-select @error('cashbox_id') is-invalid @enderror" required>
                            <option value="">اختر الصندوق...</option>
                            @foreach($cashboxes as $cashbox)
                                <option value="{{ $cashbox->id }}" {{ old('cashbox_id') == $cashbox->id ? 'selected' : '' }}>
                                    {{ $cashbox->name }} (الرصيد: {{ number_format($cashbox->current_balance, 2) }})
                                </option>
                            @endforeach
                        </select>
                        @error('cashbox_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">بند المصروف / الفئة <span class="text-danger">*</span></label>
                        <input type="text" name="category" class="form-control @error('category') is-invalid @enderror" value="{{ old('category') }}" placeholder="مثال: إيجار، رواتب، كهرباء..." required>
                        @error('category')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">المبلغ <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" step="0.01" name="amount" class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount') }}" required>
                                <span class="input-group-text">د.ع</span>
                            </div>
                            @error('amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">التاريخ <span class="text-danger">*</span></label>
                            <input type="date" name="expense_date" class="form-control @error('expense_date') is-invalid @enderror" value="{{ old('expense_date', date('Y-m-d')) }}" required>
                            @error('expense_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">ملاحظات إضافية</label>
                        <textarea name="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-danger px-4">تأكيد وتسجيل المصرف</button>
                        <a href="{{ route('subscriber.app.expenses.index') }}" class="btn btn-light px-4">إلغاء</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
