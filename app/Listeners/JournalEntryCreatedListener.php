<?php

namespace App\Listeners;

use App\Events\JournalEntryCreated;
use App\Models\Status;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class JournalEntryCreatedListener implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct() {}

    /**
     * Handle the event.
     */
    public function handle(JournalEntryCreated $event): void
    {
        $userId = $event->clientId;
        foreach ($event->ledgerEntries as $ledgerEntry) {
            try {
                $result = DB::table('ledger_entries')
                    ->join('ledger_accounts', 'ledger_accounts.id', '=', 'ledger_entries.account_id')
                    ->join('account_groups', 'account_groups.id', '=', 'ledger_accounts.account_group_id')
                    ->join('transactions', 'transactions.id', '=', 'ledger_entries.transaction_id')
                    ->join('users', 'journal_entries.client_id', '=', 'users.id')
                    ->select(
                        'transactions.id as journal_id',
                        'transactions.description as journal_description',
                        'transactions.date as journal_date',
                        'transactions.kind as transaction_type',
                        'users.name as client_name',
                        'users.email as client_email',
                        'ledger_accounts.name as acc_name',
                        'account_groups.name as acc_group',
                        DB::raw('CASE WHEN ledger_entries.entry_type = "debit" THEN amount ELSE NULL END as debit'),
                        DB::raw('CASE WHEN ledger_entries.entry_type = "credit" THEN amount ELSE NULL END as credit')
                    )
                    ->where('ledger_accounts.id', $ledgerEntry->account_id)
                    ->where('transactions.status', '=', 'approved')
                    ->where('transactions.client_id', '=', $userId)
                    ->orderByRaw('transactions.date DESC')
                    ->get();
                Cache::put('coa-' . $userId . '-' . $ledgerEntry->account_id, $result, $seconds = 3600);
            } catch (\Exception $e) {
                Log::emergency('Exception while updating COA cache');
                Log::emergency($e->getMessage());
                Log::emergency($e->getCode());
            }
        }
    }
}
