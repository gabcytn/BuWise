<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // TODO: Gate::authorization();
        $user = $request->user();
        $invoices = Invoice::with('client')
            ->whereHas('client', function ($query) use ($user) {
                $query->where('accountant_id', $user->id);
            })
            ->get();

        // TODO: set cache value realistically; initially set to 1 week: must be around 30-60 mins;
        $links = Cache::remember($user->id . '-invoices', 604800, function () use ($invoices) {
            $urls = [];
            foreach ($invoices as $invoice) {
                $urls[] = Storage::temporaryUrl('invoices/' . $invoice->image, now()->addMinutes(10080));
            }
            Log::info('Invoice images fetched from AWS successfully!');
            return $urls;
        });

        foreach ($links as $idx => $link) {
            $invoices[$idx]->image = $link;
        }

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
        //
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
