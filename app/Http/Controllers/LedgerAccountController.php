<?php

namespace App\Http\Controllers;

use App\Models\AccountGroup;
use App\Models\LedgerAccount;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class LedgerAccountController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\View;
     */
    public function chartOfAccounts(Request $request)
    {
        Gate::authorize('chartOfAccounts', LedgerAccount::class);

        $user = $request->user();
        $accId = getAccountantId($user);
        $clients = Cache::remember($accId . '-clients', 3600, function () use ($user) {
            return getClients($user);
        });
        $accounts = LedgerAccount::with('accountGroup')
            ->where('accountant_id', $accId)
            ->orWhere('accountant_id', null)
            ->orderBy('code')
            ->get();
        $accountGroups = AccountGroup::all();

        return view('ledger.coa', [
            'clients' => $clients,
            'accounts' => $accounts,
            'accountGroups' => $accountGroups,
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
            'period' => 'in:this_year,this_month,this_week,last_week,last_month,last_year,all_time',
        ]);

        $start = $request->query('start') ?: null;
        $end = $request->query('end') ?: null;

        if ($request->query('period')) {
            $period = getStartAndEndDate($request->period);
            $start = $period[0]->format('Y-m-d');
            $end = $period[1]->format('Y-m-d');
            $data = $this->getQuery($ledgerAccount->id, $user->id, $end);
        } else if ($start && $end) {
            $data = $this->getQuery($ledgerAccount->id, $user->id, $end);
        } else if (!$start && !$end) {
            $period = getStartAndEndDate('this_year');
            $start = $period[0]->format('Y-m-d');
            $end = $period[1]->format('Y-m-d');
            $data = $this->getQuery($ledgerAccount->id, $user->id, $end);
        }

        $arr = $this->calculateTotalDebitsAndCredits($data, $start, $end);

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
            'overall' => number_format($overall, 2),
            'start' => $start,
            'end' => $end,
        ]);
    }

    /*
     * @return array
     */
    private function calculateTotalDebitsAndCredits($data, string $start, string $end)
    {
        $openingDebits = 0;
        $openingCredits = 0;

        $totalDebits = 0;
        $totalCredits = 0;

        foreach ($data as $datum) {
            $credit = $datum->credit ?? 0;
            $debit = $datum->debit ?? 0;
            if ($datum->transaction_date < $start && AccountGroup::IS_PERMANENT[$datum->acc_group_id]) {
                $openingCredits += $credit;
                $openingDebits += $debit;
            } elseif ($datum->transaction_date >= $start && $datum->transaction_date <= $end) {
                $totalCredits += $credit;
                $totalDebits += $debit;
            }
        }

        if ($openingDebits > $openingCredits) {
            $openingBalance = $openingDebits - $openingCredits;
            $openingEntry = 'debit';
        } else {
            $openingBalance = $openingCredits - $openingDebits;
            $openingEntry = 'credit';
        }

        return [$totalDebits, $totalCredits, $openingBalance, $openingEntry];
    }

    private function getQuery(int $ledgerAccountId, string $userId, ?string $endDate = null)
    {
        $query = DB::table('ledger_entries AS le')
            ->join('ledger_accounts', 'ledger_accounts.id', '=', 'le.account_id')
            ->join('account_groups', 'account_groups.id', '=', 'ledger_accounts.account_group_id')
            ->join('transactions', 'transactions.id', '=', 'le.transaction_id')
            ->join('users', 'transactions.client_id', '=', 'users.id')
            ->select(
                'transactions.id as transaction_id',
                'transactions.description as transaction_description',
                'transactions.date as transaction_date',
                'transactions.kind as transaction_type',
                'users.name as client_name',
                'users.email as client_email',
                'ledger_accounts.name as acc_name',
                'account_groups.name as acc_group',
                'account_groups.id as acc_group_id',
                DB::raw('CASE WHEN le.entry_type = "debit" THEN le.amount ELSE NULL END as debit'),
                DB::raw('CASE WHEN le.entry_type = "credit" THEN le.amount ELSE NULL END as credit')
            )
            ->where('ledger_accounts.id', '=', $ledgerAccountId)
            ->whereIn('transactions.status', ['approved', 'archived'])
            ->where('transactions.client_id', $userId);

        if ($endDate !== null) {
            $query = $query
                ->where('transactions.date', '<=', $endDate);
        }
        return $query
            ->orderByRaw('transactions.date DESC')
            ->get();
    }

    public function getOpeningBalanceForAudit(int $accountId, string $clientId)
    {
        $endDate = Carbon::now()->subYear()->endOfYear()->toDateString();
        $res = $this->getQuery($accountId, $clientId, $endDate);
        $res = $this->calculateTotalDebitsAndCredits($res, '1970-01-01', $endDate);
        return $res;
    }

    public function createAccount(Request $request)
    {
        $request->validate([
            'account_type' => 'required|in:1,2,3,4,5',
            'account_code' => 'required|numeric',
            'account_name' => 'required|string|max:100',
            'account_description' => 'nullable|string|max:255',
        ]);

        $user = $request->user();
        $accountant_id = getAccountantId($user);

        if (!str_starts_with($request->account_code, $request->account_type))
            return redirect()->back()->withErrors(['error' => 'Account code prefix is incorrect']);

        $account = LedgerAccount::where('code', '=', $request->account_code)
            ->where('accountant_id', '=', $accountant_id)
            ->orWhere('accountant_id', '=', null)
            ->first();

        if ($account)
            return redirect()->back()->withErrors(['error' => 'This account code already exists']);

        LedgerAccount::create([
            'code' => $request->account_code,
            'account_group_id' => $request->account_type,
            'accountant_id' => $accountant_id,
            'name' => $request->account_name
        ]);

        return redirect()->back()->with(['status' => 'Account created successfully!']);
    }
}
