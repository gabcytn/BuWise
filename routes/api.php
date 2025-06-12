<?php

use App\Http\Controllers\APIs\AuthController;
use App\Http\Controllers\APIs\MobileInvoiceController;
use App\Http\Controllers\APIs\ReportsController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::put('/user/password', [AuthController::class, 'updatePassword']);

Route::middleware('auth:sanctum')->group(function () {
    Route::resource('/invoices', MobileInvoiceController::class);

    Route::get('/user', [AuthController::class, 'user']);

    Route::get('/income-statement', [ReportsController::class, 'incomeStatement']);
    Route::get('/balance-sheet', [ReportsController::class, 'balanceSheet']);
});
