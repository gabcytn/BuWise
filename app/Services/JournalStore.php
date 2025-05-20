<?php

namespace App\Services;

use App\Events\JournalEntryCreated;
use App\Models\EntryType;
use App\Models\Invoice;
use App\Models\JournalEntry;
use App\Models\LedgerEntry;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class JournalStore
{
    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'uuid:4',
            'invoice_id' => ['string', 'nullable'],
            'description' => ['required', 'string', 'max:255'],
            'transaction_type_id' => ['required', 'numeric', 'between:1,2'],
            'date' => ['required', 'date', 'after_or_equal:1970-01-01', 'before_or_equal:2999-12-31'],
        ]);
        if ($request->invoice_id) {
            $inv = Invoice::find($request->invoice_id);
            if (!$inv)
                return redirect()
                    ->back()
                    ->withInput()
                    ->withErrors(['invoice' => 'The provided invoice ID does not exist.']);
        }

        $rowNumbers = [];
        foreach ($request->all() as $key => $value) {
            if (strpos($key, 'row_id_') === 0) {
                $rowNumbers[] = $value;
            }
        }

        $journalEntries = [];
        foreach ($rowNumbers as $rowId) {
            $entry = [
                'account' => $request->input("account_$rowId"),
                'debit' => (float) $request->input("debit_$rowId", 0),
                'credit' => (float) $request->input("credit_$rowId", 0),
            ];

            // Only include rows with actual data
            if ($entry['account'] && ($entry['debit'] > 0 || $entry['credit'] > 0)) {
                $journalEntries[] = $entry;
            }
        }

        $totalDebits = array_sum(array_column($journalEntries, 'debit'));
        $totalCredits = array_sum(array_column($journalEntries, 'credit'));

        if ($totalDebits != $totalCredits) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['balance' => 'Journal entries must have equal debits and credits']);
        }

        if (count($journalEntries) < 2) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['entries' => 'At least two entries are required']);
        }

        try {
            DB::beginTransaction();

            // Create a master journal entry record
            $journalEntry = JournalEntry::create([
                'client_id' => $request->invoice_id ? $inv->client_id : $request->client_id,
                'description' => $request->description ?? null,
                'transaction_type_id' => $request->transaction_type_id,
                'invoice_id' => $request->invoice_id ?? null,
                'status_id' => $request->invoice_id ? Status::PENDING : Status::APPROVED,
                'date' => $request->date . ' ' . now()->format('H:i:s')
            ]);

            $ledgerEntries = [];
            // Create individual journal lines
            foreach ($journalEntries as $entry) {
                $accountId = substr($entry['account'], 0, 4);
                $ledgerEntry = LedgerEntry::create([
                    'journal_entry_id' => $journalEntry->id,
                    'account_id' => $accountId,
                    'entry_type_id' => $entry['debit'] ? EntryType::LOOKUP['debit'] : EntryType::LOOKUP['credit'],
                    'amount' => $entry['debit'] !== 0.0 ? $entry['debit'] : $entry['credit'],
                ]);
                $ledgerEntries[] = $ledgerEntry;
            }

            DB::commit();

            // update cache
            JournalEntryCreated::dispatch($journalEntry->client_id, $ledgerEntries);

            return redirect()
                ->route('journal-entries.index', ['type' => 'all', 'client' => 'all'])
                ->with('success', 'Journal entry created successfully');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['database' => 'Failed to save journal entry']);
        }
    }
}

?>
