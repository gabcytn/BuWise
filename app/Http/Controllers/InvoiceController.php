<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Role;
use App\Models\Tax;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
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

        // TODO: set cache value realistically; initially set to 1 week: must be around 30-60 mins;
        foreach ($invoices as $invoice) {
            $link = Cache::remember($invoice->id . '-invurl', 604800, function () use ($invoice) {
                Log::info('Fetching image from AWS...');
                return Storage::temporaryUrl('invoices/' . $invoice->image, now()->addMinutes(10080));
            });
            $invoice->image = $link;
        }

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

        try {
            DB::beginTransaction();
            $file = $request->file('image');
            $path = $file->store('invoices/', 's3');
            Invoice::create([
                'client_id' => $request->client,
                'image' => basename($path),
                'issue_date' => $request->issue_date,
                'due_date' => $request->due_date ?? null,
                'transaction_type_id' => $request->transaction_type,
                'invoice_number' => $request->invoice_number,
                'supplier' => $request->supplier ?? null,
                'vendor' => $request->vendor ?? null,
                'payment_method' => $request->payment_method,
                'tax_id' => $request->tax !== '0' ? $request->tax : null,
                'discount_type' => $request->discount_type,
                'is_paid' => $request->invoice_status === 'paid'
            ]);
            DB::commit();

            return redirect()
                ->back()
                ->with([
                    'status' => 'Invoice created successfully.'
                ]);
        } catch (\Exception $e) {
            Log::error('Error creating invoice: ' . $e->getMessage());
            Log::error(truncate($e->getTraceAsString(), 100));
            DB::rollBack();
            return redirect()
                ->back()
                ->withErrors([
                    'message' => 'Invoice creation failed.'
                ]);
        }
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
