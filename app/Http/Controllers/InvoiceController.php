<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Tax;
use App\Models\Transaction;
use App\Models\User;
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
        Gate::authorize('viewAny', Transaction::class);
        $user = $request->user();
        $accId = $user->role_id === Role::ACCOUNTANT ? $user->id : $user->accountant->id;
        $invoices = Transaction::with('client')
            ->whereHas('client', function ($query) use ($accId) {
                $query->where('accountant_id', $accId);
            })
            ->where('type', '=', 'invoice')
            ->orderBy('id', 'DESC')
            ->paginate(6);

        return view('invoices.index', [
            'invoices' => $invoices,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        Gate::authorize('create', Transaction::class);
        $user = $request->user();
        $accId = getAccountantId($user);
        $clients = Cache::remember($accId . '-clients', 3600, function () use ($user) {
            return getClients($user);
        });
        return view('invoices.create', [
            'clients' => $clients,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('create', Transaction::class);
        $request->validate([
            'client' => ['required', 'uuid:4'],
            'image' => ['required', File::image()->max(5000)],
            'issue_date' => ['required', 'date', 'after_or_equal:1970-01-01', 'before_or_equal:2999-12-31', Rule::date()->format('Y-m-d')],
            'transaction_type' => ['required', 'in:sales,purchases'],
            'invoice_number' => ['required', 'numeric'],
            'description' => ['nullable', 'string', 'max:255'],
            'payment_method' => ['required', 'string', 'in:cash,bank'],
            'tax' => ['nullable', 'numeric'],
            'discount_type' => ['nullable', 'string', 'max:100'],
        ]);

        $invoiceStore = new InvoiceStore($request);
        return $invoiceStore->store();
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $invoice)
    {
        Gate::authorize('view', [$invoice, ['invoice'], $invoice->type]);
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
    public function edit(Request $request, Transaction $invoice)
    {
        $user = $request->user();
        $image = Cache::remember($invoice->id . '-image', 604800, function () use ($invoice) {
            Log::info('Getting new temp. URL from AWS');
            return Storage::temporaryUrl('invoices/' . $invoice->image, now()->addWeek());
        });
        $accId = getAccountantId($user);
        return view('invoices.edit', [
            'invoice' => $invoice,
            'image' => $image,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaction $invoice)
    {
        $request->validate([
            'client' => ['required', 'uuid:4'],
            'issue_date' => ['required', 'date', 'after_or_equal:1970-01-01', 'before_or_equal:2999-12-31', Rule::date()->format('Y-m-d')],
            'transaction_type' => ['required', 'in:sales,purchases'],
            'invoice_number' => ['required', 'numeric'],
            'description' => ['nullable', 'string', 'max:255'],
        ]);
        $invoice->client_id = $request->client;
        $invoice->date = $request->issue_date;
        $invoice->kind = $request->transaction_type;
        $invoice->reference_no = $request->invoice_number;
        $invoice->description = $request->description;
        $invoice->save();

        return to_route('invoices.show', $invoice);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $invoice)
    {
        Storage::delete('invoices/' . $invoice->image);
        Transaction::destroy($invoice->id);
        return to_route('invoices.index');
    }
}
