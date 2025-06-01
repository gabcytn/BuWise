<?php

namespace App\Http\Controllers;

use App\Models\AccountGroup;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class ReportsController extends Controller
{
    public function balanceSheet(Request $request)
    {
        return 'balance sheet';
    }

    public function incomeStatement(Request $request)
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
            $period = $this->getStartAndEndDate($request->period);
            Session::flash('has_data', 'true');
            $data = $this->getIncomeStatementData($selected_client->id);
            $revenues = [];
            $expenses = [];
            foreach ($data as $datum) {
                if ($datum->acc_code >= 400 && $datum->acc_code < 500) {
                    $revenues[] = $datum;
                } else {
                    $expenses[] = $datum;
                }
            }
            return view('reports.income-statement', [
                'clients' => $clients,
                'selected_client' => $selected_client,
                'start_date' => $period[0],
                'end_date' => $period[1],
                'revenues' => $revenues,
                'expenses' => $expenses,
            ]);
        }

        return view('reports.income-statement', [
            'clients' => $clients,
        ]);
    }

    private function getStartAndEndDate(string $period): array
    {
        switch ($period) {
            case 'this_year':
                $start = Carbon::now()->startOfYear();
                $end = Carbon::now()->endOfYear();
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
        return [$start->format('d F Y'), $end->format('d F Y')];
    }

    public function workingPaper(Request $request)
    {
        // TODO: do something
        return 'working paper';
    }

    private function getIncomeStatementData(string $clientId)
    {
        return DB::table('ledger_entries AS le')
            ->join('transactions AS tr', 'tr.id', '=', 'le.transaction_id')
            ->join('users', 'users.id', '=', 'tr.client_id')
            ->join('ledger_accounts AS acc', 'acc.id', '=', 'le.account_id')
            ->where('tr.client_id', '=', $clientId)
            ->where('tr.status', '=', 'approved')
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
