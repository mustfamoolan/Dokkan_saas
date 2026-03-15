@extends('admin.layouts.admin-layout')

@section('title', 'قائمة المشتركين')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">إدارة المشتركين والمتاجر</h4>
                @can('create subscribers')
                    <a href="{{ route('admin.subscribers.create') }}" class="btn btn-primary btn-sm">إضافة مشترك جديد</a>
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
                                <th>اسم المشترك</th>
                                <th>الهاتف</th>
                                <th>المتجر</th>
                                <th>حالة المشترك</th>
                                <th>حالة التفعيل</th>
                                <th>تاريخ الانضمام</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($subscribers as $subscriber)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1">
                                            <h5 class="fs-14 mb-1">{{ $subscriber->name }}</h5>
                                            <p class="text-muted mb-0 fs-12">{{ $subscriber->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $subscriber->phone }}</td>
                                <td>
                                    @if($subscriber->store)
                                        <h5 class="fs-13 mb-1">{{ $subscriber->store->name }}</h5>
                                        <span class="badge {{ $subscriber->store->status == 'active' ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }}">
                                            {{ $subscriber->store->status == 'active' ? 'نشط' : 'معطل' }}
                                        </span>
                                    @else
                                        <span class="text-danger">لا يوجد متجر</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge {{ $subscriber->status == 'active' ? 'bg-success' : ($subscriber->status == 'pending' ? 'bg-warning' : 'bg-danger') }}">
                                        {{ $subscriber->status == 'active' ? 'نشط' : ($subscriber->status == 'pending' ? 'قيد الانتظار' : 'موقف') }}
                                    </span>
                                </td>
                                <td>
                                    @if($subscriber->is_active)
                                        <span class="badge bg-success-subtle text-success">مفعل</span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger">معطل</span>
                                    @endif
                                </td>
                                <td>{{ $subscriber->created_at->format('Y-m-d') }}</td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <iconify-icon icon="solar:menu-dots-bold"></iconify-icon>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            @can('view subscribers')
                                                <li><a class="dropdown-item" href="{{ route('admin.subscribers.show', $subscriber->id) }}">عرض التفاصيل</a></li>
                                            @endcan
                                            @can('edit subscribers')
                                                <li><a class="dropdown-item" href="{{ route('admin.subscribers.edit', $subscriber->id) }}">تعديل البيانات</a></li>
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
                    {{ $subscribers->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
