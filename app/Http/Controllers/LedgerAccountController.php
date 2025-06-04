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
use Illuminate\Support\Facades\Log;
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
            ->get();

        return view('ledger.coa', [
            'clients' => $clients,
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
            'period' => 'in:this_year,this_month,this_week,last_week,last_month,last_year,all_time',
        ]);

        $start = $request->query('start') ?: null;
        $end = $request->query('end') ?: null;

        if ($request->query('period')) {
            $period = $this->getStartAndEndDate($request->period);
            $start = $period[0];
            $end = $period[1];
            $data = $this->getQuery($ledgerAccount->id, $user->id, $end);
        } else if ($start && $end) {
            $data = $this->getQuery($ledgerAccount->id, $user->id, $end);
        } else if (!$start && !$end) {
            $period = $this->getStartAndEndDate('this_year');
            $start = $period[0];
            $end = $period[1];
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
    private function calculateTotalDebitsAndCredits($data, $start, $end)
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
            $totalDebits += $openingBalance;
        } else {
            $openingBalance = $openingCredits - $openingDebits;
            $openingEntry = 'credit';
            $totalCredits += $openingBalance;
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
        // ->groupBy('transactions.id', 'transactions.description', 'transactions.date', 'transactions.kind', 'users.name', 'users.email', 'ledger_accounts.name', 'account_groups.id', 'account_groups.name', 'le.entry_type', 'le.amount');

        if ($endDate !== null) {
            $query = $query
                ->where('transactions.date', '<=', $endDate);
        }
        return $query
            ->orderByRaw('transactions.date DESC')
            ->get();
    }

    private function getStartAndEndDate(string $period): array
    {
        switch ($period) {
            case 'all_time':
                $start = Carbon::now()->subMillennium();
                $end = Carbon::now()->endOfMillennium();
                break;
            case 'this_month':
                $start = Carbon::now()->startOfMonth();
                $end = Carbon::now()->endOfMonth();
                break;
            case 'this_week':
                $start = Carbon::now()->startOfWeek(Carbon::SUNDAY);
                $end = Carbon::now()->endOfWeek(Carbon::SATURDAY);
                break;
            case 'last_week':
                $start = Carbon::now()->subWeek()->startOfWeek(Carbon::SUNDAY);
                $end = Carbon::now()->subWeek()->endOfWeek(Carbon::SATURDAY);
                break;
            case 'last_month':
                $start = Carbon::now()->subMonthsNoOverflow()->startOfMonth();
                $end = Carbon::now()->subMonthsNoOverflow()->endOfMonth();
                break;
            case 'last_year':
                $start = Carbon::now()->subYear()->startOfYear();
                $end = Carbon::now()->subYear()->endOfYear();
                break;
            default:
                $start = Carbon::now()->startOfYear();
                $end = Carbon::now()->endOfYear();
                break;
        }
        return [$start->format('Y-m-d'), $end->format('Y-m-d')];
    }

    public function getOpeningBalanceForAudit(int $accountId, string $clientId)
    {
        $endDate = Carbon::now()->subYear()->endOfYear()->toDateString();
        $res = $this->getQuery($accountId, $clientId, $endDate);
        $res = $this->calculateTotalDebitsAndCredits($res, '1970-01-01', $endDate);
        return $res;
    }
}
