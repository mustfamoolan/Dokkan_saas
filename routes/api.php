<?php

use App\Http\Controllers\Api\Admin\AuthController;
use App\Http\Controllers\Api\Admin\EmployeeTypeController;
use App\Http\Controllers\Api\Admin\PermissionController;
use App\Http\Controllers\Api\Admin\RoleController;
use App\Http\Controllers\Api\Admin\UserController;
use App\Http\Controllers\Api\Inventory\CategoryController;
use App\Http\Controllers\Api\Inventory\ProductController;
use App\Http\Controllers\Api\Inventory\SupplierController;
use App\Http\Controllers\Api\Inventory\TagController;
use App\Http\Controllers\Api\Representatives\AccountManagementController;
use App\Http\Controllers\Api\Representatives\RepresentativeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Admin API Routes
Route::prefix('admin')->group(function () {
    // Auth routes (no authentication required)
    Route::post('/auth/login', [AuthController::class, 'login']);

    // Protected routes (require authentication)
    Route::middleware('auth:sanctum')->group(function () {
        // Auth routes
        Route::post('/auth/user', [AuthController::class, 'user']);
        Route::post('/auth/logout', [AuthController::class, 'logout']);
        Route::post('/auth/update-profile', [AuthController::class, 'updateProfile']);

        // Users routes
        Route::apiResource('users', UserController::class);
        Route::post('users/{user}/assign-role', [UserController::class, 'assignRole']);
        Route::post('users/{user}/revoke-role', [UserController::class, 'revokeRole']);
        Route::post('users/{user}/assign-permission', [UserController::class, 'assignPermission']);
        Route::post('users/{user}/revoke-permission', [UserController::class, 'revokePermission']);

        // Roles routes
        Route::apiResource('roles', RoleController::class);
        Route::post('roles/{role}/assign-permission', [RoleController::class, 'assignPermission']);
        Route::post('roles/{role}/revoke-permission', [RoleController::class, 'revokePermission']);

        // Permissions routes
        Route::apiResource('permissions', PermissionController::class);

        // Employee Types routes
        Route::apiResource('employee-types', EmployeeTypeController::class);
        Route::post('employee-types/{employee_type}/assign-role', [EmployeeTypeController::class, 'assignRole']);
        Route::post('employee-types/{employee_type}/revoke-role', [EmployeeTypeController::class, 'revokeRole']);

        // Settings routes
        Route::prefix('settings')->group(function () {
            // General Settings
            Route::get('/general', [\App\Http\Controllers\Api\Admin\GeneralSettingsApiController::class, 'index']);
            Route::put('/general', [\App\Http\Controllers\Api\Admin\GeneralSettingsApiController::class, 'update']);

            // Gifts Management (General)
            Route::post('/general/gifts', [\App\Http\Controllers\Api\Admin\GeneralSettingsApiController::class, 'storeGift']);
            Route::put('/general/gifts/{id}', [\App\Http\Controllers\Api\Admin\GeneralSettingsApiController::class, 'updateGift']);
            Route::delete('/general/gifts/{id}', [\App\Http\Controllers\Api\Admin\GeneralSettingsApiController::class, 'destroyGift']);

            // Order Commission
            Route::prefix('order-commission')->group(function () {
                Route::get('/', [\App\Http\Controllers\Api\Admin\OrderCommissionSettingsController::class, 'index']);
                Route::post('/', [\App\Http\Controllers\Api\Admin\OrderCommissionSettingsController::class, 'storeOrUpdate']);
                Route::post('/exceptions', [\App\Http\Controllers\Api\Admin\OrderCommissionSettingsController::class, 'storeException']);
                Route::put('/exceptions/{id}', [\App\Http\Controllers\Api\Admin\OrderCommissionSettingsController::class, 'updateException']);
                Route::delete('/exceptions/{id}', [\App\Http\Controllers\Api\Admin\OrderCommissionSettingsController::class, 'destroyException']);
            });

            // Gift Points
            Route::prefix('gift-points')->group(function () {
                Route::get('/', [\App\Http\Controllers\Api\Admin\GiftPointsSettingsController::class, 'index']);
                Route::post('/', [\App\Http\Controllers\Api\Admin\GiftPointsSettingsController::class, 'storeOrUpdate']);
                Route::post('/exceptions', [\App\Http\Controllers\Api\Admin\GiftPointsSettingsController::class, 'storeException']);
                Route::put('/exceptions/{id}', [\App\Http\Controllers\Api\Admin\GiftPointsSettingsController::class, 'updateException']);
                Route::delete('/exceptions/{id}', [\App\Http\Controllers\Api\Admin\GiftPointsSettingsController::class, 'destroyException']);
            });
        });

        // Inventory routes
        Route::prefix('inventory')->group(function () {
            Route::get('products/{product}/recommendations', [ProductController::class, 'recommendations']);
            Route::apiResource('products', ProductController::class);
            Route::post('products/{product}/add-quantity', [ProductController::class, 'addQuantity']);
            Route::get('products/low-stock', [ProductController::class, 'getLowStock']);

            Route::apiResource('categories', CategoryController::class);
            Route::apiResource('suppliers', SupplierController::class);
            Route::apiResource('tags', TagController::class);
        });

        // FCM routes
        Route::post('/fcm/token', [\App\Http\Controllers\Api\FCMController::class, 'storeToken'])->name('api.admin.fcm.token.store');
        Route::delete('/fcm/token', [\App\Http\Controllers\Api\FCMController::class, 'removeToken'])->name('api.admin.fcm.token.remove');
        Route::get('/fcm/token/status', [\App\Http\Controllers\Api\FCMController::class, 'getTokenStatus'])->name('api.admin.fcm.token.status');

        // Notifications routes
        Route::get('/notifications/unread-count', [\App\Http\Controllers\NotificationController::class, 'unreadCount'])->name('api.admin.notifications.unread-count');

        // AI routes
        Route::post('/ai/generate-product-description', [\App\Http\Controllers\Api\AI\ProductDescriptionController::class, 'generate'])->name('api.admin.ai.generate-product-description');

        // Representatives routes
        Route::prefix('representatives')->group(function () {
            Route::get('representatives/{representative}/statistics', [RepresentativeController::class, 'statistics']);
            Route::apiResource('representatives', RepresentativeController::class);
        });

        // Orders routes
        Route::apiResource('orders', \App\Http\Controllers\Api\Admin\OrderController::class)->only(['index', 'show']);
        Route::post('orders/{order}/status', [\App\Http\Controllers\Api\Admin\OrderController::class, 'updateStatus']);

        // Representative Accounts
        Route::prefix('accounts')->middleware('auth:sanctum')->group(function () {
            Route::get('/', [AccountManagementController::class, 'index']);
            Route::get('/{representative}', [AccountManagementController::class, 'show']);
            Route::get('/{representative}/transactions', [AccountManagementController::class, 'transactions']);
            Route::post('/{representative}/add-balance', [AccountManagementController::class, 'addBalance']);
            Route::post('/{representative}/direct-withdraw', [AccountManagementController::class, 'directWithdraw']);
        });
    });
});
