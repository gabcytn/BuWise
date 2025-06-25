<?php

use App\Http\Controllers\APIs\AuthController;
use App\Http\Controllers\APIs\MobileInvoiceController;
use App\Http\Controllers\APIs\ReportsController;
use App\Http\Controllers\APIs\TasksController;
use App\Http\Controllers\InsightsController;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\TwoFactorSecretKeyController;

Route::middleware(['verify.api', 'throttle:6,1'])->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::put('/user/password', [AuthController::class, 'updatePassword']);

    // invoices might be processed at bulk and might be blocked by rate limiter
    Route::post('/bot/invoices/processed', [MobileInvoiceController::class, 'callback'])
        ->withoutMiddleware('throttle:6,1');
});

Route::middleware(['verify.api', 'auth:sanctum', 'suspended'])->group(function () {
    Route::get('/client-tasks', [TasksController::class, 'index']);
    Route::get('/cash-flow/{user?}', [InsightsController::class, 'cashFlow']);
    Route::get('/profit-and-loss/{user?}', [InsightsController::class, 'profitAndLoss']);

    Route::get('/bookkeeper/clients', function (Request $request) {
        $user = $request->user();
        if ($user->role_id === Role::CLIENT)
            return Response::json([
                'message' => 'You are unauthorized to access this resource',
            ], 403);
        $accountant_id = getAccountantId($user);
        $clients = Cache::remember("$accountant_id-clients", 3600, function () use ($user) {
            return getClients($user);
        });

        return Response::json([
            'clients' => $clients->map(function ($client) {
                return [
                    'name' => $client->name,
                    'category' => $client->client_type,
                ];
            }),
        ]);
    });

    Route::get('/invoices/failed', [MobileInvoiceController::class, 'failedInvoices']);
    Route::post('/invoices/failed/resent', [MobileInvoiceController::class, 'resentInvoice']);
    Route::get('/invoices', [MobileInvoiceController::class, 'index']);
    Route::post('/invoices', [MobileInvoiceController::class, 'store']);

    Route::get('/user', [AuthController::class, 'user']);

    Route::get('/income-statement', [ReportsController::class, 'incomeStatement']);
    Route::get('/balance-sheet', [ReportsController::class, 'balanceSheet']);

    Route::get('/two-factor-secret-key', [TwoFactorSecretKeyController::class, 'show'])
        ->middleware('throttle:1,1');

    Route::get('/user/suspended', function (Request $request) {
        if (!$request->user()->suspended)
            return response(null, 404);
        return Response::json([
            'message' => 'Your account has been suspended by your accountant',
        ], 403);
    })->name('api.suspended')->withoutMiddleware('suspended');
});
