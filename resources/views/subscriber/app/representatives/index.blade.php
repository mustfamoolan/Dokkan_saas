@extends('subscriber.layouts.app')

@section('title', 'المناديب')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card mt-3">
            <div class="card-header d-flex justify-content-between align-items-center bg-white">
                <h5 class="mb-0">قائمة المناديب</h5>
                <a href="{{ route('subscriber.app.representatives.create') }}" class="btn btn-primary btn-sm">
                    <iconify-icon icon="solar:user-plus-bold" class="me-1"></iconify-icon>إضافة مندوب جديد
                </a>
            </div>
            <div class="card-body p-0">
                @if(session('success'))
                    <div class="alert alert-success m-3">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger m-3">{{ session('error') }}</div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>الاسم</th>
                                <th>الهاتف</th>
                                <th>الحالة</th>
                                <th>العمولة</th>
                                <th>تاريخ الإضافة</th>
                                <th class="text-end">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($representatives as $rep)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="avatar-sm bg-primary-subtle rounded-circle d-flex align-items-center justify-content-center">
                                                <iconify-icon icon="solar:user-bold" class="text-primary fs-20"></iconify-icon>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ $rep->name }}</h6>
                                                <small class="text-muted">{{ $rep->email ?? 'لا يوجد بريد' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $rep->phone }}</td>
                                    <td>
                                        @if($rep->is_active)
                                            <span class="badge bg-success-subtle text-success">نشط</span>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger">معطل</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($rep->commission_type)
                                            {{ $rep->commission_value }} 
                                            {{ $rep->commission_type == 'percentage' ? '%' : 'د.أ' }}
                                        @else
                                            <span class="text-muted">غير محددة</span>
                                        @endif
                                    </td>
                                    <td>{{ $rep->created_at->format('Y-m-d') }}</td>
                                    <td class="text-end">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-icon border-0" type="button" data-bs-toggle="dropdown">
                                                <iconify-icon icon="solar:menu-dots-vertical-bold"></iconify-icon>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li><a class="dropdown-item" href="{{ route('subscriber.app.representatives.show', $rep->id) }}">عرض التفاصيل</a></li>
                                                <li><a class="dropdown-item" href="{{ route('subscriber.app.representatives.edit', $rep->id) }}">تعديل</a></li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <form action="{{ route('subscriber.app.representatives.destroy', $rep->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا المندوب؟')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger">حذف</button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">لا يوجد مناديب مسجلين حالياً</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-3">
                    {{ $representatives->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@section('styles')
<style>
    .avatar-sm { width: 35px; height: 35px; }
</style>
@endsection
@endsection
