<?php
namespace App\Services;

use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class JournalShow
{
    public function __construct(
        public Transaction $journalEntry
    ) {}

    public function show()
    {
        $je = $this->journalEntry;
        $results = DB::table('ledger_entries')
            ->join('ledger_accounts', 'ledger_accounts.id', '=', 'ledger_entries.account_id')
            ->join('account_groups', 'account_groups.id', '=', 'ledger_accounts.account_group_id')
            ->select(
                'ledger_entries.id as id',
                'ledger_accounts.name as account_name',
                'ledger_accounts.code as account_code',
                'account_groups.name as account_group_name',
                'ledger_entries.description',
                DB::raw('CASE WHEN entry_type = "debit" THEN amount ELSE NULL END as debit'),
                DB::raw('CASE WHEN entry_type = "credit" THEN amount ELSE NULL END as credit')
            )
            ->where('transaction_id', $this->journalEntry->id)
            ->orderByRaw('ledger_accounts.code ASC')
            ->get();

        return view('journal-entries.show', [
            'journalEntry' => $je,
            'ledgerEntries' => $results,
        ]);
    }
}
?>
