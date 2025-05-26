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
        $je = $this->journalEntry;
        $results = Cache::rememberForever('journal-' . $je->id, function () use ($je) {
            Log::info('Calculating journal entry cache...');
            return DB::table('ledger_entries')
                ->join('ledger_accounts', 'ledger_accounts.id', '=', 'ledger_entries.account_id')
                ->join('account_groups', 'account_groups.id', '=', 'ledger_accounts.account_group_id')
                ->join('entry_types', 'entry_types.id', '=', 'ledger_entries.entry_type_id')
                ->leftJoin('taxes', 'taxes.id', '=', 'ledger_entries.tax_id')
                ->select(
                    'ledger_entries.id as id',
                    'ledger_accounts.name as account_name',
                    'ledger_accounts.code as account_code',
                    'account_groups.name as account_group_name',
                    'taxes.value AS tax_value',
                    DB::raw('CASE WHEN entry_types.name = "debit" THEN amount ELSE NULL END as debit'),
                    DB::raw('CASE WHEN entry_types.name = "credit" THEN amount ELSE NULL END as credit')
                )
                ->where('journal_entry_id', $this->journalEntry->id)
                ->where('ledger_accounts.id', '!=', 19)
                ->orderByRaw('ledger_entries.id ASC')
                ->get();
        });

        foreach ($results as $result) {
            $tax_value = $result->tax_value / 100;
            $amount = $result->debit ?? $result->credit;
            $result->tax_value = $tax_value * $amount;
        }

        return view('journal-entries.show', [
            'journalEntry' => $je,
            'ledgerEntries' => $results,
        ]);
    }
}
?>
