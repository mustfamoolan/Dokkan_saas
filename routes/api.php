<?php

use Illuminate\Support\Facades\Route;

Route::post('/login', 'App\Http\Controllers\Api\AuthController@login');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', 'App\Http\Controllers\Api\AuthController@logout');
    Route::get('/employees', 'App\Http\Controllers\Api\EmployeeController@index');
    Route::post('/employees', 'App\Http\Controllers\Api\EmployeeController@store');
    Route::get('/employees/{employee}', 'App\Http\Controllers\Api\EmployeeController@show');
    Route::post('/employees/{employee}', 'App\Http\Controllers\Api\EmployeeController@update');

    // Suppliers
    Route::get('/suppliers', 'App\Http\Controllers\Api\SupplierController@index');
    Route::post('/suppliers', 'App\Http\Controllers\Api\SupplierController@store');
    Route::put('/suppliers/{id}', 'App\Http\Controllers\Api\SupplierController@update');
});
