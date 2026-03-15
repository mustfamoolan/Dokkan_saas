@extends('admin.layouts.admin-layout')

@section('title', 'تفاصيل المشترك')

@section('content')
<div class="row">
    <div class="col-xl-4">
        <!-- Subscriber Basic Card -->
        <div class="card text-center">
            <div class="card-body">
                <div class="avatar-lg bg-light rounded-circle img-thumbnail mb-3 mx-auto d-flex align-items-center justify-content-center">
                    <iconify-icon icon="solar:user-broken" class="fs-32 text-muted"></iconify-icon>
                </div>
                <h4 class="mb-1">{{ $subscriber->name }}</h4>
                <p class="text-muted mb-3">{{ $subscriber->email ?? 'بدون بريد إلكتروني' }}</p>
                
                <div class="d-flex justify-content-center gap-2 mb-3">
                    <span class="badge {{ $subscriber->status == 'active' ? 'bg-success' : 'bg-danger' }} fs-12">
                        {{ $subscriber->status == 'active' ? 'حساب نشط' : 'حساب غير نشط' }}
                    </span>
                    <span class="badge {{ $subscriber->is_active ? 'bg-primary-subtle text-primary' : 'bg-danger-subtle text-danger' }} fs-12">
                        {{ $subscriber->is_active ? 'مفعل' : 'معطل إدارياً' }}
                    </span>
                </div>

                <div class="text-start mt-4">
                    <h5 class="fs-13 text-uppercase text-muted mb-3">المعلومات الشخصية</h5>
                    <p class="mb-2"><strong>رقم الهاتف:</strong> {{ $subscriber->phone }}</p>
                    <p class="mb-2"><strong>آخر دخول:</strong> {{ $subscriber->last_login_at ? $subscriber->last_login_at->diffForHumans() : 'لم يسجل دخول بعد' }}</p>
                    <p class="mb-2"><strong>تاريخ الإنشاء:</strong> {{ $subscriber->created_at->format('Y-m-d H:i') }}</p>
                </div>

                <div class="mt-4">
                    <h5 class="fs-13 text-uppercase text-muted mb-2">ملاحظات إدارية</h5>
                    <div class="bg-light p-2 rounded text-start">
                        {{ $subscriber->notes ?? 'لا توجد ملاحظات' }}
                    </div>
                </div>

                <div class="mt-4 pt-3 border-top">
                    <a href="{{ route('admin.subscribers.edit', $subscriber->id) }}" class="btn btn-primary w-100 mb-2">تعديل البيانات</a>
                    <a href="{{ route('admin.subscribers') }}" class="btn btn-light w-100">العودة للقائمة</a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-8">
        <!-- Store Information -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">بيانات المتجر</h4>
                <span class="badge {{ $subscriber->store->status == 'active' ? 'bg-success' : 'bg-danger' }}">
                    حالة المتجر: {{ $subscriber->store->status == 'active' ? 'نشط' : 'معطل' }}
                </span>
            </div>
            <div class="card-body">
                <div class="row align-items-center mb-4">
                    <div class="col-auto">
                        @if($subscriber->store->logo)
                            <img src="{{ Storage::url($subscriber->store->logo) }}" alt="Logo" class="avatar-md rounded border">
                        @else
                           <div class="avatar-md bg-light d-flex align-items-center justify-content-center rounded">
                               <iconify-icon icon="solar:shop-broken" class="fs-24 text-muted"></iconify-icon>
                           </div>
                        @endif
                    </div>
                    <div class="col">
                        <h4 class="mb-1">{{ $subscriber->store->name }}</h4>
                        <p class="text-muted mb-0">المعرف الفريد: #STR-{{ $subscriber->store->id }}</p>
                    </div>
                    <div class="col-auto">
                        <a href="{{ route('admin.usage.show', $subscriber->store->id) }}" class="btn btn-soft-info btn-sm d-flex align-items-center gap-1">
                            <iconify-icon icon="solar:chart-broken"></iconify-icon>
                            مراقبة الاستهلاك
                        </a>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-4">
                            <h5 class="fs-13 text-muted text-uppercase mb-2">معلومات الاتصال</h5>
                            <p class="mb-1"><strong>الهاتف:</strong> {{ $subscriber->store->phone ?? 'غير متوفر' }}</p>
                            <p class="mb-0"><strong>العنوان:</strong> {{ $subscriber->store->address ?? 'غير متوفر' }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-4">
                            <h5 class="fs-13 text-muted text-uppercase mb-2">الإعدادات الإقليمية</h5>
                            <p class="mb-1"><strong>العملة:</strong> {{ $subscriber->store->currency }}</p>
                            <p class="mb-1"><strong>اللغة:</strong> {{ $subscriber->store->locale == 'ar' ? 'العربية' : 'English' }}</p>
                            <p class="mb-0"><strong>التوقيت:</strong> {{ $subscriber->store->timezone }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Subscriptions List -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">الاشتراكات والمدفوعات</h4>
                <a href="{{ route('admin.subscriptions.create') }}?subscriber_id={{ $subscriber->id }}" class="btn btn-sm btn-primary">إضافة اشتراك جديد</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-centered mb-0">
                        <thead class="table-light text-start">
                            <tr>
                                <th>رقم الاشتراك</th>
                                <th>الباقة</th>
                                <th>الحالة</th>
                                <th>تاريخ الانتهاء</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $subscriptions = \App\Models\Subscription::where('subscriber_id', $subscriber->id)->with('plan')->latest()->get();
                            @endphp
                            
                            @forelse($subscriptions as $sub)
                            <tr>
                                <td>#{{ $sub->id }}</td>
                                <td>{{ $sub->plan->name }}</td>
                                <td>
                                    <span class="badge {{ $sub->status == 'active' ? 'bg-success' : 'bg-warning' }}">
                                        {{ $sub->status }}
                                    </span>
                                </td>
                                <td>{{ $sub->ends_at->format('Y-m-d') }}</td>
                                <td>
                                    <a href="{{ route('admin.subscriptions.show', $sub->id) }}" class="btn btn-link btn-sm p-0">عرض</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-3 text-muted">لا توجد اشتراكات مسجلة</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
