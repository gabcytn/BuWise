<?php

use App\Http\Controllers\MobileInvoiceController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Http\Controllers\TwoFactorSecretKeyController;

Route::post('/login', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
        'device_name' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }

    return $user->createToken($request->device_name)->plainTextToken;
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/invoices', [MobileInvoiceController::class, 'store']);

    Route::get('/two-factor-secret-key', [TwoFactorSecretKeyController::class, 'show'])
        ->middleware('throttle:6,1');

    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});

Route::put('/user/password', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'old_password' => 'required|string|min:8',
        'new_password' => 'required|string|min:8',
    ]);

    $user = User::where('email', $request->email)->first();
    if (!$user || !Hash::check($request->old_password, $user->password)) {
        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }

    $user->password = $request->new_password;
    $user->save();

    return Response::json([
        'message' => 'Successfully changed password'
    ]);
});
