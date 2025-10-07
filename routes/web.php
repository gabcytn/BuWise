<?php

use App\Exports\DataExport;
use App\Http\Controllers\BalanceSheetController;
use App\Http\Controllers\BinController;
use App\Http\Controllers\BotController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ContacUsController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FailedInvoiceController;
use App\Http\Controllers\IncomeStatementController;
use App\Http\Controllers\InsightsController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\JournalEntryController;
use App\Http\Controllers\LedgerAccountController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\ProfileInformationController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TrialBalanceController;
use App\Http\Controllers\WorkingPaperController;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;
use \App\Http\Controllers\StaffController;

Route::get('/privacy', function () {
    return view('privacy');
})->name('');
Route::get('/contact', function () {
    return view('contact');
})->name('');
Route::post('/contact', [ContacUsController::class, 'index'])->middleware('throttle:1,1');
Route::get('/', function () {
    return view('welcome');
})->name('home');
Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/services', function () {
    return view('services');
})->name('services');

Route::middleware(['auth', 'verified', 'suspended', 'enable.mfa', 'onboarding'])->group(function () {
    Route::get('/user/details', function (Request $request) {
        $user = $request->user();
        return Cache::remember($user->id . '-details', 3600, function () use ($user) {
            return User::with('role')->find($user->id);
        });
    });

    Route::delete('/user', function (Request $request) {
        $request->user()->delete();
        return to_route('login');
    })->name('user.delete');

    Route::put('/user/default-password', [ProfileInformationController::class, 'updateDefaultPassword'])
        ->name('default-password.update');

    Route::get('/suspended', function (Request $request) {
        if ($request->user()->suspended)
            return 'Your account has been suspended. If this is a mistake, please contact your accountant';
        return to_route('dashboard');
    })->name('web.suspended')->withoutMiddleware('suspended');

    Route::resource('/organizations', OrganizationController::class)
        ->withoutMiddleware('onboarding')
        ->only(['create', 'store']);

    Route::get('/bin', [BinController::class, 'index'])->name('bin');
    Route::post('/bin/restore', [BinController::class, 'restore']);
    Route::post('/bin/delete', [BinController::class, 'delete']);
    Route::get('/data/download', function (Request $request) {
        return Excel::download(new DataExport($request->user()->id), 'data.xlsx');
    })->name('data.download');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', function (Request $request) {
        $user = $request->user();
        if (!isAuthorized($user))
            abort(404);
        return view('profile.edit', [
            'user' => $user,
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

    Route::post('/suspend/{user}', function (User $user) {
        $user->suspended = !$user->suspended;
        $user->save();
        return back();
    })->name('user.suspend');

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
    Route::delete('/ledger/chart-of-accounts/{ledgerAccount}', [LedgerAccountController::class, 'deleteAccount'])
        ->name('ledger.delete-account');
    Route::get('/ledger/chart-of-accounts/{ledgerAccount}/{user}', [LedgerAccountController::class, 'showAccount'])
        ->name('ledger.coa.show');
    Route::get('/ledger/trial-balance', [TrialBalanceController::class, 'index'])
        ->name('ledger.trial-balance');

    Route::resource('/invoices/failed', FailedInvoiceController::class)
        ->only(['index', 'destroy']);

    // invoice routes
    Route::post('/invoices/scan', [InvoiceController::class, 'scan'])->name('invoices.scan');
    Route::resource('/invoices', InvoiceController::class);

    // reports
    Route::get('/reports/balance-sheet', [BalanceSheetController::class, 'index'])->name('reports.balance-sheet');
    Route::get('/reports/balance-sheet/csv/{client}', [BalanceSheetController::class, 'csv'])->name('reports.balance-sheet.csv');
    Route::get('/reports/income-statement', [IncomeStatementController::class, 'index'])->name('reports.income-statement');
    Route::get('/reports/income-statement/csv/{client}', [IncomeStatementController::class, 'csv'])->name('reports.income-statement.csv');
    Route::get('/reports/working-paper', [WorkingPaperController::class, 'index'])->name('reports.working-paper');
    Route::get('/reports/insights', [InsightsController::class, 'index'])->name('reports.insights');

    // insights
    Route::get('/cash-flow/{user}', [InsightsController::class, 'cashFlow']);
    Route::get('/receivables/{user}', [InsightsController::class, 'receivables']);
    Route::get('/payables/{user}', [InsightsController::class, 'payables']);
    Route::get('/profit-and-loss/{user}', [InsightsController::class, 'profitAndLoss']);

    // calendar
    Route::get('/tasks/todo', [TaskController::class, 'todo'])->name('tasks.todo');
    Route::post('/tasks/status/{task}', [TaskController::class, 'changeStatus']);
    Route::resource('/tasks', TaskController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::get('/api/tasks', [TaskController::class, 'assignedTasks']);

    // notifications
    Route::get('/api/notifications', [NotificationController::class, 'notifications']);
    Route::resource('/notifications', NotificationController::class)->only(['destroy']);

    Route::get('/bot/invoices/create', [BotController::class, 'index'])
        ->name('bot-invoices.create');

    Route::get('/enable-2fa', function (Request $request) {
        if ($request->user()->two_factor_confirmed_at && session('status') !== 'two-factor-authentication-confirmed') {
            return to_route('dashboard');
        }
        return view('auth.enable-mfa');
    })->name('mfa.enable')->withoutMiddleware(['enable.mfa', 'onboarding']);

    // chats/conversations
    Route::resource('/conversations/{conversation}/messages', MessageController::class)
        ->only('index', 'store', 'destroy');
    Route::resource('/conversations', ConversationController::class)
        ->only('index');
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

require __DIR__ . '/oauth.php';
