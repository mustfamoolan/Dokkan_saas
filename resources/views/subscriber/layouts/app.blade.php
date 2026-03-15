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
                        <a class="nav-link {{ request()->routeIs('subscriber.app.representatives.*') ? 'active' : '' }}" href="{{ route('subscriber.app.representatives.index') }}">
                            <span class="nav-icon"><iconify-icon icon="solar:users-group-rounded-bold-duotone"></iconify-icon></span>
                            <span class="nav-text">المناديب</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('subscriber.app.orders.*') ? 'active' : '' }}" href="{{ route('subscriber.app.orders.index') }}">
                            <span class="nav-icon"><iconify-icon icon="solar:delivery-bold-duotone"></iconify-icon></span>
                            <span class="nav-text">طلبات التوصيل</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('subscriber.app.notifications.*') ? 'active' : '' }}" href="{{ route('subscriber.app.notifications.index') }}">
                            <span class="nav-icon"><iconify-icon icon="solar:bell-bing-bold-duotone"></iconify-icon></span>
                            <span class="nav-text">التنبيهات</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('subscriber.app.settings.*') ? 'active' : '' }}" href="{{ route('subscriber.app.settings.store') }}">
                            <span class="nav-icon"><iconify-icon icon="solar:settings-bold"></iconify-icon></span>
                            <span class="nav-text">إعدادات المتجر</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Topbar -->
        @inject('notificationService', 'App\Services\NotificationService')
        @php
            $unreadCount = $notificationService->getUnreadCount(Auth::guard('subscriber')->user()->store->id);
            $latestNotifications = $notificationService->getLatest(Auth::guard('subscriber')->user()->store->id);
        @endphp
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
                        <!-- Notifications -->
                        <div class="dropdown">
                            <button class="nav-link dropdown-toggle arrow-none" data-bs-toggle="dropdown" type="button">
                                <iconify-icon icon="solar:bell-bing-bold" class="fs-22"></iconify-icon>
                                @if($unreadCount > 0)
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                        {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                                    </span>
                                @endif
                            </button>
                            <div class="dropdown-menu dropdown-menu-end dropdown-menu-animated dropdown-lg py-0 overflow-hidden">
                                <div class="p-2 border-bottom bg-light">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <h6 class="m-0">التنبيهات</h6>
                                        </div>
                                        <div class="col-auto">
                                            <a href="{{ route('subscriber.app.notifications.index') }}" class="text-dark"><small>عرض الكل</small></a>
                                        </div>
                                    </div>
                                </div>

                                <div class="scrollbar" style="max-height: 300px;" data-simplebar>
                                    @forelse($latestNotifications as $notif)
                                        <a href="{{ route('subscriber.app.notifications.mark-read', $notif->id) }}" class="dropdown-item p-3 border-bottom {{ $notif->is_read ? 'opacity-75' : 'bg-light-subtle' }}">
                                            <div class="d-flex align-items-start gap-2">
                                                <div class="flex-shrink-0">
                                                    @php
                                                        $notifIcon = 'solar:info-circle-bold';
                                                        $notifColor = 'text-info';
                                                        if($notif->severity === 'warning') { $notifIcon = 'solar:danger-triangle-bold'; $notifColor = 'text-warning'; }
                                                        if($notif->severity === 'danger') { $notifIcon = 'solar:danger-circle-bold'; $notifColor = 'text-danger'; }
                                                        if($notif->severity === 'success') { $notifIcon = 'solar:check-circle-bold'; $notifColor = 'text-success'; }
                                                    @endphp
                                                    <iconify-icon icon="{{ $notifIcon }}" class="fs-20 {{ $notifColor }}"></iconify-icon>
                                                </div>
                                                <div class="flex-grow-1 overflow-hidden">
                                                    <h6 class="mb-1 fw-bold fs-14">{{ $notif->title }}</h6>
                                                    <p class="text-muted small mb-0 text-truncate">{{ $notif->message }}</p>
                                                    <small class="text-muted">{{ $notif->created_at->diffForHumans() }}</small>
                                                </div>
                                            </div>
                                        </a>
                                    @empty
                                        <div class="text-center p-4">
                                            <p class="text-muted mb-0">لا توجد تنبيهات جديدة</p>
                                        </div>
                                    @endforelse
                                </div>

                                <a href="{{ route('subscriber.app.notifications.index') }}" class="dropdown-item text-center text-primary fw-bold py-2 border-top">
                                    عرض جميع التنبيهات
                                </a>
                            </div>
                        </div>

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
