<?php

namespace App\Http\Controllers;

use App\Events\JournalEntryCreated;
use App\Models\JournalEntry;
use App\Models\LedgerAccount;
use App\Models\LedgerEntry;
use App\Models\Status;
use App\Models\TransactionType;
use App\Services\JournalIndex;
use App\Services\JournalShow;
use App\Services\JournalStore;
use App\Services\JournalUpdate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use DateTime;

class JournalEntryController extends Controller
{
    private const ITEMS_PER_PAGE = 6;

    /**
     * Display a listing of the resource.
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        Gate::authorize('viewAny', JournalEntry::class);
        $index = new JournalIndex();
        return $index->index($request);
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
        $store = new JournalStore();
        return $store->store($request);
    }

    /**
     * Display the specified resource.
     * @return \Illuminate\Contracts\View\View
     */
    public function show(JournalEntry $journalEntry)
    {
        Gate::authorize('view', $journalEntry);
        $show = new JournalShow($journalEntry);
        return $show->show($journalEntry);
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
        $update = new JournalUpdate($request, $journalEntry);
        return $update->update();
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(JournalEntry $journalEntry)
    {
        Gate::authorize('delete', $journalEntry);
        try {
            Cache::delete('journal-' . $journalEntry->id);

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
        return to_route('journal-entries.index', ['type' => 'all', 'client' => 'all']);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function approve(JournalEntry $journalEntry)
    {
        // TODO: authorize via Gate
        $journalEntry->status_id = Status::APPROVED;
        $journalEntry->save();

        return redirect()->back()->with(['status' => 'Journal approved!']);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reject(JournalEntry $journalEntry)
    {
        // TODO: authorize via Gate
        $journalEntry->status_id = Status::REJECTED;
        $journalEntry->save();

        return redirect()->back()->with(['status' => 'Journal rejected!']);
    }
}
