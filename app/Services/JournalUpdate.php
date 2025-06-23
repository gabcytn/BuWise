<?php

namespace App\Services;

use App\Models\LedgerEntry;
use App\Models\Transaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class JournalUpdate
{
    public function __construct(
        public Request $request,
        public Transaction $journalEntry
    ) {}

    public function update()
    {
        $this->request->validate([
            'client_id' => ['required', 'uuid:4'],
            'reference_no' => ['nullable', 'string'],
            'description' => ['required', 'string', 'max:255'],
            'reference_no' => ['nullable', 'numeric'],
            'transaction_type' => ['required', 'in:sales,purchases'],
            'date' => ['required', 'date', Rule::date()->format('Y-m-d')],
        ]);

        $journalEntry = $this->journalEntry;
        $rowNumbers = $this->getRowNumbers();
        $journalLines = $this->getJournalLines($rowNumbers);

        $totalDebits = array_sum(array_column($journalLines, 'debit'));
        $totalCredits = array_sum(array_column($journalLines, 'credit'));

        if ($totalDebits != $totalCredits)
            return $this->invalidRequest('Journal entries must have equal debits and credits');
        if (count($journalLines) < 2)
            return $this->invalidRequest('At least two entries are required');

        try {
            DB::beginTransaction();
            $ledger_entries_for_cache = $this->dbTransaction($journalLines);
            DB::commit();

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
                'description' => $request->input("description_$key"),
                'debit' => (float) $request->input("debit_$key", 0),
                'credit' => (float) $request->input("credit_$key", 0),
            ];

            $this->validateJournalLines($entry);
            $journalLines[] = $entry;
        }

        return $journalLines;
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

    private function invalidRequest(string $message): RedirectResponse
    {
        return redirect()
            ->back()
            ->withInput()
            ->withErrors(['message' => $message]);
    }

    private function dbTransaction(array $journalLines)
    {
        $request = $this->request;
        $journalEntry = $this->journalEntry;
        $journalEntry->date = $request->date;
        $journalEntry->description = $request->description;
        $journalEntry->reference_no = $request->reference_no ?? null;
        $journalEntry->kind = $request->transaction_type;
        $journalEntry->save();

        $ledger_entries = LedgerEntry::where('transaction_id', $journalEntry->id)->get();
        foreach ($journalLines as $idx => $entry) {
            $ledger_entry = $ledger_entries[$idx];
            $ledger_entry->account_id = $entry['account'];
            $ledger_entry->entry_type = $entry['debit'] ? 'debit' : 'credit';
            $ledger_entry->description = $entry['description'];
            $ledger_entry->amount = $entry['debit'] !== 0.0 ? $entry['debit'] : $entry['credit'];
            $ledger_entry->save();
        }
    }
}
?>
