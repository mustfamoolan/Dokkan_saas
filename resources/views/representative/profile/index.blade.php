@extends('layouts.representative')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">الملف الشخصي</h1>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Profile Info -->
    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">المعلومات الأساسية</h2>
        
        <div class="space-y-4">
            <div>
                <span class="block text-sm font-medium text-gray-500">اسم المندوب</span>
                <span class="block mt-1 text-md text-gray-900">{{ $representative->name }}</span>
            </div>
            
            <div>
                <span class="block text-sm font-medium text-gray-500">رقم الهاتف</span>
                <span class="block mt-1 text-md text-gray-900" dir="ltr">{{ $representative->phone }}</span>
            </div>

            <div>
                <span class="block text-sm font-medium text-gray-500">البريد الإلكتروني</span>
                <span class="block mt-1 text-md text-gray-900">{{ $representative->email ?? '--' }}</span>
            </div>

            <div>
                <span class="block text-sm font-medium text-gray-500">الحالة</span>
                @if($representative->is_active)
                    <span class="inline-flex mt-1 items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">نشط</span>
                @else
                    <span class="inline-flex mt-1 items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">موقوف</span>
                @endif
            </div>

            <div>
                <span class="block text-sm font-medium text-gray-500">العمولة ({{ $representative->commission_type == 'percentage' ? 'نسبة' : 'مبلغ ثابت' }})</span>
                <span class="block mt-1 text-md font-bold text-primary">
                    {{ number_format($representative->commission_value ?? 0, 2) }}
                    {{ $representative->commission_type == 'percentage' ? '%' : 'د.ك' }}
                </span>
            </div>
        </div>
    </div>

    <!-- Update Password -->
    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">تحديث كلمة المرور</h2>
        
        <form action="{{ route('rep.profile.password') }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-4">
                <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">كلمة المرور الحالية</label>
                <input type="password" name="current_password" id="current_password" required
                    class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary">
                @error('current_password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">كلمة المرور الجديدة</label>
                <input type="password" name="password" id="password" required
                    class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary">
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">تأكيد كلمة المرور الجديدة</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required
                    class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary">
            </div>

            <button type="submit" class="w-full bg-primary text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                حفظ التغييرات
            </button>
        </form>
    </div>
</div>
@endsection
