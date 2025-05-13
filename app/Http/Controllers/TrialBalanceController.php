<?php

namespace App\Http\Controllers;

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
            $data = DB::table('ledger_entries as le')
                ->select(
                    'je.client_id',
                    'users.name',
                    'acc.id AS acc_id',
                    'acc.name AS acc_name',
                    DB::raw('SUM(CASE WHEN le.entry_type_id = ? THEN -amount ELSE amount END) AS balance')
                )
                ->join('journal_entries as je', 'je.id', '=', 'le.journal_entry_id')
                ->join('users', 'users.id', '=', 'je.client_id')
                ->join('ledger_accounts as acc', 'acc.id', '=', 'le.account_id')
                ->where('je.client_id', $request->query('client'))
                ->groupBy('le.account_id', 'je.client_id', 'users.name', 'acc.id', 'acc.name')
                ->orderBy('acc.id')
                ->setBindings([EntryType::CREDIT], 'select')
                ->get();
        }
        return view('ledger.trial-balance', [
            'clients' => $clients,
            'data' => $data ?? null,
        ]);
    }
}
