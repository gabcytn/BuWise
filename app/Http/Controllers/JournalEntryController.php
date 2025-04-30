<?php

namespace App\Http\Controllers;

use App\Models\EntryType;
use App\Models\JournalEntry;
use App\Models\LedgerAccount;
use App\Models\LedgerEntry;
use App\Models\Role;
use App\Models\TransactionType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

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
        if ($user->role_id === Role::ACCOUNTANT) {
            $clients = $user->clients;
        } else {
            $clients = $user->accountant->clients;
        }

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
            'date' => ['required', 'date'],
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
                'transaction_type' => $request->input("type_$rowId"),
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
                'date' => $validated['date'] . ' ' . now()->format('H:i:s')
            ]);

            // Create individual journal lines
            foreach ($journalEntries as $entry) {
                $accountId = substr($entry['account'], 0, 4);
                LedgerEntry::create([
                    'journal_entry_id' => $journalEntry->id,
                    'account_id' => $accountId,
                    'transaction_type_id' => TransactionType::LOOKUP[$entry['transaction_type']],
                    'entry_type_id' => $entry['debit'] ? EntryType::LOOKUP['debit'] : EntryType::LOOKUP['credit'],
                    'amount' => $entry['debit'] !== 0.0 ? $entry['debit'] : $entry['credit'],
                ]);
            }

            DB::commit();

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

        $cache = Cache::get('journal-' . $journalEntry->id, null);
        if ($cache) {
            $results = $cache;
        } else {
            $results = DB::table('ledger_entries')
                ->join('ledger_accounts', 'ledger_accounts.id', '=', 'ledger_entries.account_id')
                ->join('account_groups', 'account_groups.id', '=', 'ledger_accounts.account_group_id')
                ->join('transaction_types', 'transaction_types.id', '=', 'ledger_entries.transaction_type_id')
                ->join('entry_types', 'entry_types.id', '=', 'ledger_entries.entry_type_id')
                ->select(
                    'ledger_entries.id as id',
                    'ledger_accounts.name as account_name',
                    'account_groups.name as account_group_name',
                    'transaction_types.name as transaction_name',
                    DB::raw('CASE WHEN entry_types.name = "debit" THEN amount ELSE NULL END as debit'),
                    DB::raw('CASE WHEN entry_types.name = "credit" THEN amount ELSE NULL END as credit')
                )
                ->where('journal_entry_id', $journalEntry->id)
                ->get();
            Cache::set('journal-' . $journalEntry->id, $results);
        }
        return view('journal-entries.show', [
            'description' => $journalEntry->description,
            'ledgerEntries' => $results,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(JournalEntry $journalEntry)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, JournalEntry $journalEntry)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JournalEntry $journalEntry)
    {
        Gate::authorize('delete', $journalEntry);
        JournalEntry::destroy($journalEntry->id);
        return to_route('journal-entries.index');
    }
}
