<?php

namespace App\Listeners;

use App\Events\JournalEntryCreated;
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
                    ->where('ledger_accounts.id', $ledgerEntry->account_id)
                    ->where('journal_entries.client_id', $userId)
                    ->orderByRaw('journal_entries.date DESC')
                    ->get();
                Cache::set('coa-' . $userId . '-' . $ledgerEntry->account_id, $result);
            } catch (\Exception $e) {
                Log::emergency('Exception while updating COA cache');
                Log::emergency($e->getMessage());
                Log::emergency($e->getCode());
            }
        }
    }
}
