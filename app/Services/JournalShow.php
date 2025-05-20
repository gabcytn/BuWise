<?php
namespace App\Services;

use App\Models\JournalEntry;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class JournalShow
{
    public function __construct(
        public JournalEntry $journalEntry
    ) {}

    public function show()
    {
        Gate::authorize('view', $this->journalEntry);

        $je = $this->journalEntry;
        $results = Cache::rememberForever('journal-' . $je->id, function () use ($je) {
            Log::info('Calculating journal entry cache...');
            return DB::table('ledger_entries')
                ->join('ledger_accounts', 'ledger_accounts.id', '=', 'ledger_entries.account_id')
                ->join('account_groups', 'account_groups.id', '=', 'ledger_accounts.account_group_id')
                ->join('entry_types', 'entry_types.id', '=', 'ledger_entries.entry_type_id')
                ->select(
                    'ledger_entries.id as id',
                    'ledger_accounts.name as account_name',
                    'ledger_accounts.id as account_code',
                    'account_groups.name as account_group_name',
                    DB::raw('CASE WHEN entry_types.name = "debit" THEN amount ELSE NULL END as debit'),
                    DB::raw('CASE WHEN entry_types.name = "credit" THEN amount ELSE NULL END as credit')
                )
                ->where('journal_entry_id', $this->journalEntry->id)
                ->orderByRaw('ledger_entries.id ASC')
                ->get();
        });

        return view('journal-entries.show', [
            'journalEntry' => $je,
            'ledgerEntries' => $results,
        ]);
    }
}
?>
