<?php

namespace App\Http\Controllers\APIs;

use App\Http\Controllers\Controller;
use App\Jobs\ParseInvoiceUpload;
use App\Jobs\SendProcessedInvoiceNotification;
use App\Models\FailedInvoice;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\File;

class MobileInvoiceController extends Controller
{
    public function index(Request $request)
    {
        return $this->invoices($request->user()->id);
    }

    public function invoicesOfClient(string $clientId)
    {
        return $this->invoices($clientId);
    }

    private function invoices(string $userId)
    {
        $invoices = Transaction::where('type', '=', 'invoice')
            ->where('client_id', '=', $userId)
            ->orderBy('id', 'DESC')
            ->get();

        foreach ($invoices as $invoice) {
            $invoice->image = Cache::remember($invoice->id . '-image', 604800, function () use ($invoice) {
                Log::info('Requesting for new temp. url');
                return Storage::temporaryUrl('invoices/' . $invoice->image, now()->addWeek());
            });
        }

        return $invoices;
    }

    /*
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Gate::authorize('create', Transaction::class);
        $request->validate([
            'file' => ['required', File::image()->max(10000)],
            'transaction_type' => ['required', 'string', 'in:sales,purchases']
        ]);
        try {
            $file = $request->file('file');
            $transactionType = $request->transaction_type;
            $filename = time() . '_' . Str::uuid();

            Storage::disk('public')->put("temp/$filename", file_get_contents($file));

            if ($request->is_from_bookkeeper) {
                $is_from_bookkeeper = true;
                $client = User::find($request->client_id);
                $accountant = $request->user();
            } else {
                $is_from_bookkeeper = false;
                $client = $request->user();
                $accountant = User::find(getAccountantId($client));
            }

            ParseInvoiceUpload::dispatch($accountant, $client, $filename, $transactionType, $is_from_bookkeeper);

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
        ]);

        Storage::disk('public')->delete('temp/' . $request->filename);

        $client = User::find($request->clientId);
        $accountant_id = getAccountantId($client);

        $accountant = User::find($accountant_id);

        SendProcessedInvoiceNotification::dispatch($accountant);
        SendProcessedInvoiceNotification::dispatch($client);
    }

    public function failedInvoices(Request $request)
    {
        return $this->getFailedInvoices($request->user()->id);
    }

    public function failedInvoicesOfClient(string $clientId)
    {
        return $this->getFailedInvoices($clientId);
    }

    public function resentInvoice(Request $request, FailedInvoice $invoice)
    {
        Storage::delete('temp/' . $invoice->filename);
        Cache::forget($invoice->id . '-image');
        $invoice->delete();
        return response(null, 200);
    }

    private function getFailedInvoices(string $userId)
    {
        $invoices = FailedInvoice::where('client_id', '=', $userId)->get();
        foreach ($invoices as $invoice) {
            $invoice->image = url('storage/temp/' . $invoice->filename);
        }

        return Response::json([
            'invoices' => $invoices,
        ]);
    }
}
