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
        Gate::authorize('update', $this->journalEntry);
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

        $totalDebits = array_sum(array_column($journalLines, 'debit'));
        $totalCredits = array_sum(array_column($journalLines, 'credit'));

        if ($totalDebits != $totalCredits) {
            return $this->invalidRequest('Journal entries must have equal debits and credits');
        } elseif (count($journalLines) < 2) {
            return $this->invalidRequest('At least two entries are required');
        }

        try {
            DB::beginTransaction();
            $ledger_entries_for_cache = $this->dbTransaction($journalEntry, $journalLines);
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

            $journalLines[] = $entry;
        }

        return $journalLines;
    }

    private function invalidRequest(string $message): RedirectResponse
    {
        return redirect()
            ->back()
            ->withInput()
            ->withErrors(['message' => $message]);
    }

    private function dbTransaction(JournalEntry $journalEntry, array $journalLines): array
    {
        $request = $this->request;
        $journal = JournalEntry::find($journalEntry->id);
        $journal->date = $request->date;
        $journal->description = $request->description;
        $journal->transaction_type_id = $request->transaction_type_id;
        $journal->save();

        $ledger_entries = LedgerEntry::where('journal_entry_id', $journalEntry->id)->get();
        $ledger_entries_for_cache = [];
        foreach ($journalLines as $key => $entry) {
            try {
                $ledger_entry = $ledger_entries[$key];
                $ledger_entry->account_id = $entry['account'];
                $ledger_entry->entry_type_id = $entry['debit'] ? EntryType::LOOKUP['debit'] : EntryType::LOOKUP['credit'];
                $ledger_entry->amount = $entry['debit'] !== 0.0 ? $entry['debit'] : $entry['credit'];
                $ledger_entry->save();
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
