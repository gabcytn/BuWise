<?php

namespace App\Services;

use App\Events\JournalEntryCreated;
use App\Models\EntryType;
use App\Models\JournalEntry;
use App\Models\LedgerEntry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class JournalUpdate
{
    public function __construct(
        public Request $request,
        public JournalEntry $journalEntry
    ) {}

    public function update()
    {
        $this->request->validate([
            'client_id' => ['required', 'uuid:4'],
            'invoice_id' => ['string'],
            'description' => ['required', 'string', 'max:255'],
            'transaction_type_id' => ['required', 'numeric', 'between:1,2'],
            'date' => ['required', 'date', Rule::date()->format('Y-m-d')],
        ]);

        $journalEntry = $this->journalEntry;
        $rowNumbers = $this->getRowNumbers();
        $journalLines = $this->getJournalLines($rowNumbers);

        $totalDebits = array_sum(array_column($journalLines, 'taxed_debit'));
        $totalCredits = array_sum(array_column($journalLines, 'taxed_credit'));

        if ($totalDebits != $totalCredits) {
            return $this->invalidRequest('Journal entries must have equal debits and credits');
        } elseif (count($journalLines) < 2) {
            return $this->invalidRequest('At least two entries are required');
        }

        try {
            DB::beginTransaction();
            $ledger_entries_for_cache = $this->dbTransaction($journalLines);
            DB::commit();

            JournalEntryCreated::dispatch($journalEntry->client_id, $ledger_entries_for_cache);
            Cache::delete('journal-' . $journalEntry->id);

            return redirect()
                ->route('journal-entries.show', $journalEntry)
                ->with('status', 'Journal entry updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::warning('Error updating journal entry: ' . $e->getMessage());
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['database' => 'Failed to update journal entry']);
        }
    }

    private function getRowNumbers(): array
    {
        $rowNumbers = [];
        foreach ($this->request->all() as $key => $value) {
            if (strpos($key, 'row_id_') === 0) {
                $rowNumbers[] = $value;
            }
        }

        return $rowNumbers;
    }

    private function getJournalLines(array $rowNumbers): array
    {
        $request = $this->request;
        $journalLines = [];
        foreach ($rowNumbers as $key) {
            $entry = [
                'account' => $request->input("account_$key"),
                'debit' => (float) $request->input("debit_$key", 0),
                'credit' => (float) $request->input("credit_$key", 0),
            ];

            $tax = $request->input("tax_$key", '');
            $entry['taxed_debit'] = $this->getTaxedValue($tax, $entry['debit']);
            $entry['taxed_credit'] = $this->getTaxedValue($tax, $entry['credit']);

            $journalLines[] = $entry;
        }

        return $journalLines;
    }

    private function getTaxedValue(string $tax, float $originalValue)
    {
        if ($tax !== 'no_tax') {
            $percentage = ((int) $tax) / 100;
            return $originalValue + $originalValue * $percentage;
        }
        return $originalValue;
    }

    private function invalidRequest(string $message): RedirectResponse
    {
        return redirect()
            ->back()
            ->withInput()
            ->withErrors(['message' => $message]);
    }

    private function dbTransaction(array $journalLines): array
    {
        $request = $this->request;
        $journalEntry = $this->journalEntry;
        $journalEntry->date = $request->date;
        $journalEntry->description = $request->description;
        $journalEntry->transaction_type_id = $request->transaction_type_id;
        $journalEntry->save();

        $ledger_entries = LedgerEntry::where('journal_entry_id', $journalEntry->id)->get();
        $ledger_entries_for_cache = [];
        foreach ($journalLines as $idx => $entry) {
            try {
                $ledger_entry = $ledger_entries[$idx];
                $ledger_entry->account_id = $entry['account'];
                $ledger_entry->entry_type_id = $entry['debit'] ? EntryType::LOOKUP['debit'] : EntryType::LOOKUP['credit'];
                $ledger_entry->amount = $entry['debit'] !== 0.0 ? $entry['debit'] : $entry['credit'];
                $ledger_entry->save();

                $debitTax = $entry['taxed_debit'] - $entry['debit'];
                $creditTax = $entry['taxed_credit'] - $entry['credit'];
                if ($debitTax > 0 || $creditTax > 0) {
                    $newTax = LedgerEntry::create([
                        'journal_entry_id' => $journalEntry->id,
                        'account_id' => 19,  // TODO: set to Taxes Payable
                        'entry_type_id' => $entry['debit'] ? EntryType::LOOKUP['debit'] : EntryType::LOOKUP['credit'],
                        'amount' => $entry['debit'] !== 0.0 ? $debitTax : $creditTax
                    ]);
                    $ledger_entries_for_cache[] = $newTax;
                }
            } catch (\Exception $e) {
                $ledger_entry = LedgerEntry::create([
                    'journal_entry_id' => $journalEntry->id,
                    'account_id' => $entry['account'],
                    'entry_type_id' => $entry['debit'] ? EntryType::LOOKUP['debit'] : EntryType::LOOKUP['credit'],
                    'amount' => $entry['debit'] !== 0.0 ? $entry['debit'] : $entry['credit'],
                ]);
            } finally {
                $ledger_entries_for_cache[] = $ledger_entry;
            }
        }

        return $ledger_entries_for_cache;
    }
}
?>
