@extends('admin.layouts.admin-layout')

@section('title', 'إدارة الباقات')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">قائمة الباقات</h4>
                @can('create plans')
                    <a href="{{ route('admin.plans.create') }}" class="btn btn-primary btn-sm">إضافة باقة جديدة</a>
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
                                <th>الاسم</th>
                                <th>Slug</th>
                                <th>السعر الشهري</th>
                                <th>السعر السنوي</th>
                                <th>مجانية</th>
                                <th>افتراضية</th>
                                <th>الحالة</th>
                                <th>الترتيب</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($plans as $plan)
                            <tr>
                                <td>{{ $plan->name }}</td>
                                <td><code>{{ $plan->slug }}</code></td>
                                <td>{{ $plan->monthly_price }} {{ $plan->currency }}</td>
                                <td>{{ $plan->yearly_price }} {{ $plan->currency }}</td>
                                <td>
                                    @if($plan->is_free)
                                        <span class="badge bg-success-subtle text-success">نعم</span>
                                    @else
                                        <span class="badge bg-light">لا</span>
                                    @endif
                                </td>
                                <td>
                                    @if($plan->is_default)
                                        <span class="badge bg-primary">نعم</span>
                                    @else
                                        <span class="badge bg-light">لا</span>
                                    @endif
                                </td>
                                <td>
                                    @if($plan->is_active)
                                        <span class="badge bg-success">نشطة</span>
                                    @else
                                        <span class="badge bg-danger">معطلة</span>
                                    @endif
                                </td>
                                <td>{{ $plan->sort_order }}</td>
                                <td>
                                    @can('edit plans')
                                        <a href="{{ route('admin.plans.edit', $plan->id) }}" class="btn btn-sm btn-soft-primary">تعديل</a>
                                    @endcan
                                    @can('view plan features')
                                        <a href="{{ route('admin.plans.features', $plan->id) }}" class="btn btn-sm btn-soft-info">المزايا</a>
                                    @endcan
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $plans->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
