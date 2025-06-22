<?php

namespace App\Http\Controllers;

use App\Events\TransactionDeleted;
use App\Jobs\ScanInvoiceInWeb;
use App\Models\Role;
use App\Models\Transaction;
use App\Models\User;
use App\Services\InvoiceStore;
use App\Services\InvoiceUpdate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
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
        $accId = getAccountantId($user);
        $clients = Cache::remember("$accId-clients", 3600, function () use ($user) {
            return getClients($user);
        });
        $filters = $request->only(['search', 'client', 'status', 'period']);
        if (!array_key_exists('status', $filters))
            $filters['status'] = 'all';
        if (!array_key_exists('client', $filters))
            $filters['client'] = 'all';
        if (!array_key_exists('search', $filters))
            $filters['search'] = null;
        if (!array_key_exists('period', $filters))
            $filters['period'] = 'all_time';
        $invoices = Transaction::with('client')
            ->whereHas('client', function ($query) use ($accId) {
                $query->where('accountant_id', $accId);
            });
        if ($filters['status'] !== 'all')
            $invoices = $invoices->where('status', '=', $filters['status']);
        if ($filters['client'] !== 'all')
            $invoices = $invoices->where('client_id', '=', $filters['client']);
        if ($filters['search'])
            $invoices = $invoices->where('reference_no', '=', $filters['search']);
        if ($filters['period'] === 'this_year')
            $invoices = $invoices->whereBetween('date', [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()]);

        $invoices = $invoices
            ->where('type', '=', 'invoice')
            ->orderBy('id', 'DESC')
            ->paginate(6)
            ->appends($filters);

        return view('invoices.index', [
            'invoices' => $invoices,
            'clients' => $clients,
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
            'payment_method' => ['required', 'string', 'in:cash,checkings,savings,petty_cash,receivable,payable'],
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
        $image = Cache::remember($invoice->id . '-image', 604800, function () use ($invoice) {
            Log::info('Getting new temp. URL from AWS');
            return Storage::temporaryUrl('invoices/' . $invoice->image, now()->addWeek());
        });
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
            'issue_date' => ['required', 'date', 'after_or_equal:1970-01-01', 'before_or_equal:2999-12-31', Rule::date()->format('Y-m-d')],
            'invoice_number' => ['required', 'numeric'],
            'payment_method' => ['required', 'string', 'in:cash,checkings,savings,petty_cash,receivable,payable'],
            'description' => ['nullable', 'string', 'max:255'],
        ]);
        $helper = new InvoiceUpdate($request, $invoice);
        return $helper->update();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $invoice)
    {
        Storage::delete('invoices/' . $invoice->image);
        TransactionDeleted::dispatch($invoice->client_id, $invoice->date);
        Transaction::destroy($invoice->id);
        return to_route('invoices.index');
    }

    public function scan(Request $request)
    {
        $request->validate([
            'invoice' => ['required', File::image()->max(10000)],
            'transaction_type' => 'required|in:sales,purchases',
            'client' => 'required|uuid:4',
        ]);

        $client = User::find($request->client);
        if (!$client)
            abort(404);

        $filename = time() . '_' . Str::uuid();
        $request->file('invoice')->storeAs('temp/', $filename, 'public');

        ScanInvoiceInWeb::dispatch($request->user(), $client, $filename, $request->transaction_type);
        return redirect()->back()->with('status', 'Success');
    }
}
