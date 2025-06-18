<?php

use App\Http\Controllers\APIs\AuthController;
use App\Http\Controllers\APIs\MobileInvoiceController;
use App\Http\Controllers\APIs\ReportsController;
use App\Http\Controllers\APIs\TasksController;
use App\Http\Controllers\InsightsController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::put('/user/password', [AuthController::class, 'updatePassword']);

Route::middleware(['verify.api', 'auth:sanctum'])->group(function () {
    Route::get('/client-tasks', [TasksController::class, 'index']);
    Route::get('/cash-flow/{user?}', [InsightsController::class, 'cashFlow']);
    Route::get('/profit-and-loss/{user?}', [InsightsController::class, 'profitAndLoss']);

    Route::post('/bot/invoices/processed', [MobileInvoiceController::class, 'callback'])
        ->withoutMiddleware('auth:sanctum');
    Route::get('/invoices/failed', [MobileInvoiceController::class, 'failedInvoices']);
    Route::post('/invoices/failed/resent', [MobileInvoiceController::class, 'resentInvoice']);
    Route::resource('/invoices', MobileInvoiceController::class);

    Route::get('/user', [AuthController::class, 'user']);

    Route::get('/income-statement', [ReportsController::class, 'incomeStatement']);
    Route::get('/balance-sheet', [ReportsController::class, 'balanceSheet']);
});
