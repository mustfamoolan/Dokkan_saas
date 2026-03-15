@extends('subscriber.layouts.app')

@section('title', 'إضافة صندوق جديد')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">إضافة صندوق جديد</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('subscriber.app.cashboxes.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label">اسم الصندوق <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">الرصيد الافتتاحي</label>
                        <div class="input-group">
                            <input type="number" step="0.01" name="current_balance" class="form-control @error('current_balance') is-invalid @enderror" value="{{ old('current_balance', 0) }}">
                            <span class="input-group-text">د.ع</span>
                        </div>
                        @error('current_balance')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" id="isActive" value="1" checked>
                            <label class="form-check-label" for="isActive">صندوق نشط</label>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4">حفظ الصندوق</button>
                        <a href="{{ route('subscriber.app.cashboxes.index') }}" class="btn btn-light px-4">إلغاء</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
