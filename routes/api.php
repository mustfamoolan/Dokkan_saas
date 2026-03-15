<?php

use App\Http\Controllers\Api\V1\Auth\ApiAuthController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\CustomerController;
use App\Http\Controllers\Api\V1\SupplierController;
use App\Http\Controllers\Api\V1\WarehouseController;
use App\Http\Controllers\Api\V1\SaleController;
use App\Http\Controllers\Api\V1\PurchaseController;
use App\Http\Controllers\Api\V1\PaymentController;
use App\Http\Controllers\Api\V1\NotificationController;

Route::prefix('v1')->group(function () {
    
    // Auth Routes
    Route::prefix('auth')->group(function () {
        Route::post('login', [ApiAuthController::class, 'login']);
        
        Route::middleware('auth:sanctum')->group(function () {
            Route::get('me', [ApiAuthController::class, 'me']);
            Route::post('logout', [ApiAuthController::class, 'logout']);
        });
    });

    // Protected Routes (Sanctum + Store Isolation)
    Route::middleware(['auth:sanctum', 'api.store.scope', 'throttle:api'])->group(function () {
        
        // Products
        Route::get('products', [ProductController::class, 'index']);
        Route::get('products/{id}', [ProductController::class, 'show']);

        // Customers
        Route::get('customers', [CustomerController::class, 'index']);
        Route::get('customers/{id}', [CustomerController::class, 'show']);

        // Suppliers
        Route::get('suppliers', [SupplierController::class, 'index']);
        Route::get('suppliers/{id}', [SupplierController::class, 'show']);

        // Warehouses
        Route::get('warehouses', [WarehouseController::class, 'index']);

        // Sales
        Route::get('sales', [SaleController::class, 'index']);
        Route::get('sales/{id}', [SaleController::class, 'show']);

        // Purchases
        Route::get('purchases', [PurchaseController::class, 'index']);
        Route::get('purchases/{id}', [PurchaseController::class, 'show']);

        // Payments
        Route::get('payments/customers', [PaymentController::class, 'customerPayments']);
        Route::get('payments/suppliers', [PaymentController::class, 'supplierPayments']);

        // Notifications
        Route::get('notifications', [NotificationController::class, 'index']);
        Route::post('notifications/{id}/read', [NotificationController::class, 'markAsRead']);

    });
});
