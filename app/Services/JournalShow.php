<?php
namespace App\Services;

use App\Models\LedgerAccount;
use App\Models\Transaction;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class JournalShow
{
    public function __construct(
        public Transaction $journalEntry
    ) {}

    public function show()
    {
        $je = $this->journalEntry;
        $results = Cache::rememberForever('journal-' . $je->id, function () {
            Log::info('Calculating journal entry cache...');
            return DB::table('ledger_entries')
                ->join('ledger_accounts', 'ledger_accounts.id', '=', 'ledger_entries.account_id')
                ->join('account_groups', 'account_groups.id', '=', 'ledger_accounts.account_group_id')
                ->leftJoin('taxes', 'taxes.id', '=', 'ledger_entries.tax_id')
                ->select(
                    'ledger_entries.id as id',
                    'ledger_accounts.name as account_name',
                    'ledger_accounts.code as account_code',
                    'account_groups.name as account_group_name',
                    'ledger_entries.description',
                    'taxes.value AS tax_value',
                    DB::raw('CASE WHEN entry_type = "debit" THEN amount ELSE NULL END as debit'),
                    DB::raw('CASE WHEN entry_type = "credit" THEN amount ELSE NULL END as credit')
                )
                ->where('transaction_id', $this->journalEntry->id)
                ->where('ledger_accounts.id', '!=', LedgerAccount::TAX_PAYABLE)
                ->orderByRaw('ledger_accounts.code ASC')
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
