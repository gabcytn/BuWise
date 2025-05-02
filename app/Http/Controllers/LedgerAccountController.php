<?php

namespace App\Http\Controllers;

use App\Models\LedgerAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LedgerAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function chartOfAccounts()
    {
        $accounts = LedgerAccount::with('accountGroup')->paginate(10);

        return view('ledger.coa', [
            'accounts' => $accounts
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(LedgerAccount $ledgerAccount)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LedgerAccount $ledgerAccount)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LedgerAccount $ledgerAccount)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LedgerAccount $ledgerAccount)
    {
        //
    }
}
