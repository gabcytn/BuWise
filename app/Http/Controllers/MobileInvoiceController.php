<?php

namespace App\Http\Controllers;

use App\Events\ParseInvoice;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\File;

class MobileInvoiceController extends Controller
{
    /*
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        Gate::authorize('create', Invoice::class);
        $request->validate([
            'file' => ['required', File::image()->max(10000)],
            'transaction_type' => ['required', 'string', 'in:Sales,Purchases']
        ]);
        try {
            $file = $request->file('file');
            $transactionType = $request->transaction_type;
            $filename = $this->storeImageToAws($file);

            $invoice = Invoice::create([
                'client_id' => $request->user()->id,
                'image' => $filename,
            ]);

            // temporarily store image locally for invoice parser access.
            Storage::disk('public')->put("invoices/$filename", file_get_contents($file));
            ParseInvoice::dispatch($filename, $file->getMimeType(), $invoice->id, $transactionType);

            return Response::json([
                'message' => 'Successfully created invoice'
            ], 201);
        } catch (\Exception $e) {
            return Response::json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    private function storeImageToAws($file): string
    {
        $path = $file->store('invoices/', 's3');
        return basename($path);
    }
}
