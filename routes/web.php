<?php

use App\Http\Controllers\BalanceSheetController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\IncomeStatementController;
use App\Http\Controllers\InsightsController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\JournalEntryController;
use App\Http\Controllers\LedgerAccountController;
use App\Http\Controllers\ProfileInformationController;
use App\Http\Controllers\TrialBalanceController;
use App\Http\Controllers\WorkingPaperController;
use App\Http\Middleware\EnableMFA;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
    Route::get('/journal-entries/archives', [JournalEntryController::class, 'archives'])->name('journal-entries.archives');
    Route::resource('/journal-entries', JournalEntryController::class);
    Route::post('/journal-entries/csv', [JournalEntryController::class, 'csv']);
    Route::post('/journal-entries/{journalEntry}/approve', [JournalEntryController::class, 'approve'])
        ->name('journal-entries.approve');
    Route::post('/journal-entries/{journalEntry}/reject', [JournalEntryController::class, 'reject'])
        ->name('journal-entries.reject');

    // ledger routes
    Route::get('/ledger/chart-of-accounts', [LedgerAccountController::class, 'chartOfAccounts'])
        ->name('ledger.coa');
    Route::post('/ledger/chart-of-accounts', [LedgerAccountController::class, 'createAccount'])
        ->name('ledger.create-account');
    Route::get('/ledger/chart-of-accounts/{ledgerAccount}/{user}', [LedgerAccountController::class, 'showAccount'])
        ->name('ledger.coa.show');
    Route::get('/ledger/trial-balance', [TrialBalanceController::class, 'index'])
        ->name('ledger.trial-balance');
    Route::post('/ledger/chart-of-accounts/{ledgerAccount}/{user}', [LedgerAccountController::class, 'setInitialBalance'])
        ->name('ledger.coa.update_initial');

    // invoice routes
    Route::resource('/invoices', InvoiceController::class);

    // reports
    Route::get('/reports/balance-sheet', [BalanceSheetController::class, 'index'])->name('reports.balance-sheet');
    Route::get('/reports/income-statement', [IncomeStatementController::class, 'index'])->name('reports.income-statement');
    Route::get('/reports/working-paper', [WorkingPaperController::class, 'index'])->name('reports.working-paper');
    Route::get('/reports/insights', [InsightsController::class, 'index'])->name('reports.insights');

    Route::get('/cash-flow/{user}', function (User $user) {
        $data = DB::table('ledger_entries AS le')
            ->join('transactions AS tr', 'tr.id', '=', 'le.transaction_id')
            ->join('users', 'users.id', '=', 'tr.client_id')
            ->join('ledger_accounts AS acc', 'acc.id', '=', 'le.account_id')
            ->join('account_groups AS acc_group', 'acc_group.id', '=', 'acc.account_group_id')
            ->whereIn('le.account_id', [1, 2, 3, 4])
            ->where('users.id', '=', $user->id)
            ->select(
                'acc.code',
                'acc.name AS acc_name',
                'acc_group.name AS acc_group',
                'tr.date',
                'tr.kind',
                'le.amount',
                'le.entry_type',
            )
            ->orderBy('tr.date')
            ->get();
        return json_decode(json_encode($data));
    });

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
