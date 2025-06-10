<?php

use App\Http\Controllers\APIs\AuthController;
use App\Http\Controllers\APIs\MobileInvoiceController;
use App\Http\Controllers\APIs\ReportsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\TwoFactorSecretKeyController;

Route::post('/login', [AuthController::class, 'login']);
Route::put('/user/password', [AuthController::class, 'updatePassword']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/invoices', [MobileInvoiceController::class, 'store']);

    Route::get('/two-factor-secret-key', [TwoFactorSecretKeyController::class, 'show'])
        ->middleware('throttle:6,1');

    Route::get('/user', [AuthController::class, 'user']);

    Route::get('/income-statement', [ReportsController::class, 'incomeStatement']);
    Route::get('/balance-sheet', [ReportsController::class, 'balanceSheet']);
});
