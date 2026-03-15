<!DOCTYPE html>
<html lang="ar" dir="rtl" data-bs-theme="light" data-menu-color="dark" data-topbar-color="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - {{ config('app.name') }}</title>
    @vite(['resources/scss/app.scss', 'resources/js/app.js', 'resources/js/config.js', 'resources/js/layout.js'])
    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
    <style>
        .sidebar-link iconify-icon { font-size: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <div class="main-nav">
            <div class="logo-box text-center py-3">
                <a href="#" class="logo-light">
                    <h3 class="text-white fw-bold mb-0">{{ config('app.name') }}</h3>
                </a>
            </div>

            <div class="scrollbar" data-simplebar>
                <ul class="navbar-nav" id="navbar-nav">
                    <li class="menu-title">القائمة الرئيسية</li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('subscriber.app.dashboard') }}">
                            <span class="nav-icon"><iconify-icon icon="solar:home-2-bold"></iconify-icon></span>
                            <span class="nav-text">الرئيسية</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('subscriber.app.products.index') }}">
                            <span class="nav-icon"><iconify-icon icon="solar:box-bold"></iconify-icon></span>
                            <span class="nav-text">المنتجات</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('subscriber.app.categories.index') }}">
                            <span class="nav-icon"><iconify-icon icon="solar:widget-bold"></iconify-icon></span>
                            <span class="nav-text">الأصناف</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('subscriber.app.warehouses.index') }}">
                            <span class="nav-icon"><iconify-icon icon="solar:home-bold"></iconify-icon></span>
                            <span class="nav-text">المستودعات</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('subscriber.app.pos.index') }}">
                            <span class="nav-icon"><iconify-icon icon="solar:monitor-camera-bold"></iconify-icon></span>
                            <span class="nav-text">نقطة البيع (POS)</span>
                        </a>
                    </li>

                    <li class="nav-item-title">المالية</li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('subscriber.app.cashboxes.index') }}">
                            <span class="nav-icon"><iconify-icon icon="solar:wallet-2-bold"></iconify-icon></span>
                            <span class="nav-text">الصناديق المالية</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('subscriber.app.expenses.index') }}">
                            <span class="nav-icon"><iconify-icon icon="solar:bill-list-bold"></iconify-icon></span>
                            <span class="nav-text">المصاريف</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('subscriber.app.customer-payments.index') }}">
                            <span class="nav-icon"><iconify-icon icon="solar:round-transfer-horizontal-bold"></iconify-icon></span>
                            <span class="nav-text">سندات القبض (العملاء)</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('subscriber.app.customer-balances') }}">
                            <span class="nav-icon"><iconify-icon icon="solar:users-group-rounded-bold"></iconify-icon></span>
                            <span class="nav-text">أرصدة العملاء</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('subscriber.app.supplier-payments.index') }}">
                            <span class="nav-icon"><iconify-icon icon="solar:round-transfer-horizontal-bold-duotone" style="transform: rotate(180deg)"></iconify-icon></span>
                            <span class="nav-text">سندات الصرف (الموردين)</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('subscriber.app.supplier-balances') }}">
                            <span class="nav-icon"><iconify-icon icon="solar:shop-2-bold"></iconify-icon></span>
                            <span class="nav-text">أرصدة الموردين</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link text-primary fw-bold" href="{{ route('subscriber.app.reports.index') }}">
                            <span class="nav-icon"><iconify-icon icon="solar:chart-2-bold-duotone"></iconify-icon></span>
                            <span class="nav-text">مركز التقارير</span>
                        </a>
                    </li>

                    <li class="nav-item-title">العمليات</li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('subscriber.app.sales.index') }}">
                            <span class="nav-icon"><iconify-icon icon="solar:cart-large-minimalistic-bold"></iconify-icon></span>
                            <span class="nav-text">المبيعات</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('subscriber.app.purchases.index') }}">
                            <span class="nav-icon"><iconify-icon icon="solar:bill-list-bold"></iconify-icon></span>
                            <span class="nav-text">المشتريات</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('subscriber.app.customers.index') }}">
                            <span class="nav-icon"><iconify-icon icon="solar:users-group-two-rounded-bold"></iconify-icon></span>
                            <span class="nav-text">العملاء</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('subscriber.app.suppliers.index') }}">
                            <span class="nav-icon"><iconify-icon icon="solar:shop-bold"></iconify-icon></span>
                            <span class="nav-text">الموردين</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <span class="nav-icon"><iconify-icon icon="solar:settings-bold"></iconify-icon></span>
                            <span class="nav-text">الإعدادات (قريباً)</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Topbar -->
        <header class="topbar">
            <div class="container-fluid">
                <div class="navbar-header">
                    <div class="d-flex align-items-center gap-2">
                        <button type="button" class="button-toggle-menu">
                            <iconify-icon icon="solar:hamburger-menu-broken"></iconify-icon>
                        </button>
                        <h4 class="mb-0 fs-18">@yield('title')</h4>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <div class="dropdown">
                            <button class="nav-link dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <span class="d-none d-md-block">{{ Auth::guard('subscriber')->user()->name }}</span>
                                <iconify-icon icon="solar:user-circle-bold" class="fs-22"></iconify-icon>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="#">الملف الشخصي</a>
                                <div class="dropdown-divider"></div>
                                <form action="{{ route('subscriber.logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">تسجيل الخروج</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <div class="page-content">
            <div class="container-fluid">
                @yield('content')
            </div>
        </div>
    </div>
</body>
</html>
