@extends('admin.layouts.admin-layout')

@section('title', 'قائمة الاشتراكات')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">إدارة الاشتراكات</h4>
                @can('create subscriptions')
                    <a href="{{ route('admin.subscriptions.create') }}" class="btn btn-primary btn-sm">إضافة اشتراك يدوي</a>
                @endcan
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-centered table-nowrap mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>المشترك</th>
                                <th>المتجر</th>
                                <th>الباقة</th>
                                <th>الدورة</th>
                                <th>الحالة</th>
                                <th>تجريبي</th>
                                <th>البداية</th>
                                <th>النهاية</th>
                                <th>تجديد تلقائي</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($subscriptions as $subscription)
                            <tr>
                                <td>{{ $subscription->subscriber->name }}</td>
                                <td>{{ $subscription->store->name }}</td>
                                <td>
                                    <span class="badge bg-primary-subtle text-primary">{{ $subscription->plan->name }}</span>
                                </td>
                                <td>
                                    @if($subscription->billing_cycle == 'monthly') شهري
                                    @elseif($subscription->billing_cycle == 'yearly') سنوي
                                    @elseif($subscription->billing_cycle == 'trial') تجريبي
                                    @else مخصص
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $statusClasses = [
                                            'active' => 'bg-success',
                                            'pending' => 'bg-warning',
                                            'trial' => 'bg-info',
                                            'expired' => 'bg-danger',
                                            'suspended' => 'bg-secondary',
                                            'cancelled' => 'bg-dark',
                                        ];
                                        $statusLabels = [
                                            'active' => 'نشط',
                                            'pending' => 'قيد الانتظار',
                                            'trial' => 'تجريبي',
                                            'expired' => 'منتهي',
                                            'suspended' => 'موقف',
                                            'cancelled' => 'ملغي',
                                        ];
                                    @endphp
                                    <span class="badge {{ $statusClasses[$subscription->status] ?? 'bg-light' }}">
                                        {{ $statusLabels[$subscription->status] ?? $subscription->status }}
                                    </span>
                                </td>
                                <td>
                                    @if($subscription->is_trial)
                                        <span class="text-info"><iconify-icon icon="solar:check-read-broken"></iconify-icon> نعم</span>
                                    @else
                                        <span class="text-muted">لا</span>
                                    @endif
                                </td>
                                <td>{{ $subscription->starts_at->format('Y-m-d') }}</td>
                                <td>{{ $subscription->ends_at->format('Y-m-d') }}</td>
                                <td>
                                    <span class="badge {{ $subscription->auto_renew ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }}">
                                        {{ $subscription->auto_renew ? 'مفعل' : 'معطل' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <iconify-icon icon="solar:menu-dots-bold"></iconify-icon>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            @can('view subscriptions')
                                                <li><a class="dropdown-item" href="{{ route('admin.subscriptions.show', $subscription->id) }}">عرض</a></li>
                                            @endcan
                                            @can('edit subscriptions')
                                                <li><a class="dropdown-item" href="{{ route('admin.subscriptions.edit', $subscription->id) }}">تعديل</a></li>
                                            @endcan
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $subscriptions->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
