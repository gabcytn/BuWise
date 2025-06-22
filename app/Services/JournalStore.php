<?php

namespace App\Services;

use App\Models\LedgerEntry;
use App\Models\Transaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class JournalStore
{
    public function store(Request $request)
    {
        $request->validate([
            'client_id' => ['required', 'uuid:4'],
            'description' => ['required', 'string', 'max:255'],
            'transaction_type' => ['required', 'in:sales,purchases'],
            'date' => ['required', 'date', 'after_or_equal:1970-01-01', 'before_or_equal:2999-12-31'],
            'reference_no' => ['nullable', 'numeric']
        ]);

        $rowNumbers = $this->getRowNumbers($request);
        $journalEntries = $this->getJournalEntries($request, $rowNumbers);

        $totalDebits = array_sum(array_column($journalEntries, 'debit'));
        $totalCredits = array_sum(array_column($journalEntries, 'credit'));

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
                'status' => $request->date < now()->startOfYear()->format('Y-m-d') ? 'archived' : 'approved',
                'type' => 'journal',
                'kind' => $request->transaction_type,
                'amount' => $totalDebits,
                'date' => $request->date,
                'description' => $request->description ?? null,
                'reference_no' => $request->reference_no ?? null
            ]);

            $ledgerEntries = [];
            // Create individual journal lines
            foreach ($journalEntries as $entry) {
                $ledgerEntry = LedgerEntry::create([
                    'transaction_id' => $journalEntry->id,
                    'account_id' => $entry['account'],
                    'entry_type' => $entry['debit'] ? 'debit' : 'credit',
                    'description' => $entry['description'],
                    'amount' => $entry['debit'] !== 0.0 ? $entry['debit'] : $entry['credit'],
                ]);
                $ledgerEntries[] = $ledgerEntry;
            }

            DB::commit();

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
            $this->validateJournalLines($entry);

            if ($entry['account'] && ($entry['debit'] > 0 || $entry['credit'] > 0)) {
                $journalEntries[] = $entry;
            }
        }
        return $journalEntries;
    }

    private function validateJournalLines(array $item)
    {
        $validator = Validator::make($item, [
            'account' => 'required|numeric',
            'debit' => 'required|numeric',
            'credit' => 'required|numeric',
            'description' => 'nullable|string|max:255',
        ]);

        if ($validator->fails())
            throw new \Exception($validator->errors()->__toString());
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
