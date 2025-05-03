<?php

namespace App\Http\Controllers;

use App\Models\AccountGroup;
use App\Models\LedgerAccount;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LedgerAccountController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\View;
     */
    public function chartOfAccounts()
    {
        $accounts = LedgerAccount::with('accountGroup')->get();

        return view('ledger.coa', [
            'accounts' => $accounts
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function showAccount(LedgerAccount $ledgerAccount, User $user)
    {
        $data = Cache::remember('coa-' . $user->id . '-' . $ledgerAccount->id, 5, function () use ($user, $ledgerAccount) {
            return DB::table('ledger_entries')
                ->join('ledger_accounts', 'ledger_accounts.id', '=', 'ledger_entries.account_id')
                ->join('account_groups', 'account_groups.id', '=', 'ledger_accounts.account_group_id')
                ->join('entry_types', 'entry_types.id', '=', 'ledger_entries.entry_type_id')
                ->join('journal_entries', 'journal_entries.id', '=', 'ledger_entries.journal_entry_id')
                ->join('transaction_types', 'transaction_types.id', '=', 'journal_entries.transaction_type_id')
                ->join('users', 'journal_entries.client_id', '=', 'users.id')
                ->select(
                    'journal_entries.id as journal_id',
                    'journal_entries.description as journal_description',
                    'journal_entries.date as journal_date',
                    'transaction_types.name as transaction_type',
                    'users.name as client_name',
                    'users.email as client_email',
                    'ledger_accounts.name as acc_name',
                    'account_groups.name as acc_group',
                    DB::raw('CASE WHEN entry_types.name = "debit" THEN amount ELSE NULL END as debit'),
                    DB::raw('CASE WHEN entry_types.name = "credit" THEN amount ELSE NULL END as credit')
                )
                ->where('ledger_accounts.id', $ledgerAccount->id)
                ->where('journal_entries.client_id', $user->id)
                ->orderByRaw('journal_entries.date DESC')
                ->get();
        });

        $initialBalance = DB::table('accounts_opening_balance')
            ->join('users', 'users.id', '=', 'accounts_opening_balance.client_id')
            ->join('ledger_accounts', 'ledger_accounts.id', '=', 'accounts_opening_balance.ledger_account_id')
            ->join('entry_types', 'entry_types.id', '=', 'accounts_opening_balance.entry_type_id')
            ->select(
                'accounts_opening_balance.initial_balance',
                'entry_types.name as entry_type_name',
            )
            ->where('users.id', $user->id)
            ->where('ledger_accounts.id', $ledgerAccount->id)
            ->first();

        $totalDebits = 0;
        $totalCredits = 0;
        foreach ($data as $datum) {
            $credit = $datum->credit ?? 0;
            $debit = $datum->debit ?? 0;
            $totalCredits += $credit;
            $totalDebits += $debit;
        }

        $initial = is_null($initialBalance) ? 0 : $initialBalance->initial_balance;
        $entry = is_null($initialBalance) ? 'credit' : $initialBalance->entry_type_name;
        if ($entry === 'credit') {
            $totalCredits += $initial;
        } else {
            $totalDebits += $initial;
        }

        $overall = abs($totalCredits - $totalDebits);
        $unit = $totalDebits > $totalCredits ? 'Dr' : 'Cr';

        return view('ledger.show_acc', [
            'account' => $ledgerAccount,
            'user' => $user,
            'data' => $data,
            'total_debits' => $totalDebits,
            'total_credits' => $totalCredits,
            'initial_balance' => $initial,
            'entry_type' => is_null($initialBalance) ? 'credit' : $initialBalance->entry_type_name,
            'overall' => number_format($overall, 2),
        ]);
    }

    /*
     * @return \Illuminate\Http\RedirectResponse
     */
    public function setInitialBalance(Request $request, LedgerAccount $ledgerAccount, User $user)
    {
        $request->validate([
            'initial_balance' => ['required', 'numeric', 'min:0'],
            'entry_type_id' => ['required', 'numeric', 'min:1', 'max:2'],
        ]);

        try {
            DB::table('accounts_opening_balance')
                ->upsert(
                    [
                        'client_id' => $user->id,
                        'ledger_account_id' => $ledgerAccount->id,
                        'initial_balance' => $request->initial_balance,
                        'entry_type_id' => $request->entry_type_id,
                    ],
                    ['client_id', 'ledger_account_id'],
                    ['initial_balance', 'entry_type_id']
                );
        } catch (\Exception $e) {
            Log::warning('Error occured while updating initial balance of an account');
            return redirect()->back()->withInput()->withErrors([
                'database' => 'Failed to validate request: ' . $e->getMessage(),
            ]);
        }

        return redirect()->back();
    }
}
