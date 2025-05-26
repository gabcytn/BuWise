<?php

namespace App\Services;

use App\Events\JournalEntryCreated;
use App\Models\EntryType;
use App\Models\JournalEntry;
use App\Models\LedgerEntry;
use App\Models\Status;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JournalStore
{
    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'uuid:4',
            'description' => ['required', 'string', 'max:255'],
            'transaction_type_id' => ['required', 'numeric', 'between:1,2'],
            'date' => ['required', 'date', 'after_or_equal:1970-01-01', 'before_or_equal:2999-12-31'],
        ]);

        $rowNumbers = $this->getRowNumbers($request);
        $journalEntries = $this->getJournalEntries($request, $rowNumbers);

        $totalDebits = array_sum(array_column($journalEntries, 'taxed_debit'));
        $totalCredits = array_sum(array_column($journalEntries, 'taxed_credit'));

        if ($totalDebits != $totalCredits)
            return $this->redirectWithErrors('balance', 'Journal entries must have equal debits and credits');
        if (count($journalEntries) < 2)
            return $this->redirectWithErrors('entries', 'At least two entries are required');

        try {
            DB::beginTransaction();

            // Create a master journal entry record
            $journalEntry = JournalEntry::create([
                'client_id' => $request->client_id,
                'description' => $request->description ?? null,
                'transaction_type_id' => $request->transaction_type_id,
                'created_by' => $request->user()->id,
                'status_id' => Status::APPROVED,
                'date' => $request->date . ' ' . now()->format('H:i:s')
            ]);

            $ledgerEntries = [];
            // Create individual journal lines
            foreach ($journalEntries as $entry) {
                $debitTax = $entry['taxed_debit'] - $entry['debit'];
                $creditTax = $entry['taxed_credit'] - $entry['credit'];
                if ($debitTax > 0 || $creditTax > 0) {
                    LedgerEntry::create([
                        'journal_entry_id' => $journalEntry->id,
                        'account_id' => 19,  // TODO: set to Taxes Payable
                        'entry_type_id' => $entry['debit'] ? EntryType::LOOKUP['debit'] : EntryType::LOOKUP['credit'],
                        'amount' => $entry['debit'] !== 0.0 ? $debitTax : $creditTax
                    ]);
                }
                $ledgerEntry = LedgerEntry::create([
                    'journal_entry_id' => $journalEntry->id,
                    'account_id' => $entry['account'],
                    'entry_type_id' => $entry['debit'] ? EntryType::LOOKUP['debit'] : EntryType::LOOKUP['credit'],
                    'amount' => $entry['debit'] !== 0.0 ? $entry['debit'] : $entry['credit'],
                ]);
                $ledgerEntries[] = $ledgerEntry;
            }

            DB::commit();

            // update cache
            JournalEntryCreated::dispatch($journalEntry->client_id, $ledgerEntries);

            return redirect()
                ->route('journal-entries.show', $journalEntry->id)
                ->with('success', 'Journal entry created successfully');
        } catch (\Exception) {
            DB::rollBack();
            return $this->redirectWithErrors('database', 'Failed to save journal entry');
        }
    }

    private function getRowNumbers(Request $request): array
    {
        $rowNumbers = [];
        foreach ($request->all() as $key => $value) {
            if (strpos($key, 'row_id_') === 0) {
                $rowNumbers[] = $value;
            }
        }

        return $rowNumbers;
    }

    private function getJournalEntries(Request $request, array $rowNumbers): array
    {
        $journalEntries = [];
        foreach ($rowNumbers as $rowId) {
            $entry = [
                'account' => $request->input("account_$rowId"),
                'debit' => (float) $request->input("debit_$rowId", 0),
                'credit' => (float) $request->input("credit_$rowId", 0),
            ];

            $tax = $request->input("tax_$rowId", '');
            $entry['taxed_debit'] = $this->getTaxedValue($tax, $entry['debit']);
            $entry['taxed_credit'] = $this->getTaxedValue($tax, $entry['credit']);

            // Only include rows with actual data
            if ($entry['account'] && ($entry['debit'] > 0 || $entry['credit'] > 0)) {
                $journalEntries[] = $entry;
            }
        }
        return $journalEntries;
    }

    private function getTaxedValue(string $tax, float $originalValue)
    {
        if ($tax !== 'no_tax') {
            $percentage = ((int) $tax) / 100;
            return $originalValue + $originalValue * $percentage;
        }
        return $originalValue;
    }

    private function redirectWithErrors($key, $value): RedirectResponse
    {
        return redirect()
            ->back()
            ->withInput()
            ->withErrors([$key => $value]);
    }
}

?>
