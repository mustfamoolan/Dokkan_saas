@extends('admin.layouts.admin-layout')

@section('title', 'قائمة المدفوعات')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">إدارة المدفوعات</h4>
                @can('create payments')
                    <a href="{{ route('admin.payments.create') }}" class="btn btn-primary btn-sm">تسجيل دفع يدوي</a>
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
                                <th>الاشتراك</th>
                                <th>المبلغ</th>
                                <th>الحالة</th>
                                <th>تاريخ الدفع</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($payments as $payment)
                            <tr>
                                <td>{{ $payment->subscriber->name }}</td>
                                <td>{{ $payment->store->name }}</td>
                                <td>
                                    <span class="badge bg-primary-subtle text-primary">#{{ $payment->subscription_id }}</span>
                                </td>
                                <td>
                                    <strong>{{ number_format($payment->amount, 2) }} {{ $payment->currency }}</strong>
                                </td>
                                <td>
                                    @php
                                        $statusClasses = [
                                            'pending' => 'bg-warning',
                                            'approved' => 'bg-success',
                                            'rejected' => 'bg-danger',
                                        ];
                                        $statusLabels = [
                                            'pending' => 'قيد المراجعة',
                                            'approved' => 'مقبول',
                                            'rejected' => 'مرفوض',
                                        ];
                                    @endphp
                                    <span class="badge {{ $statusClasses[$payment->status] ?? 'bg-light' }}">
                                        {{ $statusLabels[$payment->status] ?? $payment->status }}
                                    </span>
                                </td>
                                <td>{{ $payment->created_at->format('Y-m-d H:i') }}</td>
                                <td>
                                    @can('view payments')
                                        <a href="{{ route('admin.payments.show', $payment->id) }}" class="btn btn-soft-primary btn-sm">عرض التفاصيل</a>
                                    @endcan
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $payments->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
