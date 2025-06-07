<?php

namespace App\Http\Controllers;

use App\Models\AccountGroup;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BalanceSheetController extends Controller
{
    public function index(Request $request)
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
            $data = Cache::remember(
                $selected_client->id . '-balance-sheet-' . $request->period,
                300,
                function () use ($selected_client, $period) {
                    Log::info('Calculating new balance sheet (web)...');
                    return $this->getIncomeStatementData($selected_client->id, $period[0], $period[1]);
                }
            );
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
            return view('reports.balance-sheet', [
                'has_data' => true,
                'clients' => $clients,
                'selected_client' => $selected_client,
                'start_date' => $period[0]->format('d F Y'),
                'end_date' => $period[1]->format('d F Y'),
                'assets' => $assets,
                'liabilities' => $liabilities,
                'equities' => $equities,
                'equity_from_income_statement' => $equityFromIncomeStatement,
            ]);
        }

        return view('reports.balance-sheet', [
            'has_data' => false,
            'clients' => $clients,
        ]);
    }

    private function getIncomeStatementData(string $clientId, $startDate, $endDate): \Illuminate\Support\Collection
    {
        return DB::table('ledger_entries AS le')
            ->join('transactions AS tr', 'tr.id', '=', 'le.transaction_id')
            ->join('users', 'users.id', '=', 'tr.client_id')
            ->join('ledger_accounts AS acc', 'acc.id', '=', 'le.account_id')
            ->where('tr.client_id', '=', $clientId)
            ->whereIn('tr.status', ['approved', 'archived'])
            ->whereBetween('tr.date', [$startDate, $endDate])
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
