<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['auth:sanctum', 'admin'])->group(function (){
    Route::get('/user', [\App\Http\Controllers\AuthController::class, 'getUser']);
    Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout']);

    Route::apiResource('products', \App\Http\Controllers\Api\ProductController::class);
    Route::get('orders', [\App\Http\Controllers\Api\OrderContoller::class, 'index']);
    Route::get('orders/statuses', [\App\Http\Controllers\Api\OrderContoller::class, 'getStatuses']);
    Route::get('orders/change-status/{order}/{status}', [\App\Http\Controllers\Api\OrderContoller::class, 'changeStatus']);
    Route::get('orders/{order}', [\App\Http\Controllers\Api\OrderContoller::class, 'view']);
});

Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);
