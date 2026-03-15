@extends('subscriber.layouts.app')

@section('title', 'إضافة مورد جديد')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">بيانات المورد الجديد</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('subscriber.app.suppliers.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">اسم المورد</label>
                            <input type="text" name="name" class="form-control" required value="{{ old('name') }}">
                            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">رقم الهاتف الأساسي</label>
                            <input type="text" name="phone" class="form-control" required value="{{ old('phone') }}">
                            @error('phone') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">رقم هاتف إضافي</label>
                            <input type="text" name="alternate_phone" class="form-control" value="{{ old('alternate_phone') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">البريد الإلكتروني</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                            @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">العنوان</label>
                            <input type="text" name="address" class="form-control" value="{{ old('address') }}">
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label class="form-label">الرصيد الافتتاحي</label>
                            <input type="number" step="0.01" name="opening_balance" class="form-control" required value="{{ old('opening_balance', 0) }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">نوع الرصيد</label>
                            <select name="opening_balance_type" class="form-select">
                                <option value="none" {{ old('opening_balance_type') == 'none' ? 'selected' : '' }}>بلا رصيد</option>
                                <option value="debit" {{ old('opening_balance_type') == 'debit' ? 'selected' : '' }}>مدين (نطلبه مبالغ)</option>
                                <option value="credit" {{ old('opening_balance_type') == 'credit' ? 'selected' : '' }}>دائن (يطلبنا مبالغ)</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">الحالة</label>
                            <select name="is_active" class="form-select">
                                <option value="1">نشط</option>
                                <option value="0">معطل</option>
                            </select>
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label">ملاحظات</label>
                            <textarea name="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary px-4">حفظ المورد</button>
                        <a href="{{ route('subscriber.app.suppliers.index') }}" class="btn btn-light">إلغاء</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
