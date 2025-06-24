<?php

namespace App\Http\Controllers\APIs;

use App\Http\Controllers\Controller;
use App\Jobs\InvoiceReceived;
use App\Models\FailedInvoice;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\File;

class MobileInvoiceController extends Controller
{
    public function index()
    {
        $invoices = Transaction::where('type', '=', 'invoice')
            ->orderBy('id', 'DESC')
            ->get();

        foreach ($invoices as $invoice) {
            $invoice->image = Cache::remember($invoice->id . '-image', 604800, function () use ($invoice) {
                Log::info('Requesting for new temp. url');
                Storage::temporaryUrl('invoices/' . $invoice->image, now()->addWeek());
            });
        }

        return $invoices;
    }

    /*
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        Gate::authorize('create', Transaction::class);
        $request->validate([
            'file' => ['required', File::image()->max(10000)],
            'transaction_type' => ['required', 'string', 'in:sales,purchases']
        ]);
        try {
            $file = $request->file('file');
            $transactionType = $request->transaction_type;
            $filename = time() . '_' . Str::uuid();

            Storage::disk('public')->put("temp/$filename", file_get_contents($file));
            InvoiceReceived::dispatch($filename, $transactionType, $request->user());

            return Response::json([
                'message' => 'Successfully created invoice'
            ], 201);
        } catch (\Exception $e) {
            return Response::json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function callback(Request $request)
    {
        $request->validate([
            'clientId' => 'required|uuid:4',
            'filename' => 'required|string',
            'status' => 'required|in:success,fail',
        ]);

        // only delete temp invoice if it succeeds
        if ($request->status === 'success') {
            Storage::disk('public')->delete('temp/' . $request->status);
            return;
        }

        $failed_invoice = FailedInvoice::create([
            'client_id' => $request->clientId,
            'filename' => $request->filename,
        ]);
        // TODO: notify client
    }

    public function failedInvoices(Request $request)
    {
        $invoices = FailedInvoice::where('client_id', '=', $request->user()->id)->get();
        foreach ($invoices as $invoice) {
            $invoice->image = asset('temp/' . $invoice->filename);
        }

        return Response::json([
            'invoices' => $invoices,
        ]);
    }

    public function resentInvoice(Request $request, FailedInvoice $invoice)
    {
        Storage::delete('temp/' . $invoice->filename);
        Cache::forget($invoice->id . '-image');
        $invoice->delete();
        return response(null, 200);
    }
}
