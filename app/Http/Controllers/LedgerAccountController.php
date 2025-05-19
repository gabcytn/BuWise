<?php

namespace App\Http\Controllers;

use App\Models\AccountGroup;
use App\Models\EntryType;
use App\Models\LedgerAccount;
use App\Models\Status;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class LedgerAccountController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\View;
     */
    public function chartOfAccounts()
    {
        Gate::authorize('chartOfAccounts', LedgerAccount::class);
        $accounts = LedgerAccount::with('accountGroup')->get();

        return view('ledger.coa', [
            'accounts' => $accounts
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function showAccount(Request $request, LedgerAccount $ledgerAccount, User $user)
    {
        Gate::authorize('showAccount', LedgerAccount::class);
        $request->validate([
            'start' => [Rule::date()->format('Y-m-d')],
            'end' => [Rule::date()->format('Y-m-d')],
        ]);

        $defaultStart = new \DateTime('@0')->format('Y-m-d H:i:s');
        $start = $request->query('start') ? $request->query('start') . ' 00:00:00' : $defaultStart;

        $defaultEnd = new \DateTime('9999-12-31 23:59:59')->format('Y-m-d H:i:s');
        $end = $request->query('end') ? $request->query('end') . ' 23:59:59' : $defaultEnd;

        if ($request->query('start') && $request->query('end')) {
            Log::info('Request has a custom date: calculating...');
            $data = $this->getQuery($ledgerAccount->id, $user->id, $end);
        } else {
            $data = Cache::remember('coa-' . $user->id . '-' . $ledgerAccount->id, 3600, function () use ($user, $ledgerAccount) {
                Log::info('Recalculating COA cache...');
                return $this->getQuery($ledgerAccount->id, $user->id);
            });
        }

        $initialBalance = LedgerAccountController::getInitialBalance($user->id, $ledgerAccount->id, $ledgerAccount->account_group_id);
        $arr = $this->calculateTotalDebitsAndCredits($data, $initialBalance, $start, $end);

        $totalDebits = $arr[0];
        $totalCredits = $arr[1];
        $openingBalance = $arr[2];
        $openingEntry = $arr[3];

        $overall = abs($totalCredits - $totalDebits);

        return view('ledger.show_acc', [
            'account' => $ledgerAccount,
            'user' => $user,
            'data' => $data,
            'total_debits' => $totalDebits,
            'total_credits' => $totalCredits,
            'opening_balance' => $openingBalance,
            'opening_entry_type' => $openingEntry,
            'initial_balance' => is_null($initialBalance) ? 0 : $initialBalance->initial_balance,
            'overall' => number_format($overall, 2),
            'start' => $start,
            'end' => $end,
        ]);
    }

    /*
     * @return \Illuminate\Http\RedirectResponse
     */
    public function setInitialBalance(Request $request, LedgerAccount $ledgerAccount, User $user)
    {
        Gate::authorize('setInitialBalance', $ledgerAccount);
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
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    ['client_id', 'ledger_account_id'],
                    ['initial_balance', 'entry_type_id', 'updated_at']
                );
        } catch (\Exception $e) {
            Log::warning('Error occured while updating initial balance of an account');
            return redirect()->back()->withInput()->withErrors([
                'database' => 'Failed to validate request: ' . $e->getMessage(),
            ]);
        }

        return redirect()->back();
    }

    /*
     * @return ?Illuminate\Database\Concerns\TValue;
     */
    public static function getInitialBalance(string $userId, int $ledgerAccountId, int $accountGroupId)
    {
        if (AccountGroup::IS_TEMPORARY[$accountGroupId]) {
            return null;
        }
        return DB::table('accounts_opening_balance')
            ->join('users', 'users.id', '=', 'accounts_opening_balance.client_id')
            ->join('ledger_accounts', 'ledger_accounts.id', '=', 'accounts_opening_balance.ledger_account_id')
            ->select(
                'accounts_opening_balance.initial_balance',
                'accounts_opening_balance.entry_type_id'
            )
            ->where('users.id', $userId)
            ->where('ledger_accounts.id', $ledgerAccountId)
            ->first();
    }

    /*
     * @return array
     */
    private function calculateTotalDebitsAndCredits($data, $initialBalance, $start, $end)
    {
        $openingDebits = 0;
        $openingCredits = 0;

        $totalDebits = 0;
        $totalCredits = 0;

        foreach ($data as $datum) {
            $credit = $datum->credit ?? 0;
            $debit = $datum->debit ?? 0;
            if ($datum->journal_date < $start && AccountGroup::IS_PERMANENT[$datum->acc_group_id]) {
                $openingCredits += $credit;
                $openingDebits += $debit;
            } elseif ($datum->journal_date >= $start && $datum->journal_date <= $end) {
                $totalCredits += $credit;
                $totalDebits += $debit;
            }
        }

        $veryInitial = is_null($initialBalance) ? 0 : $initialBalance->initial_balance;
        $initialEntry = is_null($initialBalance) ? EntryType::CREDIT : $initialBalance->entry_type_id;

        if ($initialEntry === EntryType::CREDIT) {
            $openingCredits += $veryInitial;
        } elseif ($initialEntry === EntryType::DEBIT) {
            $openingDebits += $veryInitial;
        }

        if ($openingDebits > $openingCredits) {
            $openingBalance = $openingDebits - $openingCredits;
            $openingEntry = EntryType::DEBIT;
            $totalDebits += $openingBalance;
        } else {
            $openingBalance = $openingCredits - $openingDebits;
            $openingEntry = EntryType::CREDIT;
            $totalCredits += $openingBalance;
        }

        return [$totalDebits, $totalCredits, $openingBalance, $openingEntry];
    }

    private function getQuery(int $ledgerAccountId, string $userId, ?string $endDate = null)
    {
        $query = DB::table('ledger_entries')
            ->join('ledger_accounts', 'ledger_accounts.id', '=', 'ledger_entries.account_id')
            ->join('account_groups', 'account_groups.id', '=', 'ledger_accounts.account_group_id')
            ->join('entry_types', 'entry_types.id', '=', 'ledger_entries.entry_type_id')
            ->join('journal_entries', 'journal_entries.id', '=', 'ledger_entries.journal_entry_id')
            ->join('transaction_types', 'transaction_types.id', '=', 'journal_entries.transaction_type_id')
            ->join('users', 'journal_entries.client_id', '=', 'users.id')
            ->join('status', 'status.id', '=', 'journal_entries.status_id')
            ->select(
                'journal_entries.id as journal_id',
                'journal_entries.description as journal_description',
                'journal_entries.date as journal_date',
                'transaction_types.name as transaction_type',
                'users.name as client_name',
                'users.email as client_email',
                'ledger_accounts.name as acc_name',
                'account_groups.name as acc_group',
                'account_groups.id as acc_group_id',
                DB::raw('CASE WHEN entry_types.name = "debit" THEN amount ELSE NULL END as debit'),
                DB::raw('CASE WHEN entry_types.name = "credit" THEN amount ELSE NULL END as credit')
            )
            ->where('ledger_accounts.id', $ledgerAccountId)
            ->where('journal_entries.status_id', '=', Status::APPROVED)
            ->where('journal_entries.client_id', $userId);

        if ($endDate !== null) {
            $query = $query
                ->where('journal_entries.date', '<=', $endDate);
        }
        return $query
            ->orderByRaw('journal_entries.date DESC')
            ->get();
    }
}
