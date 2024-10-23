<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::POST('/login', [LoginController::class, 'login'])->name('login');

Route::resource('products', ProductController::class)->only([
    'index',
]);

Route::middleware('auth:sanctum')->group(function () {
    Route::resource('orders', ProductController::class)->only([
        'store',
        'show'
    ]);
});