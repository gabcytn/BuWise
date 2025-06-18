<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AccountGroup;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InsightsController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $accId = getAccountantId($user);
        $clients = Cache::remember("$accId-clients", 3600, function () use ($user) {
            return getClients($user);
        });
        $periods = ['This Year', 'This Month', 'This Week', 'Last Week', 'Last Month', 'Last Year', 'All Time'];
        return view('reports.insights', [
            'has_data' => false,
            'periods' => $periods,
            'clients' => $clients,
        ]);
    }

    public function cashFlow(Request $request, ?User $user = null)
    {
        if (!$user)
            $user = User::find($request->user()->id);
        return $this->getData($user, [1, 2, 3, 4]);
    }

    public function receivables(User $user)
    {
        return $this->getData($user, [5, 6]);
    }

    public function payables(User $user)
    {
        return $this->getData($user, [17, 18, 19, 20, 21, 24, 26]);
    }

    public function profitAndLoss(Request $request, ?User $user = null)
    {
        if (!$user)
            $user = User::find($request->user()->id);
        $period = getStartAndEndDate('this_year');
        $data = DB::table('ledger_entries AS le')
            ->join('transactions AS tr', 'tr.id', '=', 'le.transaction_id')
            ->join('users', 'users.id', '=', 'tr.client_id')
            ->join('ledger_accounts AS acc', 'acc.id', '=', 'le.account_id')
            ->join('account_groups AS acc_group', 'acc_group.id', '=', 'acc.account_group_id')
            ->whereIn('acc.account_group_id', [AccountGroup::REVENUE, AccountGroup::EXPENSES])
            ->where('users.id', '=', $user->id)
            ->whereBetween('tr.date', [$period[0], $period[1]])
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
    }

    private function getData(User $user, array $accountIds)
    {
        $period = getStartAndEndDate('this_year');
        $data = DB::table('ledger_entries AS le')
            ->join('transactions AS tr', 'tr.id', '=', 'le.transaction_id')
            ->join('users', 'users.id', '=', 'tr.client_id')
            ->join('ledger_accounts AS acc', 'acc.id', '=', 'le.account_id')
            ->join('account_groups AS acc_group', 'acc_group.id', '=', 'acc.account_group_id')
            ->whereIn('le.account_id', $accountIds)
            ->where('users.id', '=', $user->id)
            ->whereBetween('tr.date', [$period[0], $period[1]])
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
    }
}
