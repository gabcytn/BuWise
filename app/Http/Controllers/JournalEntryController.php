<?php

namespace App\Http\Controllers;

use App\Events\JournalEntryCreated;
use App\Models\EntryType;
use App\Models\JournalEntry;
use App\Models\LedgerAccount;
use App\Models\LedgerEntry;
use App\Models\Role;
use App\Models\Status;
use App\Models\TransactionType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use DateTime;

class JournalEntryController extends Controller
{
    private const ITEMS_PER_PAGE = 6;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        Gate::authorize('viewAny', JournalEntry::class);

        $filter = $request->query('filter');
        if ($user->role_id === Role::ACCOUNTANT && !$filter) {
            $entries = $user
                ->clientsJournalEntries()
                ->withMax('ledgerEntries', 'amount')
                ->with('transactionType')
                ->paginate(JournalEntryController::ITEMS_PER_PAGE);
        } else if ($user->role_id === Role::ACCOUNTANT && $filter) {
            $entries = $user
                ->clientsJournalEntries()
                ->where('journal_entries.client_id', $filter)
                ->withMax('ledgerEntries', 'amount')
                ->paginate(JournalEntryController::ITEMS_PER_PAGE)
                ->appends([
                    'filter' => $filter
                ]);
        } else {
            $entries = $user
                ->accountant
                ->clientsJournalEntries()
                ->withMax('ledgerEntries', 'amount')
                ->paginate(JournalEntryController::ITEMS_PER_PAGE);
        }
        return view('journal-entries.index', [
            'entries' => $entries
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * @return \Illuminate\Contracts\View\View
     */
    public function create(Request $request)
    {
        Gate::authorize('create', JournalEntry::class);
        $user = $request->user();
        $clients = Cache::remember($user->id . '-clients', 3600, function () use ($user) {
            return getClients($user);
        });

        $accounts = LedgerAccount::all();
        $transactionTypes = TransactionType::all();

        return view('journal-entries.create', [
            'clients' => $clients,
            'accounts' => $accounts,
            'transactionTypes' => $transactionTypes,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        Gate::authorize('create', JournalEntry::class);
        $validated = $request->validate([
            'client_id' => ['required', 'uuid:4'],
            'invoice_id' => ['string'],
            'description' => ['required', 'string', 'max:255'],
            'transaction_type_id' => ['required', 'numeric', 'between:1,2'],
            'date' => ['required', 'date', 'after_or_equal:1970-01-01', 'before_or_equal:2999-12-31'],
        ]);

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
                'client_id' => $validated['client_id'],
                'description' => $request->description ?? null,
                'transaction_type_id' => $validated['transaction_type_id'],
                'status_id' => Status::APPROVED,
                'date' => $validated['date'] . ' ' . now()->format('H:i:s')
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
                ->route('journal-entries.index')
                ->with('success', 'Journal entry created successfully');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['database' => 'Failed to save journal entry: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     * @return \Illuminate\Contracts\View\View
     */
    public function show(JournalEntry $journalEntry)
    {
        Gate::authorize('view', $journalEntry);

        $results = Cache::rememberForever('journal-' . $journalEntry->id, function () use ($journalEntry) {
            Log::info('Calculating journal entry cache...');
            return DB::table('ledger_entries')
                ->join('ledger_accounts', 'ledger_accounts.id', '=', 'ledger_entries.account_id')
                ->join('account_groups', 'account_groups.id', '=', 'ledger_accounts.account_group_id')
                ->join('entry_types', 'entry_types.id', '=', 'ledger_entries.entry_type_id')
                ->select(
                    'ledger_entries.id as id',
                    'ledger_accounts.name as account_name',
                    'ledger_accounts.id as account_code',
                    'account_groups.name as account_group_name',
                    DB::raw('CASE WHEN entry_types.name = "debit" THEN amount ELSE NULL END as debit'),
                    DB::raw('CASE WHEN entry_types.name = "credit" THEN amount ELSE NULL END as credit')
                )
                ->where('journal_entry_id', $journalEntry->id)
                ->orderByRaw('ledger_entries.id ASC')
                ->get();
        });

        return view('journal-entries.show', [
            'description' => $journalEntry->description,
            'ledgerEntries' => $results,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(Request $request, JournalEntry $journalEntry)
    {
        Gate::authorize('update', $journalEntry);
        $accounts = LedgerAccount::all();
        $transactionTypes = TransactionType::all();

        $ledgerEntries = LedgerEntry::where('journal_entry_id', $journalEntry->id)->get();

        $date = new DateTime($journalEntry->date);

        return view('journal-entries.edit', [
            'accounts' => $accounts,
            'transactionTypes' => $transactionTypes,
            'journal_entry' => $journalEntry,
            'ledger_entries' => $ledgerEntries,
            'date' => $date->format('Y-m-d'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, JournalEntry $journalEntry)
    {
        Gate::authorize('update', $journalEntry);
        $request->validate([
            'client_id' => ['required', 'uuid:4'],
            'invoice_id' => ['string'],
            'description' => ['required', 'string', 'max:255'],
            'transaction_type_id' => ['required', 'numeric', 'between:1,2'],
            'date' => ['required', 'date', Rule::date()->format('Y-m-d')],
        ]);

        $rowNumbers = [];
        foreach ($request->all() as $key => $value) {
            if (strpos($key, 'row_id_') === 0) {
                $rowNumbers[] = $value;
            }
        }

        $journalEntries = [];
        foreach ($rowNumbers as $key) {
            $entry = [
                'account' => $request->input("account_$key"),
                'debit' => (float) $request->input("debit_$key", 0),
                'credit' => (float) $request->input("credit_$key", 0),
            ];

            $journalEntries[] = $entry;
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

            $journal = JournalEntry::find($journalEntry->id);
            $journal->date = $request->date;
            $journal->description = $request->description;
            $journal->transaction_type_id = $request->transaction_type_id;
            $journal->save();

            $ledger_entries = LedgerEntry::where('journal_entry_id', $journalEntry->id)->get();
            $ledger_entries_for_cache = [];
            foreach ($journalEntries as $key => $entry) {
                $ledger_entry = $ledger_entries[$key];
                $ledger_entry->account_id = $entry['account'];
                $ledger_entry->amount = $entry['debit'];
                $ledger_entry->entry_type_id = $entry['debit'] ? EntryType::LOOKUP['debit'] : EntryType::LOOKUP['credit'];
                $ledger_entry->amount = $entry['debit'] !== 0.0 ? $entry['debit'] : $entry['credit'];
                $ledger_entry->save();
                $ledger_entries_for_cache[] = $ledger_entry;
            }

            DB::commit();

            JournalEntryCreated::dispatch($journalEntry->client_id, $ledger_entries_for_cache);
            Cache::delete('journal-' . $journalEntry->id);

            return redirect()
                ->route('journal-entries.index')
                ->with('success', 'Journal entry created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::warning('Error updating journal entry' . $e->getMessage());
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['database' => 'Failed to update journal entry']);
        }
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(JournalEntry $journalEntry)
    {
        Gate::authorize('delete', $journalEntry);
        try {
            Cache::delete('journal-' . $journalEntry->id);
            DB::table('cache')
                ->where('key', 'like', 'coa-' . $journalEntry->client_id)
                ->delete();

            $arr = [];
            foreach ($journalEntry->ledgerEntries as $data) {
                $arr[] = $data;
            }

            // update coa cache
            JournalEntryCreated::dispatch($journalEntry->client_id, $arr);

            JournalEntry::destroy($journalEntry->id);
        } catch (\Exception $e) {
            Log::emergency('Exception while destroying a journal entry');
            Log::emergency($e->getMessage());
            Log::emergency($e->getTraceAsString());
        }
        return to_route('journal-entries.index');
    }
}
