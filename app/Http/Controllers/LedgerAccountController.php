<?php

namespace App\Http\Controllers;

use App\Models\LedgerAccount;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class LedgerAccountController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\View;
     */
    public function chartOfAccounts()
    {
        $accounts = LedgerAccount::with('accountGroup')->get();

        return view('ledger.coa', [
            'accounts' => $accounts
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function showAccount(User $user, LedgerAccount $ledgerAccount)
    {
        $data = Cache::rememberForever('coa-' . $user->id . '-' . $ledgerAccount->id, function () use ($user, $ledgerAccount) {
            return DB::table('ledger_entries')
                ->join('ledger_accounts', 'ledger_accounts.id', '=', 'ledger_entries.account_id')
                ->join('account_groups', 'account_groups.id', '=', 'ledger_accounts.account_group_id')
                ->join('entry_types', 'entry_types.id', '=', 'ledger_entries.entry_type_id')
                ->join('journal_entries', 'journal_entries.id', '=', 'ledger_entries.journal_entry_id')
                ->join('users', 'journal_entries.client_id', '=', 'users.id')
                ->select(
                    'journal_entries.id as journal_id',
                    'journal_entries.description as journal_description',
                    'journal_entries.date as journal_date',
                    'users.name as client_name',
                    'users.email as client_email',
                    'ledger_accounts.name as acc_name',
                    'account_groups.name as acc_group',
                    DB::raw('CASE WHEN entry_types.name = "debit" THEN amount ELSE NULL END as debit'),
                    DB::raw('CASE WHEN entry_types.name = "credit" THEN amount ELSE NULL END as credit')
                )
                ->where('ledger_accounts.id', $ledgerAccount->id)
                ->where('journal_entries.client_id', $user->id)
                ->get();
        });
        return view('ledger.show_acc', [
            'account' => $ledgerAccount,
            'user' => $user,
            'data' => $data,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(LedgerAccount $ledgerAccount)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LedgerAccount $ledgerAccount)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LedgerAccount $ledgerAccount)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LedgerAccount $ledgerAccount)
    {
        //
    }
}
