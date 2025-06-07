<?php

use App\Http\Controllers\APIs\ReportsController;
use App\Http\Controllers\ApiAuthController;
use App\Http\Controllers\MobileInvoiceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\TwoFactorSecretKeyController;

Route::post('/login', [ApiAuthController::class, 'login']);
Route::put('/user/password', [ApiAuthController::class, 'updatePassword']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/invoices', [MobileInvoiceController::class, 'store']);

    Route::get('/two-factor-secret-key', [TwoFactorSecretKeyController::class, 'show'])
        ->middleware('throttle:6,1');

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/income-statement', [ReportsController::class, 'incomeStatement']);
    Route::get('/balance-sheet', [ReportsController::class, 'balanceSheet']);
});
