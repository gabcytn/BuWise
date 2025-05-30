<?php

namespace App\Http\Controllers;

use App\Events\JournalEntryCreated;
use App\Events\TransactionCreated;
use App\Models\LedgerAccount;
use App\Models\LedgerEntry;
use App\Models\Role;
use App\Models\Status;
use App\Models\Tax;
use App\Models\Transaction;
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
        Gate::authorize('viewAny', Transaction::class);
        $index = new JournalIndex();
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
        $clients = Cache::remember($user->id . '-clients', 3600, function () use ($user) {
            return getClients($user);
        });

        $accId = $user->role_id === Role::ACCOUNTANT ? $user->id : $user->accountant->id;
        $taxes = Cache::remember($accId . '-taxes', 3600, function () use ($accId) {
            return Tax::where('accountant_id', $accId)->orWhere('accountant_id', null)->get();
        });

        $accounts = LedgerAccount::all();

        return view('journal-entries.create', [
            'taxes' => $taxes,
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
        Gate::authorize('view', $journalEntry);
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
        $user = $request->user();
        $accounts = LedgerAccount::all();

        $ledgerEntries = LedgerEntry::where('transaction_id', $journalEntry->id)
            ->where('account_id', '!=', LedgerAccount::TAX_PAYABLE)
            ->get();

        $accId = $user->role_id === Role::ACCOUNTANT ? $user->id : $user->accountant->id;
        $taxes = Cache::remember($accId . '-taxes', 3600, function () use ($accId) {
            return Tax::where('accountant_id', $accId)->orWhere('accountant_id', null)->get();
        });
        $date = new DateTime($journalEntry->date);

        return view('journal-entries.edit', [
            'accounts' => $accounts,
            'journal_entry' => $journalEntry,
            'ledger_entries' => $ledgerEntries,
            'date' => $date->format('Y-m-d'),
            'taxes' => $taxes,
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
            Cache::delete('journal-' . $journalEntry->id);

            $arr = [];
            foreach ($journalEntry->ledger_entries as $data) {
                $arr[] = $data;
            }

            // update coa cache
            // JournalEntryCreated::dispatch($journalEntry->client_id, $arr);

            Transaction::destroy($journalEntry->id);
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
        $journalEntry->status_id = Status::APPROVED;
        $journalEntry->save();

        $arr = [];
        foreach ($journalEntry->ledgerEntries as $data) {
            $arr[] = $data;
        }

        // update coa cache
        JournalEntryCreated::dispatch($journalEntry->client_id, $arr);
        return redirect()->back()->with(['status' => 'Journal approved!']);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reject(Transaction $journalEntry)
    {
        Gate::authorize('changeStatus', $journalEntry);
        $journalEntry->status_id = Status::REJECTED;
        $journalEntry->save();

        $arr = [];
        foreach ($journalEntry->ledgerEntries as $data) {
            $arr[] = $data;
        }

        // update coa cache
        JournalEntryCreated::dispatch($journalEntry->client_id, $arr);
        return redirect()->back()->with(['status' => 'Journal rejected!']);
    }
}
