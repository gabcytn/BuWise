<?php

namespace App\Http\Controllers;

use App\Events\TransactionDeleted;
use App\Jobs\ParseExcelUpload;
use App\Models\LedgerAccount;
use App\Models\LedgerEntry;
use App\Models\Role;
use App\Models\Transaction;
use App\Services\JournalIndex;
use App\Services\JournalShow;
use App\Services\JournalStore;
use App\Services\JournalUpdate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class JournalEntryController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        Gate::authorize('viewAny', Transaction::class);
        $subtitle = 'Create journal entries and organize financial records';
        $index = new JournalIndex('General Journal', $subtitle, 'index');
        return $index->index($request);
    }

    /**
     * Show the form for creating a new resource.
     * @return \Illuminate\Contracts\View\View
     */
    public function create(Request $request)
    {
        Gate::authorize('create', Transaction::class);
        $user = $request->user();
        if (!isAuthorized($user))
            abort(404);
        $clients = Cache::remember($user->id . '-clients', 3600, function () use ($user) {
            return getClients($user);
        });

        $accId = $user->role_id === Role::ACCOUNTANT ? $user->id : $user->accountant->id;

        $accounts = LedgerAccount::where('accountant_id', $accId)
            ->orWhere('accountant_id', null)
            ->orderBy('code')
            ->get();

        return view('journal-entries.create', [
            'clients' => $clients,
            'accounts' => $accounts,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        Gate::authorize('create', Transaction::class);
        $store = new JournalStore();
        return $store->store($request);
    }

    /**
     * Display the specified resource.
     * @return \Illuminate\Contracts\View\View
     */
    public function show(Transaction $journalEntry)
    {
        Gate::authorize('view', [$journalEntry, ['journal', 'invoice'], $journalEntry->type]);
        $show = new JournalShow($journalEntry);
        return $show->show($journalEntry);
    }

    /**
     * Show the form for editing the specified resource.
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(Request $request, Transaction $journalEntry)
    {
        Gate::authorize('update', $journalEntry);
        $accounts = LedgerAccount::all();

        $ledgerEntries = LedgerEntry::where('transaction_id', $journalEntry->id)->get();

        return view('journal-entries.edit', [
            'accounts' => $accounts,
            'journal_entry' => $journalEntry,
            'ledger_entries' => $ledgerEntries,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaction $journalEntry)
    {
        Gate::authorize('update', $journalEntry);
        $update = new JournalUpdate($request, $journalEntry);
        return $update->update();
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Transaction $journalEntry)
    {
        Gate::authorize('delete', $journalEntry);
        try {
            TransactionDeleted::dispatch($journalEntry->client_id, $journalEntry->date);
            Log::info($journalEntry->id . ' has been deleted');
            $journalEntry->delete();
            // Transaction::destroy($journalEntry->id);
        } catch (\Exception $e) {
            Log::emergency('Exception while destroying a journal entry');
            Log::emergency($e->getMessage());
            Log::emergency(truncate($e->getTraceAsString(), 300));
        }
        return to_route('journal-entries.index', ['type' => 'all', 'client' => 'all']);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function approve(Transaction $journalEntry)
    {
        Gate::authorize('changeStatus', $journalEntry);
        $journalEntry->status = 'approved';
        $journalEntry->save();

        $arr = [];
        foreach ($journalEntry->ledger_entries as $data) {
            $arr[] = $data;
        }

        return redirect()->back()->with(['status' => 'Journal approved!']);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reject(Transaction $journalEntry)
    {
        Gate::authorize('changeStatus', $journalEntry);
        $journalEntry->status = 'rejected';
        $journalEntry->save();

        $arr = [];
        foreach ($journalEntry->ledger_entries as $data) {
            $arr[] = $data;
        }

        return redirect()->back()->with(['status' => 'Journal rejected!']);
    }

    public function csv(Request $request)
    {
        $request->validate([
            'csv' => ['required', 'mimes:csv,xlsx'],
            'client' => ['required', 'uuid:4'],
            'transaction_type' => ['required', 'in:sales,purchases'],
        ]);
        $filename = $request->file('csv')->store('csv/', 'public');
        ParseExcelUpload::dispatch(basename($filename), $request->client, $request->user()->id, $request->transaction_type);
        return redirect()->back()->with(['status' => 'File uploaded successfully']);
    }

    public function archives(Request $request)
    {
        Gate::authorize('viewAny', Transaction::class);
        $subtitle = "View your clients' transactions from previous fiscal years.";
        $index = new JournalIndex('Archived Journals', $subtitle, 'archives');
        return $index->index($request);
    }
}
