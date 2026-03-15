<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('subscriber.login');
});

// Subscriber Public Routes
Route::name('subscriber.')->group(function () {
    Route::middleware('guest:subscriber')->group(function () {
        Route::get('/register', [App\Http\Controllers\Subscriber\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
        Route::post('/register', [App\Http\Controllers\Subscriber\Auth\RegisterController::class, 'register']);
        Route::get('/login', [App\Http\Controllers\Subscriber\Auth\LoginController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [App\Http\Controllers\Subscriber\Auth\LoginController::class, 'login']);
    });

    Route::post('/logout', [App\Http\Controllers\Subscriber\Auth\LoginController::class, 'logout'])->name('logout');

    Route::middleware('auth:subscriber')->prefix('onboarding')->name('onboarding.')->group(function () {
        Route::get('/status', [App\Http\Controllers\Subscriber\OnboardingController::class, 'status'])->name('status');
        Route::get('/store-setup', [App\Http\Controllers\Subscriber\OnboardingController::class, 'showStoreSetup'])->name('store-setup');
        Route::post('/store-setup', [App\Http\Controllers\Subscriber\OnboardingController::class, 'saveStoreSetup'])->name('store-setup.save');
        Route::get('/plan-selection', [App\Http\Controllers\Subscriber\OnboardingController::class, 'showPlanSelection'])->name('plan-selection');
        Route::post('/plan-selection/{plan}', [App\Http\Controllers\Subscriber\OnboardingController::class, 'selectPlan'])->name('plan-selection.save');
        Route::get('/payment/{plan}', [App\Http\Controllers\Subscriber\OnboardingController::class, 'showPayment'])->name('payment');
        Route::post('/payment/{plan}', [App\Http\Controllers\Subscriber\OnboardingController::class, 'submitPayment'])->name('payment.save');
    });

    // Internal App Area
    Route::middleware(['auth:subscriber', 'subscriber.access'])->prefix('app')->name('app.')->group(function () {
        Route::get('/', [App\Http\Controllers\Subscriber\App\DashboardController::class, 'index'])->name('dashboard');

        // Categories
        Route::resource('categories', App\Http\Controllers\Subscriber\App\CategoryController::class)->except(['show']);
        
        // Products
        Route::resource('products', App\Http\Controllers\Subscriber\App\ProductController::class);

        // Customers
        Route::resource('customers', App\Http\Controllers\Subscriber\App\CustomerController::class);

        // Suppliers
        Route::resource('suppliers', App\Http\Controllers\Subscriber\App\SupplierController::class);

        // Warehouses
        Route::get('warehouses/{warehouse}/stock', [App\Http\Controllers\Subscriber\App\WarehouseController::class, 'stock'])->name('warehouses.stock');
        Route::resource('warehouses', App\Http\Controllers\Subscriber\App\WarehouseController::class);

        // Purchases
        Route::post('purchases/{purchase}/post', [App\Http\Controllers\Subscriber\App\PurchaseController::class, 'post'])->name('purchases.post');
        Route::post('purchases/{purchase}/cancel', [App\Http\Controllers\Subscriber\App\PurchaseController::class, 'cancel'])->name('purchases.cancel');
        Route::resource('purchases', App\Http\Controllers\Subscriber\App\PurchaseController::class);

        // Sales
        Route::post('sales/{sale}/post', [App\Http\Controllers\Subscriber\App\SalesController::class, 'post'])->name('sales.post');
        Route::post('sales/{sale}/cancel', [App\Http\Controllers\Subscriber\App\SalesController::class, 'cancel'])->name('sales.cancel');
        Route::resource('sales', App\Http\Controllers\Subscriber\App\SalesController::class);

        // POS
        Route::get('pos', [App\Http\Controllers\Subscriber\App\POSController::class, 'index'])->name('pos.index');
        Route::get('pos/search', [App\Http\Controllers\Subscriber\App\POSController::class, 'search'])->name('pos.search');
        Route::post('pos', [App\Http\Controllers\Subscriber\App\POSController::class, 'store'])->name('pos.store');

        // Cashboxes
        Route::post('cashboxes/{cashbox}/adjust', [App\Http\Controllers\Subscriber\App\CashboxController::class, 'adjust'])->name('cashboxes.adjust');
        Route::resource('cashboxes', App\Http\Controllers\Subscriber\App\CashboxController::class);

        // Expenses
        Route::resource('expenses', App\Http\Controllers\Subscriber\App\ExpenseController::class);

        // Customer Payments
        Route::resource('customer-payments', App\Http\Controllers\Subscriber\App\CustomerPaymentController::class)->except(['edit', 'update', 'destroy']);

        // Supplier Payments
        Route::resource('supplier-payments', App\Http\Controllers\Subscriber\App\SupplierPaymentController::class)->except(['edit', 'update', 'destroy']);

        // Debts & Statements
        Route::get('customers/{customer}/statement', [App\Http\Controllers\Subscriber\App\StatementController::class, 'customerStatement'])->name('customers.statement');
        Route::get('suppliers/{supplier}/statement', [App\Http\Controllers\Subscriber\App\StatementController::class, 'supplierStatement'])->name('suppliers.statement');
        Route::get('customer-balances', [App\Http\Controllers\Subscriber\App\StatementController::class, 'customerBalances'])->name('customer-balances');
        Route::get('supplier-balances', [App\Http\Controllers\Subscriber\App\StatementController::class, 'supplierBalances'])->name('supplier-balances');
    });
});

// Guest Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [App\Http\Controllers\Admin\Auth\LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [App\Http\Controllers\Admin\Auth\LoginController::class, 'login']);
    Route::post('/logout', [App\Http\Controllers\Admin\Auth\LoginController::class, 'logout'])->name('logout');

    // Protected Admin Routes
    Route::middleware(['admin'])->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/admins', [App\Http\Controllers\Admin\AdminController::class, 'index'])->name('admins')->can('view admins');
        Route::get('/admins/create', [App\Http\Controllers\Admin\AdminController::class, 'create'])->name('admins.create')->can('create admins');
        Route::post('/admins', [App\Http\Controllers\Admin\AdminController::class, 'store'])->name('admins.store')->can('create admins');
        Route::get('/admins/{admin}/edit', [App\Http\Controllers\Admin\AdminController::class, 'edit'])->name('admins.edit')->can('edit admins');
        Route::put('/admins/{admin}', [App\Http\Controllers\Admin\AdminController::class, 'update'])->name('admins.update')->can('edit admins');
        
        Route::get('/plans', [App\Http\Controllers\Admin\PlanController::class, 'index'])->name('plans')->can('view plans');
        Route::get('/plans/create', [App\Http\Controllers\Admin\PlanController::class, 'create'])->name('plans.create')->can('create plans');
        Route::post('/plans', [App\Http\Controllers\Admin\PlanController::class, 'store'])->name('plans.store')->can('create plans');
        Route::get('/plans/{plan}/edit', [App\Http\Controllers\Admin\PlanController::class, 'edit'])->name('plans.edit')->can('edit plans');
        Route::put('/plans/{plan}', [App\Http\Controllers\Admin\PlanController::class, 'update'])->name('plans.update')->can('edit plans');
        
        Route::get('/plans/{plan}/features', [App\Http\Controllers\Admin\PlanFeatureController::class, 'edit'])->name('plans.features')->can('view plan features');
        Route::put('/plans/{plan}/features', [App\Http\Controllers\Admin\PlanFeatureController::class, 'update'])->name('plans.features.update')->can('edit plan features');

        Route::get('/subscribers', [App\Http\Controllers\Admin\SubscriberController::class, 'index'])->name('subscribers')->can('view subscribers');
        Route::get('/subscribers/create', [App\Http\Controllers\Admin\SubscriberController::class, 'create'])->name('subscribers.create')->can('create subscribers');
        Route::post('/subscribers', [App\Http\Controllers\Admin\SubscriberController::class, 'store'])->name('subscribers.store')->can('create subscribers');
        Route::get('/subscribers/{subscriber}', [App\Http\Controllers\Admin\SubscriberController::class, 'show'])->name('subscribers.show')->can('view subscribers');
        Route::get('/subscribers/{subscriber}/edit', [App\Http\Controllers\Admin\SubscriberController::class, 'edit'])->name('subscribers.edit')->can('edit subscribers');
        Route::put('/subscribers/{subscriber}', [App\Http\Controllers\Admin\SubscriberController::class, 'update'])->name('subscribers.update')->can('edit subscribers');

        Route::get('/subscriptions', [App\Http\Controllers\Admin\SubscriptionController::class, 'index'])->name('subscriptions')->can('view subscriptions');
        Route::get('/subscriptions/create', [App\Http\Controllers\Admin\SubscriptionController::class, 'create'])->name('subscriptions.create')->can('create subscriptions');
        Route::post('/subscriptions', [App\Http\Controllers\Admin\SubscriptionController::class, 'store'])->name('subscriptions.store')->can('create subscriptions');
        Route::get('/subscriptions/{subscription}', [App\Http\Controllers\Admin\SubscriptionController::class, 'show'])->name('subscriptions.show')->can('view subscriptions');
        Route::get('/subscriptions/{subscription}/edit', [App\Http\Controllers\Admin\SubscriptionController::class, 'edit'])->name('subscriptions.edit')->can('edit subscriptions');
        Route::put('/subscriptions/{subscription}', [App\Http\Controllers\Admin\SubscriptionController::class, 'update'])->name('subscriptions.update')->can('edit subscriptions');

        Route::get('/payments', [App\Http\Controllers\Admin\PaymentController::class, 'index'])->name('payments')->can('view payments');
        Route::get('/payments/create', [App\Http\Controllers\Admin\PaymentController::class, 'create'])->name('payments.create')->can('create payments');
        Route::post('/payments', [App\Http\Controllers\Admin\PaymentController::class, 'store'])->name('payments.store')->can('create payments');
        Route::get('/payments/{payment}', [App\Http\Controllers\Admin\PaymentController::class, 'show'])->name('payments.show')->can('view payments');
        Route::post('/payments/{payment}/approve', [App\Http\Controllers\Admin\PaymentController::class, 'approve'])->name('payments.approve')->can('review payments');
        Route::post('/payments/{payment}/reject', [App\Http\Controllers\Admin\PaymentController::class, 'reject'])->name('payments.reject')->can('review payments');

        Route::get('/stores/{store}/usage', [App\Http\Controllers\Admin\UsageController::class, 'show'])->name('usage.show')->can('view usage');
        Route::post('/stores/{store}/usage/overrides', [App\Http\Controllers\Admin\UsageController::class, 'storeOverride'])->name('usage.overrides.store')->can('manage usage overrides');
        Route::delete('/stores/{store}/usage/overrides/{override}', [App\Http\Controllers\Admin\UsageController::class, 'deleteOverride'])->name('usage.overrides.delete')->can('manage usage overrides');
        Route::post('/stores/{store}/usage/counters', [App\Http\Controllers\Admin\UsageController::class, 'updateCounter'])->name('usage.counters.update')->can('view usage');

        Route::get('/roles', [App\Http\Controllers\Admin\RoleController::class, 'index'])->name('roles');
        // Settings
        Route::get('/settings', [App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings')->can('manage settings');
        Route::post('/settings', [App\Http\Controllers\Admin\SettingController::class, 'update'])->can('manage settings');
    });
});
