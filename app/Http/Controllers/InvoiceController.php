<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $invoices = Cache::remember($user->id . '-clients-invoices', 3600, function () use ($user) {
            return DB::table('invoices')
                ->join('status', 'status.id', '=', 'invoices.status')
                ->join('users', 'users.id', '=', 'invoices.client_id')
                ->select(
                    'invoices.id as invoice_id',
                    'invoices.invoice_number',
                    'invoices.amount',
                    'status.description'
                )
                ->where('users.accountant_id', $user->id)
                ->get();
        });
        return view('invoices.index', [
            'invoices' => $invoices,
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
        $file = $request->file('file');
        $filename = $file->getClientOriginalName();
        Storage::disk('public')->put("invoices/$filename", file_get_contents($file));
        Log::info($request->all());
        return json_encode([
            'messsage' => 'success',
            'status' => '200'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invoice $invoice)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invoice $invoice)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {
        //
    }
}
