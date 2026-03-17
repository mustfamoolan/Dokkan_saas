@extends('layouts.representative')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">أهلاً بك، {{ $representative->name }}</h1>
    <p class="text-gray-600 text-sm mt-1">هنا تجد ملخصاً مبسطاً لأدائك ومهامك اليومية.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <!-- Stat Box: Orders -->
    <div class="bg-white rounded-lg shadow p-6 border-t-4 border-primary">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-100 text-primary">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
            </div>
            <div class="mr-4">
                <p class="text-gray-500 text-sm font-medium">إجمالي الطلبات</p>
                <p class="text-2xl font-bold text-gray-900">{{ $ordersCount }}</p>
            </div>
        </div>
        <div class="mt-4">
            <a href="{{ route('rep.orders.index') }}" class="text-sm text-primary hover:underline">عرض كل الطلبات &larr;</a>
        </div>
    </div>

    <!-- Stat Box: Customers -->
    <div class="bg-white rounded-lg shadow p-6 border-t-4 border-secondary">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-100 text-secondary">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
            <div class="mr-4">
                <p class="text-gray-500 text-sm font-medium">العملاء المرتبطين</p>
                <p class="text-2xl font-bold text-gray-900">{{ $customersCount }}</p>
            </div>
        </div>
        <div class="mt-4">
            <a href="{{ route('rep.customers.index') }}" class="text-sm text-secondary hover:underline">عرض كل العملاء &larr;</a>
        </div>
    </div>

    <!-- Stat Box: Financials Placeholder -->
    <div class="bg-white rounded-lg shadow p-6 border-t-4 border-yellow-400">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div class="mr-4">
                <p class="text-gray-500 text-sm font-medium">العمولات المستحقة</p>
                <p class="text-2xl font-bold text-gray-900">--</p>
            </div>
        </div>
        <div class="mt-4">
            <a href="{{ route('rep.financials.index') }}" class="text-sm text-yellow-600 hover:underline">عرض التفاصيل &larr;</a>
        </div>
    </div>
</div>

<div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-lg font-bold mb-4">تعليمات وإشعارات</h2>
    <div class="p-4 bg-gray-50 border rounded-md text-gray-700">
        مرحباً بك في لوحة تحكم المندوبين الجديدة. من هنا يمكنك متابعة الطلبات المرتبطة بك وإدارة عملائك والاطلاع على مستحقاتك المالية لاحقاً.
    </div>
</div>
@endsection
