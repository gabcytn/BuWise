<?php

namespace App\Services;

use App\Events\JournalEntryCreated;
use App\Models\EntryType;
use App\Models\LedgerAccount;
use App\Models\LedgerEntry;
use App\Models\Status;
use App\Models\Tax;
use App\Models\Transaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class JournalStore
{
    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'uuid:4',
            'description' => ['required', 'string', 'max:255'],
            'transaction_type' => ['required', 'in:sales,purchases'],
            'date' => ['required', 'date', 'after_or_equal:1970-01-01', 'before_or_equal:2999-12-31'],
            'reference_no' => ['nullable', 'numeric']
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
            $journalEntry = Transaction::create([
                'client_id' => $request->client_id,
                'created_by' => $request->user()->id,
                'status' => 'approved',
                'type' => 'journal',
                'kind' => $request->transaction_type,
                'amount' => 1,  // TODO: calculate max amount
                'date' => $request->date . ' ' . now()->format('H:i:s'),
                'payment_method' => 'cash',  // TODO: request from user
                'description' => $request->description ?? null,
                'reference_no' => $request->reference_no ?? null
            ]);

            $ledgerEntries = [];
            // Create individual journal lines
            foreach ($journalEntries as $entry) {
                $debitTax = $entry['taxed_debit'] - $entry['debit'];
                $creditTax = $entry['taxed_credit'] - $entry['credit'];
                $isTaxed = false;
                if ($debitTax > 0 || $creditTax > 0) {
                    $isTaxed = true;
                    $taxEntry = LedgerEntry::create([
                        'transaction_id' => $journalEntry->id,
                        'account_id' => LedgerAccount::TAX_PAYABLE,  // Taxes Payable
                        'entry_type' => $entry['debit'] ? 'debit' : 'credit',
                        'amount' => $entry['debit'] !== 0.0 ? $debitTax : $creditTax
                    ]);
                }
                $ledgerEntry = LedgerEntry::create([
                    'transaction_id' => $journalEntry->id,
                    'account_id' => $entry['account'],
                    'tax_id' => $isTaxed ? (int) $entry['tax_id'] : null,
                    'tax_ledger_entry_id' => $isTaxed ? $taxEntry->id : null,
                    'entry_type' => $entry['debit'] ? 'debit' : 'credit',
                    'description' => $entry['description'],
                    'amount' => $entry['debit'] !== 0.0 ? $entry['debit'] : $entry['credit'],
                ]);
                $ledgerEntries[] = $ledgerEntry;
            }

            DB::commit();

            // update cache
            JournalEntryCreated::dispatch($journalEntry->client_id, $ledgerEntries);

            return redirect()
                ->route('journal-entries.create')
                ->with('status', 'Journal entry created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::info('Error saving journal entry: ' . $e->getMessage());
            Log::info(truncate($e->getTraceAsString(), 500));
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
                'description' => $request->input("description_$rowId"),
            ];

            $taxId = $request->input("tax_$rowId", '');
            $entry['taxed_debit'] = $this->getTaxedValue($taxId, $entry['debit']);
            $entry['taxed_credit'] = $this->getTaxedValue($taxId, $entry['credit']);
            $entry['tax_id'] = $taxId;

            // Only include rows with actual data
            if ($entry['account'] && ($entry['debit'] > 0 || $entry['credit'] > 0)) {
                $journalEntries[] = $entry;
            }
        }
        return $journalEntries;
    }

    private function getTaxedValue(string $tax, float $originalValue)
    {
        if ($tax !== '0') {
            $tax = Tax::find($tax);
            $percentage = ((int) $tax->value) / 100;
            $withTax = (float) round($originalValue * $percentage, 2);
            return $originalValue + $withTax;
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
