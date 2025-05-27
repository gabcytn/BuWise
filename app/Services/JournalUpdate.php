<?php

namespace App\Services;

use App\Events\JournalEntryCreated;
use App\Models\EntryType;
use App\Models\JournalEntry;
use App\Models\LedgerAccount;
use App\Models\LedgerEntry;
use App\Models\Tax;
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
            $tax = $request->input("tax_$key");
            $entry = [
                'account' => $request->input("account_$key"),
                'tax_id' => $tax,
                'description' => $request->input("description_$key"),
                'debit' => (float) $request->input("debit_$key", 0),
                'credit' => (float) $request->input("credit_$key", 0),
            ];

            $entry['taxed_debit'] = $this->getTaxedValue($tax, $entry['debit']);
            $entry['taxed_credit'] = $this->getTaxedValue($tax, $entry['credit']);

            $journalLines[] = $entry;
        }

        return $journalLines;
    }

    private function getTaxedValue(string $tax, float $originalValue)
    {
        try {
            if ($tax !== '0') {
                $tax = Tax::find((int) $tax);
                $percentage = ((float) $tax->value) / 100;
                return $originalValue + $originalValue * $percentage;
            }
            return $originalValue;
        } catch (\Exception $e) {
            Log::info('Error calculating tax value: ' . $e->getMessage());
            return $originalValue;
        }
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

        $ledger_entries = LedgerEntry::where('journal_entry_id', $journalEntry->id)
            ->where('account_id', '!=', LedgerAccount::TAX_PAYABLE)
            ->get();
        $ledger_entries_for_cache = [];
        foreach ($journalLines as $idx => $entry) {
            try {
                $ledger_entry = $ledger_entries[$idx];
                $ogTaxId = $ledger_entry->tax_id ?? 0;
                $newTaxId = (int) $entry['tax_id'];
                $hasNewTax = false;
                if (!$ogTaxId && $newTaxId) {
                    $debitTax = $entry['taxed_debit'] - $entry['debit'];
                    $creditTax = $entry['taxed_credit'] - $entry['credit'];
                    $newTax = LedgerEntry::create([
                        'journal_entry_id' => $journalEntry->id,
                        'account_id' => LedgerAccount::TAX_PAYABLE,
                        'entry_type_id' => $entry['debit'] ? EntryType::LOOKUP['debit'] : EntryType::LOOKUP['credit'],
                        'amount' => $entry['debit'] !== 0.0 ? $debitTax : $creditTax
                    ]);
                    $ledger_entries_for_cache[] = $newTax;
                    $hasNewTax = true;
                } else if ($ogTaxId && !$newTaxId) {
                    LedgerEntry::destroy($ledger_entry->tax_ledger_entry_id);
                } else if ($ogTaxId !== $newTaxId && $ogTaxId && $newTaxId) {
                    // TODO: fix this
                    $ledger_entries_for_cache[] = LedgerEntry::destroy($ledger_entry->id);
                    $debitTax = $entry['taxed_debit'] - $entry['debit'];
                    $creditTax = $entry['taxed_credit'] - $entry['credit'];
                    $newTax = LedgerEntry::create([
                        'journal_entry_id' => $journalEntry->id,
                        'account_id' => LedgerAccount::TAX_PAYABLE,
                        'entry_type_id' => $entry['debit'] ? EntryType::LOOKUP['debit'] : EntryType::LOOKUP['credit'],
                        'amount' => $entry['debit'] !== 0.0 ? $debitTax : $creditTax
                    ]);
                }
                $ledger_entry->account_id = $entry['account'];
                $ledger_entry->entry_type_id = $entry['debit'] ? EntryType::LOOKUP['debit'] : EntryType::LOOKUP['credit'];
                $ledger_entry->tax_id = (int) $entry['tax_id'] ?: null;
                $ledger_entry->tax_ledger_entry_id = $hasNewTax ? $newTax->id : null;
                $ledger_entry->description = $entry['description'];
                $ledger_entry->amount = $entry['debit'] !== 0.0 ? $entry['debit'] : $entry['credit'];
                $ledger_entry->save();
            } catch (\Exception $e) {
                Log::info('Error: ' . truncate($e->getMessage(), 150));
                $debitTax = $entry['taxed_debit'] - $entry['debit'];
                $creditTax = $entry['taxed_credit'] - $entry['credit'];
                $isTaxed = false;
                if ($debitTax > 0 || $creditTax > 0) {
                    $isTaxed = true;
                    $newTax = LedgerEntry::create([
                        'journal_entry_id' => $journalEntry->id,
                        'account_id' => LedgerAccount::TAX_PAYABLE,
                        'entry_type_id' => $entry['debit'] ? EntryType::LOOKUP['debit'] : EntryType::LOOKUP['credit'],
                        'amount' => $entry['debit'] !== 0.0 ? $debitTax : $creditTax
                    ]);
                }
                $ledger_entry = LedgerEntry::create([
                    'journal_entry_id' => $journalEntry->id,
                    'tax_id' => $isTaxed ? (int) $entry['tax_id'] : null,
                    'tax_ledger_entry_id' => $isTaxed ? $newTax->id : null,
                    'account_id' => $entry['account'],
                    'entry_type_id' => $entry['debit'] ? EntryType::LOOKUP['debit'] : EntryType::LOOKUP['credit'],
                    'amount' => $entry['debit'] !== 0.0 ? $entry['debit'] : $entry['credit'],
                    'description' => $entry['description'],
                ]);
            } finally {
                $ledger_entries_for_cache[] = $ledger_entry;
            }
        }

        return $ledger_entries_for_cache;
    }
}
?>
