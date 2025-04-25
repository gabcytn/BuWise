<?php

namespace App\Http\Controllers;

use App\Models\JournalEntry;
use App\Models\LedgerAccount;
use App\Models\Role;
use App\Models\TransactionType;
use Illuminate\Http\Request;

class JournalEntryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return 'index';
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // TODO: authorize using Gate
        $user = $request->user();
        if ($user->role_id === Role::ACCOUNTANT) {
            $clients = $user->clients;
        } else {
            $clients = $user->accountant->clients;
        }

        $accounts = LedgerAccount::all();
        $transactionTypes = TransactionType::all();

        return view('journal-entries.index', [
            'clients' => $clients,
            'accounts' => $accounts,
            'transactionTypes' => $transactionTypes,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => ['required', 'uuid:4'],
            'invoice_id' => ['string'],
            'description' => ['max:255'],
            'date' => ['required', 'date'],
        ]);

        // var_dump($request->all());

        $creditsSum = 0;
        $debitsSum = 0;

        foreach ($request->credit as $credit) {
            $creditsSum += $credit;
        }
        foreach ($request->debit as $debit) {
            $debitsSum += $debit;
        }

        if ($creditsSum != $debitsSum) {
            $request->session()->flash('status', 'Make sure debits and credits balance.');
            return to_route('journal-entries.create');
        } else {
            return 'NO ERROR';
        }

        // return to_route('journal-entries.create');
    }

    /**
     * Display the specified resource.
     */
    public function show(JournalEntry $journalEntry)
    {
        //
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
        //
    }
}
