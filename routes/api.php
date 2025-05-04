<?php

use App\Http\Controllers\api\DashboardController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->group(function () {
    Route::prefix('dashboard')->group(function () {
        Route::get('/top-customer-sales', [DashboardController::class, 'index'])->name('dashboard.top-customer-sales');
        Route::get('/customer-trends', [DashboardController::class, 'getCustomerTrendsForChart'])->name('dashboard.customer-trends');
    });
});
