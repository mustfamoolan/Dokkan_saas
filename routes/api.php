<?php

use Illuminate\Support\Facades\Route;

Route::post('/login', 'App\Http\Controllers\Api\AuthController@login');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', 'App\Http\Controllers\Api\AuthController@logout');
    Route::get('/employees', 'App\Http\Controllers\Api\EmployeeController@index');
    Route::post('/employees', 'App\Http\Controllers\Api\EmployeeController@store');
});
