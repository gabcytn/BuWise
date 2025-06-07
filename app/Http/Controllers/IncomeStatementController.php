<?php

namespace App\Http\Controllers;

use App\Models\AccountGroup;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class IncomeStatementController extends Controller
{
    public function getNetProfitLoss(Request $request)
    {
        return $this->index($request, true);
    }

    public function index(Request $request, $fromBalanceSheet = false)
    {
        $user = $request->user();
        $accId = getAccountantId($user);
        $clients = Cache::remember($accId . '-clients', 3600, function () use ($user) {
            return getClients($user);
        });

        if ($request->query('client') && $request->query('period')) {
            $request->validate([
                'client' => 'required|uuid:4',
                'period' => 'required|in:this_year,this_month,this_week,today,last_week,last_month,all_time',
            ]);
            $selected_client = User::find($request->client);
            $period = getStartAndEndDate($request->period);
            $data = $this->getIncomeStatementData($selected_client->id, $period[0], $period[1]);
            $revenues = [];
            $expenses = [];
            $revenuesTotal = 0;
            $expensesTotal = 0;
            foreach ($data as $datum) {
                if ($datum->acc_code >= 400 && $datum->acc_code < 500) {
                    $revenues[] = $datum;
                    $revenuesTotal += $datum->debit > 0 ? -$datum->debit : $datum->credit;
                } else {
                    $expenses[] = $datum;
                    $expensesTotal += $datum->debit > 0 ? $datum->debit : -$datum->credit;
                }
            }
            if ($fromBalanceSheet)
                return $revenuesTotal - $expensesTotal;
            return view('reports.income-statement', [
                'has_data' => true,
                'clients' => $clients,
                'selected_client' => $selected_client,
                'start_date' => $period[0]->format('d F Y'),
                'end_date' => $period[1]->format('d F Y'),
                'revenues' => $revenues,
                'expenses' => $expenses,
            ]);
        }

        return view('reports.income-statement', [
            'has_data' => false,
            'clients' => $clients,
        ]);
    }

    private function getIncomeStatementData(string $clientId, $startDate, $endDate)
    {
        return DB::table('ledger_entries AS le')
            ->join('transactions AS tr', 'tr.id', '=', 'le.transaction_id')
            ->join('users', 'users.id', '=', 'tr.client_id')
            ->join('ledger_accounts AS acc', 'acc.id', '=', 'le.account_id')
            ->where('tr.client_id', '=', $clientId)
            ->whereIn('tr.status', ['approved', 'archived'])
            ->whereBetween('tr.date', [$startDate, $endDate])
            ->whereIn('acc.account_group_id', [AccountGroup::REVENUE, AccountGroup::EXPENSES])
            ->groupBy('acc.id', 'acc.name', 'acc.account_group_id')
            ->orderBy('acc.code')
            ->select(
                'acc.id AS acc_id',
                'acc.name AS acc_name',
                'acc.code AS acc_code',
                'acc.account_group_id AS acc_group_id',
                DB::raw("SUM(CASE WHEN le.entry_type = 'debit' THEN le.amount ELSE 0 END) AS debit"),
                DB::raw("SUM(CASE WHEN le.entry_type = 'credit' THEN le.amount ELSE 0 END) AS credit")
            )
            ->get();
    }
}
