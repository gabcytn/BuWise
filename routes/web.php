<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\JournalEntryController;
use App\Http\Controllers\LedgerAccountController;
use App\Http\Controllers\ProfileInformationController;
use App\Http\Controllers\TrialBalanceController;
use App\Http\Middleware\EnableMFA;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\StaffController;

Route::get('/', function () {
    return view('welcome');
})->name('home');
Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/services', function () {
    return view('services');
})->name('services');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified', EnableMFA::class])->name('dashboard');

Route::middleware(['auth', 'verified', EnableMFA::class])->group(function () {
    Route::get('/profile', function (Request $request) {
        return view('profile.edit', [
            'user' => $request->user()
        ]);
    })->name('profile.edit');

    Route::put('/user/profile-information', [ProfileInformationController::class, 'update'])
        ->name('profile.update');

    // client related routes
    Route::resource('/clients', ClientController::class)
        ->only(['index', 'store', 'edit', 'update', 'destroy']);

    // staff related routes
    Route::resource('/staff', StaffController::class)
        ->only(['index', 'store', 'edit', 'update', 'destroy']);

    // journal entries
    Route::resource('/journal-entries', JournalEntryController::class);
    Route::post('/journal-entries/{journalEntry}/approve', [JournalEntryController::class, 'approve'])
        ->name('journal-entries.approve');

    // ledger routes
    Route::get('/ledger/chart-of-accounts', [LedgerAccountController::class, 'chartOfAccounts'])
        ->name('ledger.coa');
    Route::get('/ledger/chart-of-accounts/{ledgerAccount}/{user}', [LedgerAccountController::class, 'showAccount'])
        ->name('ledger.coa.show');
    Route::get('/ledger/trial-balance', [TrialBalanceController::class, 'index'])
        ->name('ledger.trial-balance');
    Route::post('/ledger/chart-of-accounts/{ledgerAccount}/{user}', [LedgerAccountController::class, 'setInitialBalance'])
        ->name('ledger.coa.update_initial');

    // invoice routes
    Route::resource('/invoices', InvoiceController::class)
        ->only(['index']);

    Route::get('/enable-2fa', function (Request $request) {
        if ($request->user()->two_factor_confirmed_at && session('status') !== 'two-factor-authentication-confirmed') {
            return to_route('dashboard');
        }
        return view('auth.enable-mfa');
    })->name('mfa.enable')->withoutMiddleware(EnableMFA::class);
});

// allow email verification without signing in
Route::get('/email/verify/{id}/{hash}', function (Request $request) {
    $user = User::find($request->route('id'));

    if (!hash_equals((string) $request->route('hash'), sha1($user->getEmailForVerification()))) {
        throw new AuthorizationException;
    }

    if ($user->markEmailAsVerified())
        event(new Verified($user));

    return to_route('dashboard');
})->middleware(['signed', 'throttle:6,1'])->name('verification.verify');

// require __DIR__ . '/auth.php';
