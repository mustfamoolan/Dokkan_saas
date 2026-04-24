<?php

use Illuminate\Support\Facades\Route;

Route::post('/login', 'App\Http\Controllers\Api\AuthController@login');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', 'App\Http\Controllers\Api\AuthController@logout');
    Route::get('/employees', 'App\Http\Controllers\Api\EmployeeController@index');
    Route::post('/employees', 'App\Http\Controllers\Api\EmployeeController@store');
    Route::get('/employees/{employee}', 'App\Http\Controllers\Api\EmployeeController@show');
    Route::post('/employees/{employee}', 'App\Http\Controllers\Api\EmployeeController@update');
    Route::delete('/employees/{employee}', 'App\Http\Controllers\Api\EmployeeController@destroy');

    // Suppliers
    Route::get('/suppliers', 'App\Http\Controllers\Api\SupplierController@index');
    Route::post('/suppliers', 'App\Http\Controllers\Api\SupplierController@store');
    Route::put('/suppliers/{id}', 'App\Http\Controllers\Api\SupplierController@update');
    Route::delete('/suppliers/{id}', 'App\Http\Controllers\Api\SupplierController@destroy');

    // Inventory
    Route::get('/categories', 'App\Http\Controllers\Api\CategoryController@index');
    Route::post('/categories', 'App\Http\Controllers\Api\CategoryController@store');

    Route::get('/products', 'App\Http\Controllers\Api\ProductController@index');
    Route::post('/products', 'App\Http\Controllers\Api\ProductController@store');
    Route::get('/products/{product}', 'App\Http\Controllers\Api\ProductController@show');
    Route::post('/products/{product}', 'App\Http\Controllers\Api\ProductController@update');
});
