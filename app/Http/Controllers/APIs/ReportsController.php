<?php

namespace App\Http\Controllers\APIs;

use App\Http\Controllers\Controller;
use App\Http\Controllers\IncomeStatementController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;

class ReportsController extends Controller
{
    public function incomeStatement(Request $request)
    {
        $this->validate($request);
        $clientId = $request->user()->id;
        $period = new IncomeStatementController()->getStartAndEndDate($request->period);
        $data = Cache::remember("api-$clientId-reports", 300, function () use ($clientId, $period) {
            Log::info('calculating new reports');
            return $this->getReportsData($clientId, $period[0], $period[1]);
        });

        $revenues = [];
        $expenses = [];
        $revenuesTotal = 0;
        $expensesTotal = 0;
        foreach ($data as $datum) {
            if ($datum->acc_code >= 400 && $datum->acc_code < 500) {
                $revenues[] = $datum;
                $revenuesTotal += $datum->debit > 0 ? -$datum->debit : $datum->credit;
            } else if ($datum->acc_code >= 500) {
                $expenses[] = $datum;
                $expensesTotal += $datum->debit > 0 ? $datum->debit : -$datum->credit;
            }
            $amount = abs($datum->debit - $datum->credit);
            $entryType = $datum->debit > $datum->credit ? 'DR' : 'CR';
            $datum->amount = $amount . ' ' . $entryType;
        }

        return Response::json([
            'revenues' => $revenues,
            'expenses' => $expenses,
            'revenuesTotal' => $revenuesTotal,
            'expensesTotal' => $expensesTotal,
            'net' => $revenuesTotal - $expensesTotal,
        ]);
    }

    public function balanceSheet(Request $request)
    {
        $this->validate($request);
        $clientId = $request->user()->id;
        $period = new IncomeStatementController()->getStartAndEndDate($request->period);
        $data = Cache::remember("api-$clientId-reports", 300, function () use ($clientId, $period) {
            Log::info('calculating new reports');
            return $this->getReportsData($clientId, $period[0], $period[1]);
        });
        $assets = [];
        $liabilities = [];
        $equities = [];
        $revenues = [];
        $expenses = [];
        $revenuesTotal = 0;
        $expensesTotal = 0;
        foreach ($data as $datum) {
            if ($datum->acc_code >= 100 && $datum->acc_code < 200) {
                $assets[] = $datum;
            } else if ($datum->acc_code >= 200 && $datum->acc_code < 300) {
                $liabilities[] = $datum;
            } else if ($datum->acc_code >= 300 && $datum->acc_code < 400) {
                $equities[] = $datum;
            } else if ($datum->acc_code >= 400 && $datum->acc_code < 500) {
                $revenues[] = $datum;
                $revenuesTotal += $datum->debit > 0 ? -$datum->debit : $datum->credit;
            } else {
                $expenses[] = $datum;
                $expensesTotal += $datum->debit > 0 ? $datum->debit : -$datum->credit;
            }
            $amount = abs($datum->debit - $datum->credit);
            $entryType = $datum->debit > $datum->credit ? 'DR' : 'CR';
            $datum->amount = $amount . ' ' . $entryType;
        }
        $equityFromIncomeStatement = $revenuesTotal - $expensesTotal;
        $debit = $equityFromIncomeStatement < 0 ? $equityFromIncomeStatement : 0;
        $credit = $equityFromIncomeStatement > 0 ? $equityFromIncomeStatement : 0;
        $amount = abs($debit - $credit);
        $entryType = $debit > $credit ? 'DR' : 'CR';
        $equities[] = json_decode(json_encode([
            'acc_name' => "Current Year's Earnings",
            'acc_code' => '3xx',
            'acc_group' => 'Equity',
            'debit' => $debit,
            'credit' => $credit,
            'amount' => $amount . ' ' . $entryType
        ]));
        return Response::json([
            'assets' => $assets,
            'liabilities' => $liabilities,
            'equities' => $equities,
        ]);
    }

    private function getReportsData(string $clientId, string $startDate, string $endDate): \Illuminate\Support\Collection
    {
        return DB::table('ledger_entries AS le')
            ->join('transactions AS tr', 'tr.id', '=', 'le.transaction_id')
            ->join('users', 'users.id', '=', 'tr.client_id')
            ->join('ledger_accounts AS acc', 'acc.id', '=', 'le.account_id')
            ->join('account_groups', 'account_groups.id', '=', 'acc.account_group_id')
            ->where('tr.client_id', '=', $clientId)
            ->whereIn('tr.status', ['approved', 'archived'])
            ->whereBetween('tr.date', [$startDate, $endDate])
            ->groupBy('acc.id', 'acc.name', 'acc.account_group_id')
            ->orderBy('acc.code')
            ->select(
                'acc.name AS acc_name',
                'acc.code AS acc_code',
                'account_groups.name AS acc_group',
                DB::raw("SUM(CASE WHEN le.entry_type = 'debit' THEN le.amount ELSE 0 END) AS debit"),
                DB::raw("SUM(CASE WHEN le.entry_type = 'credit' THEN le.amount ELSE 0 END) AS credit")
            )
            ->get();
    }

    private function validate(Request $request)
    {
        $request->validate([
            'period' => 'required|in:this_year,this_month,this_week,today,last_week,last_month,last_year,all_time',
        ]);
    }
}
