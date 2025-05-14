<?php

namespace App\Http\Controllers;

use App\Models\AccountGroup;
use App\Models\EntryType;
use App\Models\LedgerAccount;
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
        $clients = Cache::remember($user->id . '-clients', $ttl = 3600, function () use ($user) {
            return getClients($user);
        });

        // TODO: cache the db results
        if ($request->query('client')) {
            $request->validate([
                'client' => ['uuid:4']
            ]);
            if ($request->query('start_date') && $request->query('end_date')) {
                $request->validate([
                    'start_date' => ['date', 'after_or_equal:1970-01-01', 'before_or_equal:2999-12-31'],
                    'end_date' => ['date', 'after_or_equal:1970-01-01', 'before_or_equal:2999-12-31'],
                ]);
                $data = $this->getQuery($request->query('client'), $request->query('start_date'), $request->query('end_date'));
            } else {
                $data = $this->getQuery($request->query('client'));
            }

            foreach ($data as $datum) {
                $openingBalance = LedgerAccountController::getInitialBalance($request->query('client'), $datum->acc_id, $datum->acc_group_id);
                if ($openingBalance) {
                    $openingBalance->entry_type_id == EntryType::DEBIT ? $datum->debit += $openingBalance->initial_balance : $datum->credit += $openingBalance->initial_balance;
                }
            }
        }

        return view('ledger.trial-balance', [
            'clients' => $clients,
            'data' => $data ?? null,
        ]);
    }

    private function getQuery($clientId, $startDate = '1970-01-01', $endDate = '9999-12-31')
    {
        $startDate .= ' 00:00:00';
        $endDate .= ' 23:59:59';
        return DB::table('ledger_entries AS le')
            ->join('journal_entries AS je', 'je.id', '=', 'le.journal_entry_id')
            ->join('users', 'users.id', '=', 'je.client_id')
            ->join('ledger_accounts AS acc', 'acc.id', '=', 'le.account_id')
            ->where('je.client_id', $clientId)
            ->where(function ($query) use ($startDate, $endDate) {
                $query->where(function ($q) use ($startDate, $endDate) {
                    $q
                        ->whereIn('acc.account_group_id', [AccountGroup::ASSETS, AccountGroup::LIABILITIES, AccountGroup::EQUITY])
                        ->whereBetween('je.date', [$startDate, $endDate]);
                })->orWhere(function ($q) use ($startDate, $endDate) {
                    $q
                        ->whereNotIn('acc.account_group_id', [AccountGroup::ASSETS, AccountGroup::LIABILITIES, AccountGroup::EQUITY])
                        ->whereBetween('je.date', [$startDate, $endDate]);
                });
            })
            ->groupBy('acc.id', 'acc.name', 'acc.account_group_id')
            ->orderBy('acc.id')
            ->select(
                'acc.id AS acc_id',
                'acc.name AS acc_name',
                'acc.account_group_id AS acc_group_id',
                DB::raw('SUM(CASE WHEN le.entry_type_id = ? THEN amount ELSE 0 END) AS debit'),
                DB::raw('SUM(CASE WHEN le.entry_type_id = ? THEN amount ELSE 0 END) AS credit')
            )
            ->setBindings([EntryType::DEBIT, EntryType::CREDIT], 'select')
            ->get();
    }
}
