<?php

namespace App\Http\Controllers;

use App\Models\LedgerAccount;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class WorkingPaperController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        if (!isAuthorized($user))
            abort(404);
        $months = [];
        for ($i = 1; $i <= 12; $i++) {
            $months[$i] = Carbon::create()->month($i)->format('F');
        }
        $accId = getAccountantId($user);
        $clients = Cache::remember($accId . '-clients', 3600, function () use ($user) {
            return getClients($user);
        });
        $accounts = LedgerAccount::where('accountant_id', '=', null)->orWhere('accountant_id', '=', $accId)->get();
        if ($request->query('client') && $request->query('account')) {
            $client = User::find($request->client);
            $account = LedgerAccount::find($request->account);
            if (!$client || !$account)
                abort(404);
            $res = $this->getQuery($request->client, $request->account);
            $data = [];
            foreach ($res as $value) {
                $data[$value->month] = $value;
            }
            $helper = new LedgerAccountController();
            $arr = $helper->getOpeningBalanceForAudit($account->id, $client->id);
            $totalDebits = $arr[0];
            $totalCredits = $arr[1];
            return view('reports.working-paper', [
                'has_data' => true,
                'clients' => $clients,
                'selected_client' => $client,
                'selected_account' => $account,
                'accounts' => $accounts,
                'months' => $months,
                'opening_debits' => $totalDebits,
                'opening_credits' => $totalCredits,
                'data' => $data,
            ]);
        }
        return view('reports.working-paper', [
            'has_data' => false,
            'clients' => $clients,
            'accounts' => $accounts,
        ]);
    }

    private function getQuery($clientId, $accId)
    {
        $startDate = Carbon::now()->startOfYear();
        $endDate = Carbon::now()->endOfYear();
        return DB::table('ledger_entries AS le')
            ->join('transactions AS tr', 'tr.id', '=', 'le.transaction_id')
            ->join('users', 'users.id', '=', 'tr.client_id')
            ->join('ledger_accounts AS acc', 'acc.id', '=', 'le.account_id')
            ->where('tr.client_id', '=', $clientId)
            ->where('tr.deleted_at', '=', null)
            ->whereBetween('tr.date', [$startDate, $endDate])
            ->whereIn('tr.status', ['approved', 'archived'])
            ->where('acc.id', '=', $accId)
            ->groupBy('acc.id', 'acc.name', 'acc.account_group_id', DB::raw('MONTH(tr.date)'))
            ->orderBy('acc.id')
            ->select(
                DB::raw('MONTH(tr.date) AS month'),
                'acc.id AS acc_id',
                'acc.name AS acc_name',
                'acc.account_group_id AS acc_group_id',
                DB::raw("SUM(CASE WHEN le.entry_type = 'debit' THEN le.amount ELSE 0 END) AS debit"),
                DB::raw("SUM(CASE WHEN le.entry_type = 'credit' THEN le.amount ELSE 0 END) AS credit")
            )
            ->get();
    }
}
