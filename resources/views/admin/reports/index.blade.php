@extends('layouts.vertical', ['title' => 'التقارير التفصيلية'])

@section('css')
    <style>
        .stat-card {
            border-radius: 20px;
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .progress-thin {
            height: 6px;
        }
    </style>
@endsection

@section('content')
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-transparent border-0 shadow-none">
                <div class="card-body p-0">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h2 class="fw-black mb-1" style="letter-spacing: -0.5px;">التقارير التفصيلية</h2>
                            <div style="width: 40px; height: 4px; background: #4e73df; border-radius: 2px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Date Filter -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="border-radius: 20px;">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.reports.index') }}" class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">من تاريخ</label>
                            <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">إلى تاريخ</label>
                            <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary w-100">
                                <iconify-icon icon="solar:refresh-bold" class="me-1"></iconify-icon>
                                تحديث البيانات
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Financial Summary -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card border-0 shadow-sm bg-primary text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <iconify-icon icon="solar:banknote-bold-duotone" class="fs-40 opacity-50"></iconify-icon>
                        <span class="badge bg-white bg-opacity-20 rounded-pill">إجمالي الإيرادات</span>
                    </div>
                    <h3 class="fw-bold mb-1 text-white">{{ format_currency($totalRevenue) }}</h3>
                    <p class="mb-0 text-white text-opacity-75 small">مبالغ الطلبات المكتملة</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card border-0 shadow-sm bg-success text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <iconify-icon icon="solar:hand-stars-bold-duotone" class="fs-40 opacity-50"></iconify-icon>
                        <span class="badge bg-white bg-opacity-20 rounded-pill">إجمالي أرباح البيع</span>
                    </div>
                    <h3 class="fw-bold mb-1 text-white">{{ format_currency($totalGrossProfit) }}</h3>
                    <p class="mb-0 text-white text-opacity-75 small">الربح من فارق السعر والعمولات</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card border-0 shadow-sm bg-danger text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <iconify-icon icon="solar:bill-list-bold-duotone" class="fs-40 opacity-50"></iconify-icon>
                        <span class="badge bg-white bg-opacity-20 rounded-pill">إجمالي المصروفات</span>
                    </div>
                    <h3 class="fw-bold mb-1 text-white">{{ format_currency($totalExpenses) }}</h3>
                    <p class="mb-0 text-white text-opacity-75 small">تكاليف تشغيلية أخرى</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card border-0 shadow-sm bg-dark text-white h-100"
                style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%) !important;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <iconify-icon icon="solar:case-round-bold-duotone"
                            class="fs-40 text-warning opacity-75"></iconify-icon>
                        <span class="badge bg-white bg-opacity-20 rounded-pill">صافي الربح</span>
                    </div>
                    <h3 class="fw-bold mb-1 text-warning">{{ format_currency($netProfit) }}</h3>
                    <p class="mb-0 text-white text-opacity-75 small">الربح النهائي بعد الخصم</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Profits by Parent Category -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 20px;">
                <div class="card-header bg-transparent border-0 pt-4 px-4">
                    <h5 class="card-title fw-bold mb-0">أرباح الأقسام (الفئات الرئيسية)</h5>
                </div>
                <div class="card-body px-4">
                    @forelse($sectionProfits as $section)
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-semibold">{{ $section->section_name }}</span>
                                <span class="fw-bold text-primary">{{ format_currency($section->profit) }}</span>
                            </div>
                            <div class="progress progress-thin bg-light">
                                @php $percent = $totalGrossProfit > 0 ? ($section->profit / $totalGrossProfit) * 100 : 0; @endphp
                                <div class="progress-bar" role="progressbar" style="width: {{ $percent }}%;"
                                    aria-valuenow="{{ $percent }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-muted py-4">لا توجد بيانات متاحة</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Profits by Sub Category -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 20px;">
                <div class="card-header bg-transparent border-0 pt-4 px-4">
                    <h5 class="card-title fw-bold mb-0">أرباح الفروع (الفئات الفرعية)</h5>
                </div>
                <div class="card-body px-4">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>الفئة الفرعية</th>
                                    <th class="text-end">الربح</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($branchProfits->take(10) as $branch)
                                    <tr>
                                        <td>{{ $branch->branch_name }}</td>
                                        <td class="text-end fw-bold text-success">{{ format_currency($branch->profit) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center py-4">لا توجد بيانات</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Representatives -->
        <div class="col-lg-7 mb-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 20px;">
                <div class="card-header bg-transparent border-0 pt-4 px-4">
                    <h5 class="card-title fw-bold mb-0">أفضل المناديب تحقيقاً للربح</h5>
                </div>
                <div class="card-body px-4">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>المندوب</th>
                                    <th class="text-center">عدد الطلبات</th>
                                    <th class="text-end">إجمالي الربح</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($repPerformance as $rep)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-soft-primary text-primary rounded-circle d-flex align-items-center justify-content-center me-2 fw-bold"
                                                    style="width: 32px; height: 32px;">
                                                    {{ substr($rep->representative->name ?? '?', 0, 1) }}
                                                </div>
                                                <span class="fw-bold">{{ $rep->representative->name ?? 'محذوف' }}</span>
                                            </div>
                                        </td>
                                        <td class="text-center">{{ $rep->orders_count }}</td>
                                        <td class="text-end fw-bold text-primary">{{ format_currency($rep->total_profit) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-4 text-muted">لا يوجد مناديب مسجلين في هذه الفترة
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Expense Breakdown -->
        <div class="col-lg-5 mb-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 20px;">
                <div class="card-header bg-transparent border-0 pt-4 px-4">
                    <h5 class="card-title fw-bold mb-0">توزيع المصروفات</h5>
                </div>
                <div class="card-body px-4">
                    @forelse($expenseCategories as $expense)
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <span class="d-block fw-semibold">{{ $expense->category_name }}</span>
                                @php $exPercent = $totalExpenses > 0 ? ($expense->total / $totalExpenses) * 100 : 0; @endphp
                                <small class="text-muted">{{ number_format($exPercent, 1) }}% من الإجمالي</small>
                            </div>
                            <span class="fw-bold text-danger">{{ format_currency($expense->total) }}</span>
                        </div>
                    @empty
                        <p class="text-center text-muted py-4">لا توجد مصروفات مسجلة</p>
                    @endforelse

                    @if($totalExpenses > 0)
                        <hr>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <span class="fw-bold">المجموع</span>
                            <h4 class="fw-black text-danger mb-0">{{ format_currency($totalExpenses) }}</h4>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection