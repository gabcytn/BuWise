<?php

namespace App\Http\Controllers;

use App\Models\AccountGroup;
use App\Models\LedgerAccount;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class TrialBalanceController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('trialBalance', LedgerAccount::class);
        $user = $request->user();
        $accId = getAccountantId($user);
        $clients = Cache::remember($accId . '-clients', 3600, function () use ($user) {
            return getClients($user);
        });

        if (!$request->query('client')) {
            return view('ledger.trial-balance', [
                'clients' => $clients,
                'data' => null,
            ]);
        }
        $client = User::find($request->client);
        if (!$client)
            abort(404);
        if ($request->query('start_date') && $request->query('end_date')) {
            $request->validate([
                'start_date' => ['date', 'after_or_equal:1970-01-01', 'before_or_equal:2999-12-31'],
                'end_date' => ['date', 'after_or_equal:1970-01-01', 'before_or_equal:2999-12-31'],
            ]);
            $data = $this->getQuery($request->client, $request->query('start_date'), $request->query('end_date'));
        } else if ($request->query('period')) {
            $request->validate([
                'period' => 'in:this_year,this_month,this_week,last_week,last_month,last_year,all_time',
            ]);
            $period = getStartAndEndDate($request->period);
            $data = $this->getQuery($request->client, $period[0], $period[1]);
        }
        return view('ledger.trial-balance', [
            'clients' => $clients,
            'data' => $data,
        ]);
    }

    private function getQuery($clientId, $startDate = '1970-01-01', $endDate = '9999-12-31')
    {
        return DB::table('ledger_entries AS le')
            ->join('transactions AS tr', 'tr.id', '=', 'le.transaction_id')
            ->join('users', 'users.id', '=', 'tr.client_id')
            ->join('ledger_accounts AS acc', 'acc.id', '=', 'le.account_id')
            ->where('tr.client_id', '=', $clientId)
            ->whereIn('tr.status', ['approved', 'archived'])
            ->where(function ($query) use ($startDate, $endDate) {
                $query->where(function ($q) use ($startDate, $endDate) {
                    $q
                        ->whereIn('acc.account_group_id', [AccountGroup::ASSETS, AccountGroup::LIABILITIES, AccountGroup::EQUITY])
                        ->whereBetween('tr.date', [$startDate, $endDate]);
                })->orWhere(function ($q) use ($startDate, $endDate) {
                    $q
                        ->whereNotIn('acc.account_group_id', [AccountGroup::ASSETS, AccountGroup::LIABILITIES, AccountGroup::EQUITY])
                        ->whereBetween('tr.date', [$startDate, $endDate]);
                });
            })
            ->groupBy('acc.id', 'acc.name', 'acc.account_group_id')
            ->orderBy('acc.id')
            ->select(
                'acc.code AS acc_id',
                'acc.name AS acc_name',
                'acc.account_group_id AS acc_group_id',
                DB::raw("SUM(CASE WHEN le.entry_type = 'debit' THEN le.amount ELSE 0 END) AS debit"),
                DB::raw("SUM(CASE WHEN le.entry_type = 'credit' THEN le.amount ELSE 0 END) AS credit")
            )
            ->get();
    }
}
