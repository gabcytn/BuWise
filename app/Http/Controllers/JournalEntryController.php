<?php

namespace App\Http\Controllers;

use App\Models\JournalEntry;
use Illuminate\Http\Request;

class JournalEntryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $entries = JournalEntry::all();
        return view('journal-entries.index', [
            'entries' => $entries,
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
