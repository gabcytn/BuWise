<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Role;
use App\Models\Tax;
use App\Services\InvoiceStore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\File;
use Illuminate\Validation\Rule;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        Gate::authorize('viewAny', Invoice::class);
        $user = $request->user();
        $accId = $user->role_id === Role::ACCOUNTANT ? $user->id : $user->accountant->id;
        $invoices = Invoice::with('client')
            ->whereHas('client', function ($query) use ($accId) {
                $query->where('accountant_id', $accId);
            })
            ->orderBy('id', 'DESC')
            ->get();

        return view('invoices.index', [
            'invoices' => $invoices,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $user = $request->user();
        $accId = getAccountantId($user);
        $clients = Cache::remember($accId . '-clients', 3600, function () use ($user) {
            return getClients($user);
        });
        $taxes = Tax::where('accountant_id', '=', $accId)
            ->orWhere('accountant_id', '=', null)
            ->get();
        return view('invoices.create', [
            'clients' => $clients,
            'taxes' => $taxes
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'client' => ['required', 'uuid:4'],
            'image' => ['required', File::image()->max(5000)],
            'issue_date' => ['required', 'date', 'after_or_equal:1970-01-01', 'before_or_equal:2999-12-31', Rule::date()->format('Y-m-d')],
            'due_date' => ['nullable', 'date', 'after_or_equal:1970-01-01', 'before_or_equal:2999-12-31', Rule::date()->format('Y-m-d')],
            'transaction_type' => ['required', 'numeric', 'between:1,2'],
            'invoice_number' => ['required', 'numeric'],
            'supplier' => ['nullable', 'string', 'max:255'],
            'vendor' => ['nullable', 'string', 'max:255'],
            'payment_method' => ['required', 'string', 'max:255'],
            'tax' => ['nullable', 'numeric'],
            'discount_type' => ['nullable', 'string', 'max:100'],
            'invoice_status' => ['required']
        ]);

        $invoiceStore = new InvoiceStore($request);
        return $invoiceStore->store();
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice)
    {
        $items = $invoice->invoice_lines;
        $invUrl = Cache::remember($invoice->id . '-image', 604800, function () use ($invoice) {
            Log::info('Getting new temp. URL from AWS');
            return Storage::temporaryUrl('invoices/' . $invoice->image, now()->addWeek());
        });
        $invoice->image = $invUrl;
        return view('invoices.show', [
            'invoice' => $invoice,
            'items' => $items,
        ]);
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
        Storage::delete('invoices/' . $invoice->image);
        Invoice::destroy($invoice->id);
        return to_route('invoices.index');
    }
}
